<?php 
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToCompany
{
    public static function bootBelongsToCompany()
    {
        static::creating(function (Model $model) {
            if (empty($model->company_id)) {
                $model->company_id = current_company_id();
            }
        });

    }

    public function scopeCompanyOnly($query)
    {
        if (function_exists('current_company_id') && current_company_id()) {
            return $query->where('company_id', current_company_id());
        }

        return $query;
    }

}
