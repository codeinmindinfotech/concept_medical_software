<?php

namespace App\Http\Controllers;

use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnlyOfficeController extends Controller
{
    public function callback(Request $request, PatientDocument $document)
    {
        \Log::info('ONLYOFFICE callback:', $request->all());

        if ($request->has('status')) {
            // Status 2 means "Document is ready to save"
            if ($request->status == 2 && $request->has('url')) {
                $updatedFileUrl = $request->input('url');

                try {
                    $contents = file_get_contents($updatedFileUrl);

                    if ($contents === false) {
                        \Log::error('Failed to download updated file from URL: ' . $updatedFileUrl);
                        return response()->json(['error' => 1]);
                    }

                    \Storage::put($document->file_path, $contents);

                    return response()->json(['error' => 0]);
                } catch (\Exception $e) {
                    \Log::error('Exception when downloading file: ' . $e->getMessage());
                    return response()->json(['error' => 1]);
                }
            }

            // Other statuses
            return response()->json(['error' => 0]);
        }

        return response()->json(['error' => 1]);
    }

}