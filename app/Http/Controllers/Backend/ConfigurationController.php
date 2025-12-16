<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function __construct()
    {
        // Middleware to allow only superadmin
        // $this->middleware('role:superadmin');
    }

    public function index()
    {
        $configs = Configuration::get();
        return view(guard_view('configurations.index', 'patient_admin.configuration.index'), compact('configs'));
    }

    public function create()
    {
        return view(guard_view('configurations.create', 'patient_admin.configuration.create'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => [
                'required',
                'unique:configurations,key',
                'regex:/^[A-Za-z0-9_]+$/',
            ],
            // 'key' => 'required|string|unique:configurations,key',
            'value' => 'nullable|string',
        ]);

      Configuration::create($request->only('key', 'value'));
      return response()->json([
        'redirect' =>guard_route('configurations.index'),
        'message' => 'Configuration created successfully',
    ]);
  }

    public function edit(Configuration $configuration)
    {
        return view(guard_view('configurations.edit', 'patient_admin.configuration.edit'), compact('configuration'));
    }

    public function update(Request $request, Configuration $configuration)
    {
        $request->validate([
            'key' => [
                'required',
                'unique:configurations,key,'. $configuration->id,
                'regex:/^[A-Za-z0-9_]+$/',
            ],
            // 'key' => 'required|string|unique:configurations,key,' . $configuration->id,
            'value' => 'nullable|string',
        ]);

        $configuration->update($request->only('key', 'value'));

        // Clear cache on update
        \Illuminate\Support\Facades\Cache::forget("config:{$configuration->key}");

        return response()->json([
            'redirect' =>guard_route('configurations.index'),
            'message' => 'Configuration updated successfully',
        ]);
    }

    public function destroy(Configuration $configuration)
    {
        // Clear cache on delete
        \Illuminate\Support\Facades\Cache::forget("config:{$configuration->key}");

        $configuration->delete();

        return redirect(guard_route('configurations.index'))
            ->with('success', 'Configuration deleted successfully.');
    }
}
