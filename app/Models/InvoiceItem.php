<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'inventory_id',
        'quantity',
        'price',
    ];
    protected $table = 'invoice_items';
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
