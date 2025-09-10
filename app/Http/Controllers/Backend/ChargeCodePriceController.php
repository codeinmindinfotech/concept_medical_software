<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Insurance;
use App\Models\ChargeCode;
use App\Models\ChargeCodePrice;
use Illuminate\Http\Request;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChargeCodePriceController extends Controller
{
    use DropdownTrait;
    public function index(Request $request): View|string
    {
        //$this->authorize('viewAny', ChargeCode::class);
        // Admins can search all patients
        $query = Insurance::companyOnly()->latest();

        $insurances = $query->get();


        if ($request->ajax()) {
            return view('chargecodes.chargecodeprices.list', compact('insurances'))->render();
        }

        return view('chargecodes.chargecodeprices.index', compact('insurances'));
    }

    public function showAdjustPrices(Insurance $insurance)
    {
        $chargePrices = ChargeCode::companyOnly()->with(['insurancePrices' => function ($query) use ($insurance) {
            $query->where('insurance_id', $insurance->id);
        }])->orderBy('id')->get();
        
        return view('chargecodes.chargecodeprices.adjust_prices', compact('insurance','chargePrices'));
    }

    public function processAdjustPrices(Request $request, Insurance $insurance)
    {
        $request->validate([
            'percentage' => 'nullable|numeric|between:-100,100',
            'updated_prices' => 'array',
            'updated_prices.*' => 'nullable|numeric|min:0',
        ]);
    
        $percentage = $request->percentage;
        $chargeCodeIds = $request->charge_code_ids ?? [];

        foreach ($chargeCodeIds as $chargeCodeId) {
            $inputPrice = $request->updated_prices[$chargeCodeId] ?? null;
    
            if (is_null($inputPrice)) continue;
    
            // Apply percentage increase if provided
            $finalPrice = $percentage
                ? $inputPrice + ($inputPrice * ($percentage / 100))
                : $inputPrice;
    
            ChargeCodePrice::updateOrCreate(
                [
                    'charge_code_id' => $chargeCodeId,
                    'insurance_id' => $insurance->id,
                ],
                [
                    'price' => round($finalPrice, 2),
                ]
            );
        }
    

        return redirect()->route('chargecodeprices.adjust-prices', $insurance->id)
                        ->with('success', 'Prices updated successfully.');
    }



    public function create()
    {
        //$this->authorize('create', ChargeCode::class);
        return view('chargecodes.create', [
            'insurances' => Insurance::companyOnly()->all(),
            'groupTypes' => $this->getDropdownOptions('CHARGE_GROUP_TYPE')
        ]);
    }
}
