<?php

namespace App\Image;

use Imagick;

class ImageEditor
{
    public static function getPdfNumPages($path) {
        $imagick = new Imagick();
        $imagick->pingImage($path);
        return $imagick->getNumberImages();
    }

    /**
     * Equivalent to the following CLI command:
     *     exec("convert -density 150 {$src}[{$pageNum}] -quality 100 -flatten -sharpen 0x1.0
     *      {$dest}");
     *
     *
     * @param $input        -> input PDF filename
     * @param $pageNum      -> input PDF's page number to be converted to JPEG
     * @param $output       -> output JPEG's filename
     * @return bool         -> indicates success/false
     */
    public static function jpegFromPdf($input, $pageNum, $output) {
        $imagick = new Imagick();
        $imagick->setResolution(150, 150);
        $imagick->readImage($input . "[{$pageNum}]");
        $imagick->setImageFormat('jpeg');
        $imagick->setImageCompressionQuality(100);
        $imagick->sharpenImage(0, 1.0);
        $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        $is_successful = $imagick->writeImage($output);
        return $is_successful;
    }
}