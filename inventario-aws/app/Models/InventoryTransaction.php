<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id', 'user_id', 'transaction_type',
        'quantity', 'before_quantity', 'after_quantity',
        'reason', 'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public static function record(string $type, \App\Models\Product $product, int $quantity, ?string $reason = null, ?string $description = null): self
    {
        $before = $product->getOriginal('quantity') ?? $product->quantity;
        $after  = $product->quantity;

        return self::create([
            'product_id'      => $product->id,
            'user_id'         => auth()->id(),
            'transaction_type'=> $type,
            'quantity'        => $quantity,
            'before_quantity' => $before,
            'after_quantity'  => $after,
            'reason'          => $reason,
            'description'     => $description,
        ]);
    }
}
