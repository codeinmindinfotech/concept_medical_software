<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Company Settings</h5>
            </div>
            <div class="card-body">

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="companyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="company-info-tab" data-bs-toggle="tab" data-bs-target="#company-info" type="button" role="tab" aria-controls="company-info" aria-selected="true">
                            Company Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp-settings" type="button" role="tab" aria-controls="whatsapp-settings" aria-selected="false">
                            WhatsApp Settings
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms-settings" type="button" role="tab" aria-controls="sms-settings" aria-selected="false">
                            SMS Settings
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="companyTabsContent">

                    <!-- Company Info Tab -->
                    <div class="tab-pane fade show active" id="company-info" role="tabpanel" aria-labelledby="company-info-tab">
                        <div class="row">
                            {{-- Company Name --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label"><strong>Name <span class="text-danger">*</span></strong></label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $company->name ?? '') }}"
                                    maxlength="255"
                                    required
                                    @unless(has_role('superadmin')) readonly @endunless>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Company Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label"><strong>Email <span class="text-danger">*</span></strong></label>
                                <input id="email" name="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $company->email ?? '') }}"
                                    maxlength="255"
                                    required
                                    @unless(has_role('superadmin')) readonly @endunless>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Settings Tab -->
                    <div class="tab-pane fade" id="whatsapp-settings" role="tabpanel" aria-labelledby="whatsapp-tab">
                        <div class="row">
                            {{-- WhatsApp Phone Number ID --}}
                            <div class="col-md-6">
                                <label for="whatsapp_phone_number_id" class="form-label"><strong>WhatsApp Phone Number ID</strong></label>
                                <input id="whatsapp_phone_number_id" name="whatsapp_phone_number_id" type="text"
                                       class="form-control @error('whatsapp_phone_number_id') is-invalid @enderror"
                                       value="{{ old('whatsapp_phone_number_id', $company->whatsapp_phone_number_id ?? '') }}"
                                       placeholder="e.g. 123456789012345">
                                @error('whatsapp_phone_number_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- WhatsApp Business Account ID --}}
                            <div class="col-md-6">
                                <label for="whatsapp_business_account_id" class="form-label"><strong>WhatsApp Business Account ID</strong></label>
                                <input id="whatsapp_business_account_id" name="whatsapp_business_account_id" type="text"
                                       class="form-control @error('whatsapp_business_account_id') is-invalid @enderror"
                                       value="{{ old('whatsapp_business_account_id', $company->whatsapp_business_account_id ?? '') }}"
                                       placeholder="e.g. 987654321098765">
                                @error('whatsapp_business_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- WhatsApp Access Token --}}
                            <div class="col-12">
                                <label for="whatsapp_access_token" class="form-label"><strong>WhatsApp Access Token</strong></label>
                                <textarea id="whatsapp_access_token" name="whatsapp_access_token"
                                          class="form-control @error('whatsapp_access_token') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Paste your WhatsApp Access Token here...">{{ old('whatsapp_access_token', $company->whatsapp_access_token ?? '') }}</textarea>
                                @error('whatsapp_access_token')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SMS Settings Tab -->
                    <div class="tab-pane fade" id="sms-settings" role="tabpanel" aria-labelledby="sms-tab">
                        <div class="row">
                            {{-- webex_token  webex_sender Phone Number ID --}}
                            <div class="col-md-6">
                                <label for="webex_token" class="form-label"><strong>Webex Token</strong></label>
                                <input id="webex_token" name="webex_token" type="text"
                                       class="form-control @error('webex_token') is-invalid @enderror"
                                       value="{{ old('webex_token', $company->webex_token ?? '') }}"
                                       placeholder="e.g. 123456789012345">
                                @error('webex_token')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- WhatsApp Business Account ID --}}
                            <div class="col-md-6">
                                <label for="webex_sender" class="form-label"><strong>Webex Sender</strong></label>
                                <input id="webex_sender" name="webex_sender" type="text"
                                       class="form-control @error('webex_sender') is-invalid @enderror"
                                       value="{{ old('webex_sender', $company->webex_sender ?? '') }}"
                                       placeholder="e.g. 987654321098765">
                                @error('webex_sender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="text-center mb-5">
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-floppy-disk"></i> Submit
        </button>
    </div>
</div>