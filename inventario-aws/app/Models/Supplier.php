<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = ['name', 'email', 'phone_number', 'address', 'image'];

    /**
     * Products relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * This method customizes what gets indexed by Laravel Scout.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'phone_number'  => $this->phone_number,
            'address'       => $this->address,
        ];
    }
}
