<?php

namespace App\Models;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IngoingItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // Set the table name
    protected $table = 'ingoing';
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
