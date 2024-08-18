<?php

namespace App\Models;

use App\Models\User;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    protected $fillable = ['total_amount', 'user_id', 'created_at', 'updated_at'];

    // Definisikan relasi dengan InvoiceItem
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    // Definisikan relasi dengan User jika diperlukan
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
