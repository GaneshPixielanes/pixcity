<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Transliterator;

class FileUploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $file, $subDirectory = "", $rename = true, $resize = false)
    {
        if(!$rename){
            $filePath = pathinfo($file->getClientOriginalName());
            $fileName = $this->slugify($filePath['filename']).".".$filePath['extension'];
        }
        else {
            $fileName = md5(uniqid()) . '.' . $file->guessClientExtension();
        }

        $file->move($this->getUploadDirectory().(($subDirectory !=="")?"/".$subDirectory:""), $fileName);

        return $fileName;
    }

    public function getUploadDirectory()
    {
        return $this->uploadDir;
    }

    public function slugify($text)
    {
        $transliterator = Transliterator::create(
            'NFD; [:Nonspacing Mark:] Remove; NFC;'
        );

        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = $transliterator->transliterate($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}