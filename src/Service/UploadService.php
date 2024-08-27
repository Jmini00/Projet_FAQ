<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

// Permet d'effectuer un upload
class UploadService {

    public function upload(UploadedFile $file, string $oldFile = null): string {

        // Recupere le nom original du fichier envoyé
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Slugify le nom du fichier
        $slugger = new AsciiSlugger();
        $safeFileName = $slugger->slug($originalFileName);
        $uniqid = uniqid();

        // Nouveau nom du fichier : nom_original-1254858.png
        $newFileName = "$safeFileName-$uniqid.{$file->guessExtension()}";

        // Upload
        $file->move('avatars', $newFileName);

        // Instancie le composant Symfony Filesystem
        $filesystem = new Filesystem();

        // Si l'argument $oldfile est different de null et que le fichier existe
        if ($oldFile !== null && $oldFile !== 'imgs/user_default.webp' && $filesystem->exists($oldFile)) {
            // Alors on supprime celui-ci
            $filesystem->remove($oldFile);
        }
        // Retourne le nouveau nom du fichier
        return $newFileName;
    }
}