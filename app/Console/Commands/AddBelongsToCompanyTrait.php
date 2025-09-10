<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class AddBelongsToCompanyTrait extends Command
{
    protected $signature = 'traits:add-company';
    protected $description = 'Add BelongsToCompany trait to all models';

    public function handle()
    {
        $modelPath = app_path('Models');
        $trait = 'use App\Traits\BelongsToCompany;';

        $modelFiles = File::allFiles($modelPath);

        foreach ($modelFiles as $file) {
            $path = $file->getRealPath();
            $content = File::get($path);

            $namespacePattern = '/namespace\s+App\\\Models;/';
            $classPattern = '/class\s+(\w+)/';

            if (preg_match($namespacePattern, $content, $namespaceMatch) &&
                preg_match($classPattern, $content, $classMatch)) {

                $className = $classMatch[1];
                $fqcn = "App\\Models\\$className";

                if (!class_exists($fqcn)) {
                    require_once $path;
                }

                try {
                    $reflection = new ReflectionClass($fqcn);

                    if ($reflection->isAbstract()) {
                        continue;
                    }

                    // Skip if trait already used
                    if (in_array('App\Traits\BelongsToCompany', class_uses($fqcn))) {
                        $this->info("Trait already used in $className");
                        continue;
                    }

                    // Add trait
                    $lines = explode("\n", $content);
                    $newContent = [];
                    $traitInserted = false;

                    foreach ($lines as $line) {
                        $newContent[] = $line;

                        // After namespace declaration, insert trait use
                        if (strpos($line, 'namespace') !== false && !$traitInserted) {
                            $newContent[] = $trait;
                            $traitInserted = true;
                        }

                        // Inside class, inject the trait if not used
                        if (strpos($line, 'class ') !== false && strpos($line, '{') !== false) {
                            $classLineKey = array_key_last($newContent);
                            $newContent[$classLineKey] .= "\n    use BelongsToCompany;";
                        }
                    }

                    File::put($path, implode("\n", $newContent));
                    $this->info("Trait added to $className");

                } catch (\Throwable $e) {
                    $this->error("Failed on $fqcn: " . $e->getMessage());
                }
            }
        }

        $this->info("✅ Done applying BelongsToCompany trait to models.");
    }
}
