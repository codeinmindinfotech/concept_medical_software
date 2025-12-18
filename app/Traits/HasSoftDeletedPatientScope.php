<?php 
namespace App\Traits;

trait HasSoftDeletedPatientScope
{
    public function scopeWherePatientOnlyTrashed($query)
    {
        // return $query->whereHas('patient', function ($q) {
        //     $q->onlyTrashed();
        // });
    }
}
