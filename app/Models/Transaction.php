<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'remaining_amount',
        'due_on',
        'vat',
        'vat_included',
        'payer',
        'category_id',
        'subcategory_id'
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function paymentRecords()
    {
        return $this->hasMany(PaymentRecord::class, 'transaction_id');
    }
}
