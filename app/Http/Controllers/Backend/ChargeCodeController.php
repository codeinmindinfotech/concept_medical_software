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

class ChargeCodeController extends Controller
{
    use DropdownTrait;
    public function index(Request $request): View|string
    {
        $chargecodes = ChargeCode::with('chargeGroup')->latest()->get();

        if ($request->ajax()) {
            return view('chargecodes.list', compact('chargecodes'))->render();
        }

        return view('chargecodes.index', compact('chargecodes'));
    }

    public function create()
    {
        return view('chargecodes.create', [
            'insurances' => Insurance::all(),
            'groupTypes' => $this->getDropdownOptions('CHARGE_GROUP_TYPE')
        ]);
    }

    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'chargeGroupType' => 'required|exists:drop_down_values,id',
            'code' => 'required|string|max:255|unique:charge_codes,code',
            'price' => 'required|numeric|min:0',
            'vatrate' => 'nullable|numeric|min:0',
            'insurance_prices' => 'array',
        ]);

        // Create charge code
        $chargecode = ChargeCode::create([
            'charge' => $request->charge,
            'chargeGroupType' => $request->chargeGroupType,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'price' => $request->price,
            'vatrate' => $request->vatrate ?? 0,
            'last_price_updated' => now(),
            'previous_amount' => 0
        ]);

        // Save insurance prices
        foreach ($request->insurance_prices as $insuranceId => $price) {
            if ($price !== null && $price !== '') {
                ChargeCodePrice::create([
                    'charge_code_id' => $chargecode->id,
                    'insurance_id' => $insuranceId,
                    'price' => $price,
                ]);
            }
        }

        return response()->json([
            'redirect' => guard_route('chargecodes.index'),
            'message' => 'Charge Code created successfully',
        ]);
    }

    public function show($chargecodeId): View
    {
        $chargecode = ChargeCode::findOrFail($chargecodeId);
        $insurances = Insurance::all();
        $groupTypes = $this->getDropdownOptions('CHARGE_GROUP_TYPE');

        return view('chargecodes.show',compact('chargecode','insurances','groupTypes'));
    }

    public function edit($chargecodeId): View
    {
        $chargecode = ChargeCode::findOrFail($chargecodeId);
        $insurances = Insurance::all();
        $groupTypes = $this->getDropdownOptions('CHARGE_GROUP_TYPE');
        return view('chargecodes.edit',compact('chargecode', 'insurances', 'groupTypes'));
    }

    public function update(Request $request, $chargecodeId): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'chargeGroupType' => 'required|exists:drop_down_values,id',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'vatrate' => 'nullable|numeric|min:0',
            'insurance_prices' => 'array',
            'insurance_prices.*' => 'nullable|numeric|min:0',
        ]);

        $chargecode = ChargeCode::findOrFail($chargecodeId);
        $chargecode->update([
            'code' => $request->code,
            'chargeGroupType' => $request->chargeGroupType,
            'description' => $request->description,
            'price' => $request->price,
            'vatrate' => $request->vatrate ?? 0,
            'last_price_updated' => now(),
        ]);

        // Save or update insurance prices
        foreach ($request->insurance_prices as $insuranceId => $price) {
            if ($price === null || $price === '') {
                continue; // skip blank entries
            }
            ChargeCodePrice::updateOrCreate(
                [
                    'charge_code_id' => $chargecode->id,
                    'insurance_id' => $insuranceId
                ],
                [
                    'price' => $price
                ]
            );
        }
        return response()->json([
            'redirect' => guard_route('chargecodes.index'),
            'message' => 'Charge Code updated successfully',
        ]);
    }

    public function destroy($chargecodeId): RedirectResponse
    {
        $chargecode = ChargeCode::findOrFail($chargecodeId);
        $chargecode->delete();
    
        return redirect()->guard_route('chargecodes.index')
                        ->with('success','chargecode deleted successfully');
    }
}
