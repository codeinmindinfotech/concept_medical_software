<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

class OnlyOfficeHelper
{
    public static function createJwtToken($document, $key, $url, $userOrPatient)
    {
        $payload = [
            "document" => [
                "fileType" => "docx",
                "key" => $key,
                "title" => $document->title ?? 'Document',
                "url" => $url,
            ],
            "editorConfig" => [
                "callbackUrl" => url("/api/onlyoffice/callback/{$document->id}"),
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
        $data = $document->id . '|' . $document->updated_at->timestamp; // use integer timestamp
        return substr(hash('sha256', $data), 0, 128);
    }
}
