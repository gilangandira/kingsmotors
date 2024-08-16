<?php

namespace App\Models;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutgoingItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
