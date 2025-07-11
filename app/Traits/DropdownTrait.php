<?php

namespace App\Traits;

use App\Models\DropDown;
use App\Models\DropDownValue;

trait DropdownTrait
{
    /**
     * Get dropdown values by ID
     */
    public function getDropdownValues(string $code)
    {
        $dropdown = DropDown::where('code', $code)->first();

        if (! $dropdown) {
            return collect(); // or throw exception/log error
        }

        $values = DropDownValue::where('drop_down_id', $dropdown->id)
            ->orderBy('value')
            ->get();
    }

    public function getDropdownOptions(string $code)
    {
        $dropdown = DropDown::where('code', $code)->first();

        if (! $dropdown) {
            return [];
        }

        return  DropDownValue::where('drop_down_id', $dropdown->id)
            ->orderBy('value')
            ->pluck('value', 'id')
            ->toArray();
    }


    /**
     * Get all commonly used dropdowns in one call
     */
    public function getCommonDropdowns()
    {
        return [
            'titles'            => $this->getDropdownValuesByCode('TITLES'),
            'contactMethods'    => $this->getDropdownValuesByCode('CONTACT_METHODS'),
            'contactTypes'      => $this->getDropdownValuesByCode('CONTACT_TYPES'),
            'paymentMethods'    => $this->getDropdownValuesByCode('PAYMENT_METHODS'),
            'categories'        => $this->getDropdownValuesByCode('CATEGORIES'),
        ];
    }
}
