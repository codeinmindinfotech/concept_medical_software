<?php

namespace App\Http\Controllers;

use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnlyOfficeController extends Controller
{
    public function callback(Request $request, $id)
    {
        $status = $request->input('status');

        if ($status == 2 || $status == 6) {
            $url = $request->input('url');

            $document = PatientDocument::findOrFail($id);
            $fileContent = file_get_contents($url);

            if ($fileContent) {
                Storage::put("public/{$document->file_path}", $fileContent);
                $document->touch();
            }

            return response()->json(['error' => 0]);
        }

        return response()->json(['error' => 0]);
    }

}
