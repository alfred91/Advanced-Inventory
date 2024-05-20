<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Order extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = ['customer_id', 'order_date', 'total_amount', 'status', 'notification_sent'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details')->withPivot('quantity', 'unit_price')->withTimestamps();
    }

    public function toSearchableArray()
    {
        $this->loadMissing(['customer', 'products']);

        $productsSummary = $this->products->map(function ($product) {
            return [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'quantity'     => $product->pivot->quantity,
                'unit_price'   => $product->pivot->unit_price,
            ];
        });

        return [
            'id'            => $this->id,
            'order_date'    => $this->order_date,
            'total_amount'  => $this->total_amount,
            'status'        => $this->status,
            'customer_id'   => $this->customer_id,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'products'      => $productsSummary,
        ];
    }

    public function getTranslatedStatusAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => ucfirst($this->status),
        };
    }
}
