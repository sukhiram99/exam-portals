<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\ExamForm;
use App\Models\Payment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use App\Http\Requests\SubmitExamFormRequest;
use App\Http\Requests\CreateRazorpayOrderRequest;
use App\Http\Requests\VerifyPaymentRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Errors\SignatureVerificationError;

class ExamApiController extends BaseController
{
    /**
     */
    public function submitForm(SubmitExamFormRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $form = ExamForm::create([
                'user_id'   => $request->user()->id,
                'full_name' => $data['full_name'],
                'email'     => $data['email'],
                'course'    => $data['course'],
                'is_paid'   => false,
            ]);

            DB::commit();

            Log::channel('exam_portals')->info("Exam form submitted for user_id {$request->user()->id}");

            return $this->sendResponse(['form_id' => $form->id], 'Exam form submitted successfully.');

        } catch (QueryException $e) {
            DB::rollBack();
            Log::channel('exam_portals')->error('QueryException (submitForm): ' . $e->getMessage(), [
                'request_data' => $request->all(),
            ]);

            return $this->sendError('Database Error', $e->getMessage(), 500);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('exam_portals')->error('Exception (submitForm): ' . $e->getMessage(), [
                'request_data' => $request->all(),
            ]);

            return $this->sendError('Unable to submit exam form', $e->getMessage(), 500);
        }
    }

    /**
     * Step 2: Create Razorpay Order
     */
    public function createRazorpayOrder(CreateRazorpayOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $order = $api->order->create([
                'receipt'  => 'exam_form_' . $data['form_id'],
                'amount'   => $data['amount'] * 100, // paise
                'currency' => 'INR',
            ]);

            DB::commit();

            Log::channel('exam_portals')->info("Razorpay order created for form_id {$data['form_id']}");

            return $this->sendResponse([
                'order_id' => $order['id'],
                'receipt'  => $order['receipt'],
                'amount'   => $order['amount'],
                'currency' => 'INR',
            ], 'Razorpay order created successfully.');

        } catch (QueryException $e) {
            DB::rollBack();
            Log::channel('exam_portals')->error('QueryException (createOrder): ' . $e->getMessage());

            return $this->sendError('Database Error', $e->getMessage(), 500);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('exam_portals')->error('Exception (createOrder): ' . $e->getMessage());

            return $this->sendError('Failed to create Razorpay order', $e->getMessage(), 500);
        }
    }

    /**
     * Step 3: Verify Payment
     */
   public function verifyPayment(VerifyPaymentRequest $request)
{
    DB::beginTransaction();
    try {
        $data = $request->validated();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // CRITICAL: These 3 fields must match exactly what Razorpay sent
        $attributes = [
            'razorpay_order_id'   => $data['razorpay_order_id'],     // ← Must be this exact name
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature'  => $data['razorpay_signature'],
        ];

        // This will throw SignatureVerificationError if invalid
        $api->utility->verifyPaymentSignature($attributes);

        // Fetch original order to get correct amount (never trust frontend)
        $razorpayOrder = $api->order->fetch($data['razorpay_order_id']);

        $examForm = ExamForm::findOrFail($data['form_id']);

        if ($examForm->is_paid) {
            return $this->sendError('Exam form already paid', [], 400);
        }

        // Create payment record
        $payment = Payment::create([
            'exam_form_id'    => $examForm->id,
            'payment_gateway' => 'Razorpay',
            'transaction_id'  => $data['razorpay_payment_id'],
            'amount'          => $razorpayOrder->amount,        // ← Always in paise
            'status'          => 'success'
        ]);

        $examForm->is_paid = true;
        $examForm->save();

        // Generate PDF Receipt (dompdf)
        $pdf = Pdf::loadView('pdf.receipt', [
            'payment'  => $payment,
            'examForm' => $examForm
        ])->setPaper('A4');

        $fileName = 'receipt_' . $payment->id . '.pdf';
        $path = 'receipts/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());

        $payment->pdf_path = $path;
        $payment->save();

        DB::commit();

        return $this->sendResponse([
            'receipt_url'   => Storage::url($path),
            'download_url'  => route('payment.receipt.download', $payment->id),
        ], 'Payment successful! Receipt generated.');

    } catch (SignatureVerificationError $e) {
        DB::rollBack();
        Log::channel('exam_portals')->warning('Razorpay signature mismatch', $request->all());
        return $this->sendError('Invalid payment signature', [], 400);

    } catch (Exception $e) {
        DB::rollBack();
        Log::channel('exam_portals')->error('Payment verification failed: ' . $e->getMessage());
        return $this->sendError('Payment failed', $e->getMessage(), 500);
    }
}

}