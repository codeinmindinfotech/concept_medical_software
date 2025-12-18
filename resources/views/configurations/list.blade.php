<table class="table table-hover table-center mb-0" id="ConfigurationTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Key</th>
            <th>Value</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($configs as $index => $config)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $config->key }}</td>
            <td>{{ $config->value }}</td>
            <td>
                <a class="btn btn-sm bg-primary-light" href="{{guard_route('configurations.edit',$config->id) }}" class="btn btn-sm bg-primary-light" title="Edit">
                    <i class="fe fe-pencil"></i> Edit
                </a>
                <form action="{{guard_route('configurations.destroy', $config->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to delete this config? This will also delete the associated database.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm bg-danger-light" title="Delete"><i class="fe fe-trash"></i> Delete</button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>