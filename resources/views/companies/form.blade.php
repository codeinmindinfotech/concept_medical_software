<div class="row">
    <div class="col-12">
        <div class="card shadow-sm p-3">
            
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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email-settings" type="button" role="tab" aria-controls="sms-settings" aria-selected="false">
                            EMail Settings
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

                    <!-- Email Settings Tab -->
                    <div class="tab-pane fade" id="email-settings" role="tabpanel" aria-labelledby="sms-tab">
                        <div class="row">

                            {{-- Mail Host --}}
                            <div class="col-md-6">
                                <label for="mail_host" class="form-label"><strong>Mail Host</strong></label>
                                <input id="mail_host" name="mail_host" type="text"
                                       class="form-control @error('mail_host') is-invalid @enderror"
                                       value="{{ old('mail_host', $company->mail_host ?? '') }}"
                                       placeholder="e.g. smtp.office365.com">
                                @error('mail_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- Mail Port --}}
                            <div class="col-md-6">
                                <label for="mail_port" class="form-label"><strong>Mail Port</strong></label>
                                <input id="mail_port" name="mail_port" type="number"
                                       class="form-control @error('mail_port') is-invalid @enderror"
                                       value="{{ old('mail_port', $company->mail_port ?? '') }}"
                                       placeholder="e.g. 587">
                                @error('mail_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- Mail Username --}}
                            <div class="col-md-6 mt-3">
                                <label for="mail_username" class="form-label"><strong>Mail Username</strong></label>
                                <input id="mail_username" name="mail_username" type="email"
                                       class="form-control @error('mail_username') is-invalid @enderror"
                                       value="{{ old('mail_username', $company->mail_username ?? '') }}"
                                       placeholder="email@company.com">
                                @error('mail_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- Mail Password --}}
                            <div class="col-md-6 mt-3">
                                <label for="mail_password" class="form-label"><strong>Mail Password</strong></label>
                                <input id="mail_password" name="mail_password" type="password"
                                       class="form-control @error('mail_password') is-invalid @enderror"
                                       value="{{ old('mail_password', $company->mail_password ?? '') }}"
                                       placeholder="********">
                                @error('mail_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- Mail Encryption --}}
                            <div class="col-md-6 mt-3">
                                <label for="mail_encryption" class="form-label"><strong>Mail Encryption</strong></label>
                                <select id="mail_encryption" name="mail_encryption"
                                        class="form-select @error('mail_encryption') is-invalid @enderror">
                                    <option value="">None</option>
                                    <option value="tls" {{ old('mail_encryption', $company->mail_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ old('mail_encryption', $company->mail_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                                @error('mail_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- From Email --}}
                            <div class="col-md-6 mt-3">
                                <label for="mail_from_address" class="form-label"><strong>From Email Address</strong></label>
                                <input id="mail_from_address" name="mail_from_address" type="email"
                                       class="form-control @error('mail_from_address') is-invalid @enderror"
                                       value="{{ old('mail_from_address', $company->mail_from_address ?? '') }}"
                                       placeholder="no-reply@company.com">
                                @error('mail_from_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            {{-- From Name --}}
                            <div class="col-md-6 mt-3">
                                <label for="mail_from_name" class="form-label"><strong>From Name</strong></label>
                                <input id="mail_from_name" name="mail_from_name" type="text"
                                       class="form-control @error('mail_from_name') is-invalid @enderror"
                                       value="{{ old('mail_from_name', $company->mail_from_name ?? '') }}"
                                       placeholder="Company Name">
                                @error('mail_from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="col-12 mt-4">
                                <button type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    id="sendTestEmailBtn"
                                    data-url="{{ route('company.email.test') }}"
                                    data-csrf="{{ csrf_token() }}">
                                <i class="fa-solid fa-paper-plane"></i> Send Test Email
                                </button>
                            
                                <span id="testEmailStatus" class="ms-3"></span>
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