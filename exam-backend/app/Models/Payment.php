<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    public $table = 'payments';

    protected $fillable = [
        'exam_form_id',
        'payment_gateway',
        'transaction_id',
        'amount',
        'status',
        'razorpay_order_id',
        'razorpay_signature',
        'pdf_path',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

      protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

     public function form()
    {
        return $this->belongsTo(ExamForm::class);
    }

    
}
