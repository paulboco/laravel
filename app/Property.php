<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $primary_key = 'property_id';
    public $timestamps = false;

    /**
     * Get the property's full street address.
     *
     * @return string
     */
    public function fullStreetAddress()
    {
        return sprintf('%s %s %s',
            $this->attributes['property_street_number'],
            $this->attributes['property_street_name'],
            $this->attributes['property_apt_no']
        );
    }

    /**
     * Scope - Sort by street address.
     */
    public function scopeSortByStreetAddress($query)
    {
        return $query->addSelect('*', \DB::raw("LPAD(property_apt_no,10,'') as address_sort"))
                     ->orderBy('property_street_name')
                     ->orderBy('property_street_number')
                     ->orderBy('address_sort');
    }

    /**
     * Scope - Where active.
     */
    public function scopeWhereActive($query)
    {
        return $query->where('property_active', 'Yes');
    }
}
