<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamForm extends Model
{
    
    protected $fillable = [
        'user_id', 'full_name', 'email', 'course', 'is_paid'
    ];

      protected $dates = [
        'created_at',
        'updated_at',
    ];

      protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

}
