<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Company Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Company Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label"><strong>Name <span class="text-danger">*</span></strong></label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $company->name ?? '') }}" maxlength="255">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="db_database" class="form-label"><strong>Database Name <span class="text-danger">*</span></strong></label>
                        <input id="db_database" name="db_database" type="text" class="form-control @error('db_database') is-invalid @enderror"
                            value="{{ old('db_database', $company->db_database ?? '') }}" maxlength="255">
                        @error('db_database') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- DB Host --}}
                    <div class="col-md-6">
                        <label for="db_host" class="form-label"><strong>DB Host</strong></label>
                        <input id="db_host" name="db_host" type="text" class="form-control"
                            value="{{ old('db_host', $company->db_host ?? '127.0.0.1') }}">
                    </div>

                    {{-- DB Port --}}
                    <div class="col-md-6">
                        <label for="db_port" class="form-label"><strong>DB Port</strong></label>
                        <input id="db_port" name="db_port" type="text" class="form-control"
                            value="{{ old('db_port', $company->db_port ?? '3306') }}">
                    </div>

                    {{-- DB Username --}}
                    <div class="col-md-6">
                        <label for="db_username" class="form-label"><strong>DB Username</strong></label>
                        <input id="db_username" name="db_username" type="text" class="form-control"
                            value="{{ old('db_username', $company->db_username ?? 'root') }}">
                    </div>

                    {{-- DB Password --}}
                    <div class="col-md-6">
                        <label for="db_password" class="form-label"><strong>DB Password</strong></label>
                        <input id="db_password" name="db_password" type="password" class="form-control"
                            value="{{ old('db_password', $company->db_password ?? '') }}">
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
