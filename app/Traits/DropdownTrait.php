<?php

namespace App\Traits;

use App\Models\DropDown;
use App\Models\DropDownValue;

trait DropdownTrait
{
    /**
     * Get dropdown values by ID
     */
    public function getDropdownValuesByCode(string $code)
    {
        $dropdown = DropDown::where('code', $code)->first();

        if (! $dropdown) {
            return collect(); // or throw exception/log error
        }

        $values = DropDownValue::where('drop_down_id', $dropdown->id)
            ->orderBy('value')
            ->get(['id', 'value']);

            return $values;
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
            'titles'            => $this->getDropdownValuesByCode('TITLE'),
            'preferredContact'    => $this->getDropdownValuesByCode('PREFERRED_CONTACT'),
            'contactMethods'    => $this->getDropdownValuesByCode('CONTACT_METHOD'),
            'contactTypes'      => $this->getDropdownValuesByCode('CONTACT_TYPE'),
            'paymentMethods'    => $this->getDropdownValuesByCode('PAYMENT_METHOD'),
            'categories'        => $this->getDropdownValuesByCode('VISIT_CATEGORY'),
        ];
    }
}
