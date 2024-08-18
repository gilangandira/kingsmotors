<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Location;
use App\Models\IngoingItem;
use App\Models\OutgoingItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function outgoingItems()
    {
        return $this->hasMany(OutgoingItem::class);
    }
    public function ingoingItems()
    {
        return $this->hasMany(IngoingItem::class);
    }
    public function invoiceItem()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
