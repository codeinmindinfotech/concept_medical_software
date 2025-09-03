@extends('backend.theme.default')

@section('content')
<div class="container-fluid px-4">
    @php
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => guard_route('dashboard.index')],
            ['label' => 'Roles', 'url' => guard_route('roles.index')],
            ['label' => 'Edit Role'],
        ];
    @endphp

    @include('backend.theme.breadcrumb', [
        'pageTitle' => 'Edit Role',
        'breadcrumbs' => $breadcrumbs,
        'backUrl' => guard_route('roles.index'),
        'isListPage' => false
    ])

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ guard_route('roles.update', $role->id) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" readonly placeholder="Name" class="form-control" value="{{ $role->name }}">
                </div>
            </div>

            @php
                // Group permissions by module and action
                $groupedPermissions = [];

                foreach ($permission as $perm) {
                    if (strpos($perm->name, '-') !== false) {
                        [$module, $action] = explode('-', $perm->name);
                        $groupedPermissions[$module][$action] = $perm;
                    }
                }

                $actions = ['list', 'create', 'edit', 'delete'];
            @endphp

            <div class="col-12 mt-3">
                <div class="form-group">
                    <strong>Permissions:</strong>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th><input type="checkbox" id="selectAll"> All</th>
                                @foreach ($actions as $action)
                                    <th>{{ ucfirst($action) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedPermissions as $module => $perms)
                                <tr>
                                    <td><strong>{{ ucfirst($module) }}</strong></td>
                                    <td>
                                        <input type="checkbox" class="select-module" data-module="{{ $module }}">
                                    </td>
                                    @foreach ($actions as $action)
                                        @php
                                            $perm = $perms[$action] ?? null;
                                        @endphp
                                        <td>
                                            @if ($perm)
                                                <input type="checkbox"
                                                    name="permission[{{ $perm->id }}]"
                                                    value="{{ $perm->id }}"
                                                    class="perm-checkbox {{ $module }}-perm"
                                                    {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-primary btn-sm mb-3">
                    <i class="fa-solid fa-floppy-disk"></i> Submit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Select/deselect all permissions globally
    document.getElementById('selectAll').addEventListener('change', function () {
        const checked = this.checked;
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
        document.querySelectorAll('.select-module').forEach(cb => cb.checked = checked);
    });

    // Select/deselect all permissions in a module
    document.querySelectorAll('.select-module').forEach(moduleCheckbox => {
        moduleCheckbox.addEventListener('change', function () {
            const module = this.dataset.module;
            const checked = this.checked;
            document.querySelectorAll(`.${module}-perm`).forEach(cb => cb.checked = checked);
        });
    });

    // If individual permission checkboxes are changed, update module checkbox state
    document.querySelectorAll('.perm-checkbox').forEach(permCheckbox => {
        permCheckbox.addEventListener('change', function () {
            const classes = this.className.split(' ');
            classes.forEach(cls => {
                if (cls.endsWith('-perm')) {
                    const module = cls.replace('-perm', '');
                    const all = document.querySelectorAll(`.${module}-perm`);
                    const allChecked = [...all].every(cb => cb.checked);
                    document.querySelector(`.select-module[data-module="${module}"]`).checked = allChecked;
                }
            });

            // Update global 'selectAll'
            const allPerms = document.querySelectorAll('.perm-checkbox');
            const allChecked = [...allPerms].every(cb => cb.checked);
            document.getElementById('selectAll').checked = allChecked;
        });
    });
</script>
@endpush
