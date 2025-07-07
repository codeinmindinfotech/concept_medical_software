<?php

namespace App\Traits;

use App\Models\DropDownValue;

trait DropdownTrait
{
    /**
     * Get dropdown values by ID
     */
    public function getDropdownValues(int $dropdownId)
    {
        return DropDownValue::where('drop_down_id', $dropdownId)
            ->orderBy('value')
            ->get();
    }

    /**
     * Get all commonly used dropdowns in one call
     */
    public function getCommonDropdowns()
    {
        return [
            'titles' => $this->getDropdownValues(1),           // Titles
            'contactMethods' => $this->getDropdownValues(2),   // Preferred Contact Methods
            'contactTypes' => $this->getDropdownValues(3),           // Titles
            'paymentMethods' => $this->getDropdownValues(4), 
        ];
    }
}
