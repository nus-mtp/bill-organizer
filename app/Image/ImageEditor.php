<?php

namespace App\Image;

use Imagick;
use TesseractOCR;

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

    /**
     * Equivalent to the following CLI command:
     *      exec("convert {$input} -crop {$width}x{$height}+{$x}+{$y} {$output}");
     *
     * @param $input    -> input JPEG filename
     * @param $output   -> output JPEG filename
     * @param $x        -> top-left x coordinate of the desired cropped region
     * @param $y        -> top left y coordinate of the desired cropped region
     * @param $width    -> width of the crop
     * @param $height   -> height of the crop
     */
    public static function cropJpeg($input, $output, $x, $y, $width, $height) {
        $imagick = new Imagick();
        $imagick->readImage($input);
        $imagick->cropImage($width, $height, $x, $y);
        $imagick->writeImage($output);
    }


    /**
     * Recognize text from a jpeg input
     * @param $input    -> input JPEG filename
     * @param $without_symbols -> if true, clean symbols from the recognized text
     * @return string   -> recognized text
     */
    public static function recognizeTextFromJpeg($input, $without_symbols = false) {
        $tess = new TesseractOCR($input);
        $result = $tess->run();
        if ($without_symbols) {
            // this is to remove any symbols in the $result string
            $result = preg_replace('/[^\p{L}\p{N}\s]/u', '', $result);
        }
        return $result;
    }
}