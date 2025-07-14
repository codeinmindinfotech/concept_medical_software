@php
    $isEdit = isset($chargecode);
@endphp


    <!-- Charge Code Fields -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label><strong>Charge Code</strong></label>
            <input type="text"
                   name="code"
                   class="form-control"
                   value="{{ old('code', $chargecode->code ?? '') }}"
                   {{ $isEdit ? 'readonly' : 'required' }}>
        </div>
        <div class="col-md-4">
            <label><strong>Charge Group Type</strong></label>
            <select name="chargeGroupType" class="select2" required>
                @foreach($groupTypes as $id => $value)
                    <option value="{{ $id }}" {{ old('chargeGroupType', $chargecode->chargeGroupType ?? '') == $id ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label><strong>Description</strong></label>
            <textarea name="description" class="form-control" rows="2">{{ old('description', $chargecode->description ?? '') }}</textarea>
        </div>
        <div class="col-md-3">
            <label><strong>Price</strong></label>
            <input type="number"
                   name="price"
                   step="0.01"
                   class="form-control"
                   value="{{ old('price', $chargecode->price ?? '') }}"
                   required>
        </div>
        <div class="col-md-3">
            <label><strong>VAT Rate (Inc. VAT)</strong></label>
            <input type="number"
                   name="vatrate"
                   step="0.01"
                   class="form-control"
                   value="{{ old('vatrate', $chargecode->vatrate ?? '') }}">
        </div>
    </div>

    <hr>

    <!-- Insurance Price List -->
    <h5><strong>Insurance-specific Prices</strong></h5>
    <div class="table-responsive mb-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Insurance</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($insurances as $insurance)
                    @php
                        $priceValue = '';
                        if ($isEdit) {
                            $priceModel = $chargecode->insurancePrices->firstWhere('insurance_id', $insurance->id);
                            $priceValue = old("insurance_prices.{$insurance->id}", $priceModel->price ?? '');
                        } else {
                            $priceValue = old("insurance_prices.{$insurance->id}");
                        }
                    @endphp
                    <tr>
                        <td>{{ $insurance->code }}</td>
                        <td>
                            <input type="hidden" name="insurance_ids[]" value="{{ $insurance->id }}">
                            <input type="number"
                                   name="insurance_prices[{{ $insurance->id }}]"
                                   step="0.01"
                                   class="form-control"
                                   value="{{ $priceValue }}"
                                   placeholder="0.00">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Submit -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> {{ $isEdit ? 'Update' : 'Save' }} Charge Code
        </button>
    </div>

