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
        //$this->authorize('viewAny', ChargeCode::class);
        $chargecodes = ChargeCode::with('chargeGroup')->latest()->get();

        if ($request->ajax()) {
            return view('chargecodes.list', compact('chargecodes'))->render();
        }

        return view('chargecodes.index', compact('chargecodes'));
    }
    public function create()
    {
        //$this->authorize('create', ChargeCode::class);
        return view('chargecodes.create', [
            'insurances' => Insurance::all(),
            'groupTypes' => $this->getDropdownOptions('CHARGE_GROUP_TYPE')
        ]);
    }

    public function store(Request $request) : JsonResponse
    {
        //$this->authorize('create', ChargeCode::class);
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
            'redirect' => route('chargecodes.index'),
            'message' => 'Charge Code created successfully',
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(ChargeCode $chargecode): View
    {
        //$this->authorize('view', $chargecode);
        $insurances = Insurance::all();
        $groupTypes = $this->getDropdownOptions('CHARGE_GROUP_TYPE');

        return view('chargecodes.show',compact('chargecode','insurances','groupTypes'));
    }

    public function edit(ChargeCode $chargecode): View
    {
        //$this->authorize('update', $chargecode);
        $insurances = Insurance::all();
        $groupTypes = $this->getDropdownOptions('CHARGE_GROUP_TYPE');
        return view('chargecodes.edit',compact('chargecode', 'insurances', 'groupTypes'));
    }

    public function update(Request $request, ChargeCode $chargecode): JsonResponse
    {
        //$this->authorize('update', $chargecode);
        $request->validate([
            'code' => 'required|string|max:255',
            'chargeGroupType' => 'required|exists:drop_down_values,id',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'vatrate' => 'nullable|numeric|min:0',
            'insurance_prices' => 'array',
            'insurance_prices.*' => 'nullable|numeric|min:0',
        ]);

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
            'redirect' => route('chargecodes.index'),
            'message' => 'Charge Code updated successfully',
        ]);
    }

    public function destroy(ChargeCode $chargecode): RedirectResponse
    {
        //$this->authorize('delete', $chargecode);
        $chargecode->delete();
    
        return redirect()->route('chargecodes.index')
                        ->with('success','chargecode deleted successfully');
    }
}
