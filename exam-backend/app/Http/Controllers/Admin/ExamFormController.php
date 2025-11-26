<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExamForm;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ExamFormController extends Controller
{
    public function index()
    {
        $forms = Auth::user()->exam_forms()->latest()->get();
        return view('admin.exam.forms', compact('forms'));
    }

    public function create()
    {
        return view('admin.exam.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email',
            'course'    => 'required|string|max:255',
        ]);

        $form = ExamForm::create([
            'user_id'   => Auth::id(),
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'course'    => $request->course,
            'is_paid'   => false,
        ]);

        return redirect()->route('admin.exam.pay', $form->id)
            ->with('success', 'Form submitted! Please complete payment.');
    }

    public function pay($id)
    {
        $form = ExamForm::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($form->is_paid) {
            return redirect()->route('exam.forms');
        }

        return view('admin.exam.pay', compact('form'));
    }

    // API: Create Razorpay Order
    public function createOrder(Request $request)
    {
        $request->validate(['form_id' => 'required|exists:exam_forms,id']);

        $form = ExamForm::where('id', $request->form_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($form->is_paid) {
            return response()->json(['message' => 'Already paid'], 400);
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $order = $api->order->create([
            'amount'   => 50000, // ₹500 in paise
            'currency' => 'INR',
            'receipt'  => 'exam_' . $form->id
        ]);

        return response()->json([
            'success'   => true,
            'order_id'  => $order->id,
            'amount'    => 50000,
            'key'       => env('RAZORPAY_KEY'),
            'name'      => $form->full_name,
            'email'     => $form->email,
        ]);
    }

    // API: Verify Payment & Generate PDF
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'form_id'              => 'required|exists:exam_forms,id',
            'razorpay_order_id'    => 'required',
            'razorpay_payment_id'  => 'required',
            'razorpay_signature'   => 'required',
        ]);

        $form = ExamForm::where('id', $request->form_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($form->is_paid) {
            return response()->json(['message' => 'Already paid'], 400);
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            // Create Payment Record
            $payment = Payment::create([
                'exam_form_id'    => $form->id,
                'payment_gateway' => 'razorpay',
                'transaction_id'  => $request->razorpay_payment_id,
                'amount'          => 50000,
                'status'          => 'success',
            ]);

            $form->is_paid = true;
            $form->save();

            // Generate PDF Receipt
            $pdf = Pdf::loadView('pdf.receipt', compact('form', 'payment'));
            $fileName = 'receipt_' . $payment->id . '.pdf';
            $path = 'receipts/' . $fileName;
            Storage::disk('public')->put($path, $pdf->output());

            $payment->pdf_path = $path;
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'receipt_url' => route('receipt.download', $payment->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 400);
        }
    }

    public function receipt($paymentId)
    {
        $payment = Payment::with('form')->findOrFail($paymentId);
        if ($payment->form->user_id !== Auth::id()) abort(403);

        return view('admin.receipt.show', compact('payment'));
    }

    public function downloadReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        if ($payment->form->user_id !== Auth::id()) abort(403);

        return response()->download(storage_path('app/public/' . $payment->pdf_path));
    }
}