<?php
/**
 * Dynamic Dummy Image Generator — as seen on DummyImage.com
 *
 * This script enables you to create placeholder images in a breeze.
 * Please refer to the README on how to use it.
 *
 * (Original idea by Russel Heimlich. When I first published this script,
 * DummyImage.com was not Open Source, so I had to write a small script to
 * replace the function on my own server.)
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license MIT
 * @link    https://github.com/FabianBeiner/PHP-Dummy-Image-Generator/
 * @version 0.3.0 <2017-12-26>
 */

/**
 * Handle the “size” parameter.
 */
$size = '640x480';
if (isset($_GET['size'])) {
    $size = $_GET['size'];
}
list($imgWidth, $imgHeight) = explode('x', $size . 'x');
if ($imgHeight === '') {
    $imgHeight = $imgWidth;
}
$filterOptions = [
    'options' => [
        'min_range' => 0,
        'max_range' => 9999
    ]
];
if (filter_var($imgWidth, FILTER_VALIDATE_INT, $filterOptions) === false) {
    $imgWidth = '640';
}
if (filter_var($imgHeight, FILTER_VALIDATE_INT, $filterOptions) === false) {
    $imgHeight = '480';
}

/**
 * Handle the “type” parameter.
 */
$type = 'png';
if (isset($_GET['type']) && in_array(strtolower($_GET['type']), ['png', 'gif', 'jpg', 'jpeg'])) {
    $type = strtolower($_GET['type']);
}

/**
 * Handle the “text” parameter.
 */
$text = $imgWidth . '×' . $imgHeight;
if (isset($_GET['text']) && strlen($_GET['text'])) {
    $text = filter_var(trim($_GET['text']), FILTER_SANITIZE_STRING);
}
$encoding = mb_detect_encoding($text, 'UTF-8, ISO-8859-1');
if ($encoding !== 'UTF-8') {
    $text = mb_convert_encoding($text, 'UTF-8', $encoding);
}
$text = mb_encode_numericentity($text,
                                [0x0, 0xffff, 0, 0xffff],
                                'UTF-8');

/**
 * Handle the “bg” parameter.
 */
$bg = 'CC0099';
if (isset($_GET['bg']) && (strlen($_GET['bg']) === 6 || strlen($_GET['bg']) === 3)) {
    $bg = strtoupper($_GET['bg']);
    if (strlen($_GET['bg']) === 3) {
        $bg =
            strtoupper($_GET['bg'][0] .
                       $_GET['bg'][0] .
                       $_GET['bg'][1] .
                       $_GET['bg'][1] .
                       $_GET['bg'][2] .
                       $_GET['bg'][2]);
    }
}
list($bgRed, $bgGreen, $bgBlue) = sscanf($bg, "%02x%02x%02x");

/**
 * Handle the “color” parameter.
 */
$color = 'FFFFFF';
if (isset($_GET['color']) && (strlen($_GET['color']) === 6 || strlen($_GET['color']) === 3)) {
    $color = strtoupper($_GET['color']);
    if (strlen($_GET['color']) === 3) {
        $color =
            strtoupper($_GET['color'][0] .
                       $_GET['color'][0] .
                       $_GET['color'][1] .
                       $_GET['color'][1] .
                       $_GET['color'][2] .
                       $_GET['color'][2]);
    }
}
list($colorRed, $colorGreen, $colorBlue) = sscanf($color, "%02x%02x%02x");

/**
 * Define the typeface settings.
 */
$fontFile = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
if ( ! is_readable($fontFile)) {
    $fontFile = 'arial';
}

$fontSize = round(($imgWidth - 50) / 8);
if ($fontSize <= 9) {
    $fontSize = 9;
}

/**
 * Generate the image.
 */
$image     = imagecreatetruecolor($imgWidth, $imgHeight);
$colorFill = imagecolorallocate($image, $colorRed, $colorGreen, $colorBlue);
$bgFill    = imagecolorallocate($image, $bgRed, $bgGreen, $bgBlue);
imagefill($image, 0, 0, $bgFill);
$textBox = imagettfbbox($fontSize, 0, $fontFile, $text);

while ($textBox[4] >= $imgWidth) {
    $fontSize -= round($fontSize / 2);
    $textBox  = imagettfbbox($fontSize, 0, $fontFile, $text);
    if ($fontSize <= 9) {
        $fontSize = 9;
        break;
    }
}
$textWidth  = abs($textBox[4] - $textBox[0]);
$textHeight = abs($textBox[5] - $textBox[1]);
$textX      = ($imgWidth - $textWidth) / 2;
$textY      = ($imgHeight + $textHeight) / 2;
imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);

/**
 * Return the image and destroy it afterwards.
 */
switch ($type) {
    case 'png':
        header('Content-Type: image/png');
        imagepng($image, null, 9);
        break;
    case 'gif':
        header('Content-Type: image/gif');
        imagegif($image);
        break;
    case 'jpg':
    case 'jpeg':
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        break;
}
imagedestroy($image);
