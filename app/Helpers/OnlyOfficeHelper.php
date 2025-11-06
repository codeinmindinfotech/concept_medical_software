<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OnlyOfficeHelper
{
    public static function createJwtToken($document, $key, $url, $userOrPatient)
    {
        $docId = is_object($document) && isset($document->id)
        ? $document->id
        : uniqid('temp_');

        $title = is_object($document) && isset($document->file_path)
            ? $document->file_path
            : (is_string($document) ? basename($document) : 'Document');


        $payload = [
            "document" => [
                "fileType" => "docx",
                "key" => $key,
                "title" => $title,
                "url" => $url,
            ],
            "editorConfig" => [
                "callbackUrl" => url("/api/onlyoffice/callback/{$docId}"),
                "mode" => "edit",
                "user" => [
                    'id' => (string) ($userOrPatient->id ?? '1'),
                    'name' => $userOrPatient->full_name ?? $userOrPatient->name ?? 'Guest',
                ],
            ],
            "iat" => time(),
            "exp" => time() + 3600,
        ];

        return JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    }

    public static function createJwtTokenDocumentTemplate($document, $key, $url, $userOrPatient)
    {
        $docId = is_object($document) && isset($document->id)
        ? $document->id
        : uniqid('temp_');

        $title = is_object($document) && isset($document->name)
            ? $document->name
            : (is_string($document) ? basename($document) : 'Document');


        $payload = [
            "document" => [
                "fileType" => "docx",
                "key" => $key,
                "title" => $title,
                "url" => $url,
            ],
            "editorConfig" => [
                "callbackUrl" => url("/api/onlyoffice/document_callback/{$docId}"),
                "mode" => "edit",
                "user" => [
                    'id' => (string) ($userOrPatient->id ?? '1'),
                    'name' => $userOrPatient->full_name ?? $userOrPatient->name ?? 'Guest',
                ],
            ],
            "iat" => time(),
            "exp" => time() + 3600,
        ];

        return JWT::encode($payload, env('ONLYOFFICE_JWT_SECRET'), 'HS256');
    }
    public static function generateDocumentKey($document): string
    {
        if (is_object($document) && isset($document->id)) {
            $data = $document->id . '|' . optional($document->updated_at)->timestamp;
        } else {
            $data = is_string($document)
                ? md5($document . microtime())
                : uniqid('temp_', true);
        }
        // $data = $document->id . '|' . $document->updated_at->timestamp; // use integer timestamp
        return substr(hash('sha256', $data), 0, 128);
    }

    /**
     * Convert DOCX to PDF using OnlyOffice ConvertService
     *
     * @param string $docxPath  Local storage path of the DOCX
     * @param string|null $fileName Optional filename for PDF
     * @return string|null Full path to the converted PDF, or null on failure
     */
    public static function convertDocxToPdf(string $docxPath, ?string $fileName = null): ?string
    {
        // Make sure file exists
        if (!file_exists($docxPath)) return null;

        // Copy to public storage if not already
        $fileName = $fileName ?? basename($docxPath, '.docx') . '.pdf';
        $publicDocPath = 'public/temp/' . basename($docxPath);
        Storage::putFileAs('public/temp', $docxPath, basename($docxPath));
        $fileUrl = asset('storage/temp/' . basename($docxPath));

        $convertServiceUrl = rtrim(env('ONLYOFFICE_DOC_SERVER'), '/') . '/ConvertService.ashx';

        try {
            $response = Http::asForm()->post($convertServiceUrl, [
                'async' => false,
                'filetype' => 'docx',
                'key' => uniqid(),
                'title' => basename($docxPath),
                'url' => $fileUrl,
                'outputtype' => 'pdf',
            ]);

            if ($response->successful()) {
                $pdfPath = storage_path('app/temp/' . $fileName);
                if (!file_exists(dirname($pdfPath))) mkdir(dirname($pdfPath), 0777, true);
                file_put_contents($pdfPath, $response->body());
                return $pdfPath;
            }
        } catch (\Exception $e) {
            \Log::error("OnlyOffice DOCX → PDF conversion failed: " . $e->getMessage());
        }

        return null;
    }
}
