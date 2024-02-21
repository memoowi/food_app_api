<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function transaction():BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function menu():BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
