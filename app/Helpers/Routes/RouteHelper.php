<?php

declare(strict_types=1);

namespace App\Helpers\Routes;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Illuminate\Support\Facades\Log; // Ajouter cette ligne pour importer Log

final class RouteHelper
{
    public static function includeRouteFiles(string $directory): void
    {
        $directoryIterator = new RecursiveDirectoryIterator($directory);

        /** @var RecursiveDirectoryIterator|RecursiveIteratorIterator $iterator */
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        while ($iterator->valid()) {
            if ( ! $iterator->isDot() && $iterator->isFile() && $iterator->isReadable() && 'php' === $iterator->current()->getExtension()) {
                // Log de debug pour afficher les fichiers inclus
                Log::info("Inclusion du fichier : " . $iterator->key());
                require $iterator->key();
            }

            $iterator->next();
        }
    }
}
