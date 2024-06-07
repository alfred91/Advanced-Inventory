<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'name', 'description', 'image', 'quantity', 'price', 'category_id', 'supplier_id', 'minimum_stock', 'discount'
    ];

    /**
     * Category relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Supplier relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Orders relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    /**
     * Inventory transactions relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * This method is used by Laravel Scout to determine which data should be indexed.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $this->loadMissing(['category', 'supplier']);

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'quantity'       => $this->quantity,
            'price'          => $this->price,
            'category_name'  => $this->category_name,
            'supplier_name'  => $this->supplier_name,
        ];
    }

    /**
     * Funcion para determinar si el Stock esta por debajo del minimo
     */
    public function isStockBelowMinimum(): bool
    {
        return $this->quantity < $this->minimum_stock;
    }

    /**
     * Get category name with fallback.
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return $this->category->name ?? 'N/A';
    }

    /**
     * Get supplier name with fallback.
     *
     * @return string
     */
    public function getSupplierNameAttribute()
    {
        return $this->supplier->name ?? 'N/A';
    }
}
