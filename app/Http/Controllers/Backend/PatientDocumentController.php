<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use Firebase\JWT\JWT;

use ZipArchive;

class PatientDocumentController extends Controller
{

    public function generateDocxWithReplacements($filePath, $replacements)
    {
        // Load your existing docx template
        $templateProcessor = new TemplateProcessor($filePath);

        // Replace placeholders
        foreach ($replacements as $placeholder => $value) {
            // Note: TemplateProcessor expects placeholders wrapped like ${placeholder}
            // So we can convert «Consultant.Name» to Consultant_Name (or other format)
            // But PHPWord replaces ${placeholder} - so we'll do string replacements here

            // Let's standardize placeholders as ${Consultant_Name}, ${Consultant_Description}
            // So your docx placeholders should be like ${Consultant_Name} etc. or you do direct replace:
            $templateProcessor->setValue($placeholder, $value);
        }

        // Save to a temp file or output directly
        $tempFile = storage_path('app/public/generated_doc.docx');
        $templateProcessor->saveAs($tempFile);

        return $tempFile;
    }


    // public function downloadGeneratedDoc()
    // {
    //     $filePath = 'https://conceptmedicalpm.ie/storage/document_templates/qwcOPjGeDvGAf6llbe1Cdg8VyOuJs9sRVwi9o6eR.docx';
    //     //storage_path('app/public/document_templates/NPrZzyRSN67WSwWCLS4zh5PGFMoapdDYmvYXXArt.docx');

    //     $replacements = [
    //         'Consultant_Name' => 'Dr. John Smith',
    //         'Consultant_Description' => 'Senior Consultant Cardiologist',
    //     ];

    //     $generatedFile = $this->generateDocxWithReplacements($filePath, $replacements);

    //     return response()->download($generatedFile, 'consultant_letter.docx')->deleteFileAfterSend(true);
    // }
    public function replaceWordPlaceholders(string $templatePath, array $replacements, string $outputPath)
    {
        $templatePath = storage_path('app/public/document_templates/E7fIUmRV9D8Mqnuc6E69omZe5oQoPEIMtpLIRQJd.docx');

        $outputPath = storage_path('app/public/generated/generated-letter.docx');
        $tempDir = storage_path('app/temp-docx');

        if (!file_exists($templatePath)) {
            throw new \Exception("Template not found at: $templatePath");
        }

        $zip = new \ZipArchive;
    
        // Clean temp dir
        if (is_dir($tempDir)) {
            \File::deleteDirectory($tempDir);
        }
        mkdir($tempDir);
    
        if ($zip->open($templatePath) === true) {
            $zip->extractTo($tempDir);
            $zip->close();
    
            // Load document.xml
            $documentXmlPath = $tempDir . '/word/document.xml';
            $content = file_get_contents($documentXmlPath);
    
            // Replace placeholders
            foreach ($replacements as $key => $value) {
                $content = str_replace("«{$key}»", $value, $content);
            }
    
            // Save the modified content
            file_put_contents($documentXmlPath, $content);
    
            // Recreate the .docx file
            $newZip = new \ZipArchive;
            if ($newZip->open($outputPath, \ZipArchive::CREATE) === true) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($tempDir),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
    
                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $filePath     = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($tempDir) + 1);
                        $newZip->addFile($filePath, $relativePath);
                    }
                }
    
                $newZip->close();
            }
    
            \File::deleteDirectory($tempDir); // cleanup
    
            return $outputPath;
        }
    
        throw new \Exception('Could not open the Word template.');
    }
    
    // public function downloadGeneratedDoc()
    // {
    //     // $templatePath = storage_path('app/public/document_templates/my-template.docx');
    //     $templatePath = 'https://conceptmedicalpm.ie/storage/document_templates/qwcOPjGeDvGAf6llbe1Cdg8VyOuJs9sRVwi9o6eR.docx';

    //     $replacements = [
    //         'Consultant.Name' => 'Dr. John Smith',
    //         'Consultant.Description' => 'M.B.,B.Ch, B.A.O.,F.R.C.S.I(Tr & Orth)',
    //         'Consultant.Address1' => '123 Main St.',
    //         'Consultant.Address2' => 'Suite 456',
    //         'Consultant.Address3' => 'Dublin',
    //         'Consultant.Address4' => 'Ireland',
    //         'Consultant.PhoneNo' => '+353 1 123 4567',
    //         'Consultant.FaxNo' => '+353 1 987 6543',
    //         'Patient.Salutation' => 'Mr.',
    //         'Patient.FirstName' => 'John',
    //         'Patient.Surname' => 'Doe',
    //         'Patient.DOB' => '01-Jan-1980',
    //         'Patient.Address1' => 'Apt 1',
    //         'Patient.Address2' => 'High Street',
    //         'Patient.Address3' => 'Dublin',
    //         'Patient.Address4' => '',
    //         'Patient.Address5' => '',
    //         'General.CurrentDate' => now()->format('d M Y'),
    //     ];
    
    //     // Step 3: Cre

    //     $outputPath = storage_path('app/public/generated/generated-letter.docx');

    //     $this->replaceWordPlaceholders($templatePath, $replacements, $outputPath);

    //     return response()->download($outputPath, 'Letter.docx')->deleteFileAfterSend(true);
    // }


    public function saveDoc(Request $request)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        Html::addHtml($section, $request->input('content'));

        $fileName = 'edited_' . time() . '.docx';
        $savePath = storage_path("app/documents/$fileName");

        $phpWord->save($savePath, 'Word2007');

        return response()->download($savePath);
    }
    
    public function edit($filename)
    {
        $documentUrl = route('documents.download', ['filename' => 'document_templates/'.$filename]);
        $callbackUrl = route('onlyoffice.callback');

        $config = [
            'document' => [
                'fileType' => pathinfo($filename, PATHINFO_EXTENSION),
                'key' => md5($filename . time()),
                'title' => $filename,
                'url' => $documentUrl,
            ],
            'editorConfig' => [
                'callbackUrl' => $callbackUrl,
                'user' => [
                    'id' => 'user-123',
                    'name' => 'John Doe',
                ]
            ]
        ];

        $secret = env('DOCUMENT_SERVER_JWT_SECRET');
        if ($secret) {
            $config['token'] = JWT::encode($config, $secret, 'HS256');
        }

        return view('docs.editor', [
            'documentServer' => env('DOCUMENT_SERVER_URL'),
            'config' => json_encode($config, JSON_UNESCAPED_SLASHES)
        ]);
    }

    public function download($filename)
    {
        $path = storage_path("app/public/{$filename}");
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function callback(Request $request)
    {
        $data = $request->all();

        // OnlyOffice status 2 => “MustSave”
        if (isset($data['status']) && $data['status'] === 2) {
            $fileUrl = $data['url'];
            $filename = 'saved_' . time() . '.' . pathinfo($fileUrl, PATHINFO_EXTENSION);

            $content = file_get_contents($fileUrl);
            file_put_contents(storage_path("app/public/{$filename}"), $content);
        }

        return response()->json(['error' => 0]);
    }


}
