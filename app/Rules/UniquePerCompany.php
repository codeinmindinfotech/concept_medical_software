<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniquePerCompany implements Rule
{
    protected $table;
    protected $column;
    protected $companyId;
    protected $ignoreId;

    /**
     * Create a new rule instance.
     *
     * @param  string  $table
     * @param  string  $column
     * @param  int|string|null  $companyId
     * @param  int|string|null  $ignoreId  // optional: for update scenarios
     */
    public function __construct(string $table, string $column, $companyId, $ignoreId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->companyId = $companyId;
        $this->ignoreId = $ignoreId;
    }

    public function passes($attribute, $value)
    {
        $query = DB::table($this->table)
        ->where($this->column, $value);

        if ($this->companyId !== null) {
            $query->where('company_id', $this->companyId);
        } else {
            // Global uniqueness when company_id is null (e.g. superadmin)
            $query->whereNull('company_id');
        }

        // Exclude a given record id (for updates)
        if ($this->ignoreId) {
            $query->where('id', '<>', $this->ignoreId);
        }

        return !$query->exists();
    }

    public function message()
    {
        return "The :attribute has already been taken for this company.";
    }
}
