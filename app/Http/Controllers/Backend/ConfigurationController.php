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
        $configs = Configuration::paginate(15);
        return view('configurations.index', compact('configs'));
    }

    public function create()
    {
        return view('configurations.create');
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

        return redirect()->route('configurations.index')
            ->with('success', 'Configuration created successfully.');
    }

    public function edit(Configuration $configuration)
    {
        return view('configurations.edit', compact('configuration'));
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

        return redirect()->route('configurations.index')
            ->with('success', 'Configuration updated successfully.');
    }

    public function destroy(Configuration $configuration)
    {
        // Clear cache on delete
        \Illuminate\Support\Facades\Cache::forget("config:{$configuration->key}");

        $configuration->delete();

        return redirect()->route('configurations.index')
            ->with('success', 'Configuration deleted successfully.');
    }
}
