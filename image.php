<?php
/**
 * Dynamic Dummy Image Generator - as seen on DummyImage.com
 *
 * You can create dummy images with this script kinda easily. Just provide at least the size parameter, that's it.
 * Examples:
 *
 * image.php?size=250x850&type=jpg&bg=ff8800&color=000000
 * - will create a 250px width, 800px height jpg image with orange background and black text
 *
 * image.php?size=250
 * - will create a 250px width, 250px height png image with black background and white text
 *
 * Original idea and script by Russel Heimlich (see http://DummyImage.com). Rewritten by Fabian Beiner.
 *
 * @author Russell Heimlich
 * @author Fabian Beiner <mail -at- fabian-beiner -dot- de)
 */

// Handle the parameters.
$strSize  = (($strSize = $_GET['size'])   ? strtolower($strSize)  : NULL);
$strType  = (($strType = $_GET['type'])   ? strtolower($strType)  : 'png');
$strBg    = (($strBg = $_GET['bg'])       ? strtolower($strBg)    : '000000');
$strColor = (($strColor = $_GET['color']) ? strtolower($strColor) : 'ffffff');

// Now let's check the parameters.
if ($strSize == NULL) {
	die('<b>You have to provide the size of the image.</b> Example: 250x320.</b>');
}
if ($strType != 'png' and $strType != 'gif' and $strType != 'jpg') {
	die('<b>The selected type is wrong. You can chose between PNG, GIF or JPG.');
}
if (strlen($strBg) != 6 and strlen($strBg) != 3) {
	die('<b>You have to provide the background color as hex.</b> Example: 000000 (for black).');
}
if (strlen($strColor) != 6 and strlen($strColor) != 3) {
	die('<b>You have to provide the font color as hex.</b> Example: ffffff (for white).');
}

// Get width and height from current size.
list($strWidth, $strHeight) = split('x', $strSize);
// If no height is given, we'll return a squared picture.
if ($strHeight == NULL) $strHeight = $strWidth;

// Check if size and height are digits, otherwise stop the script.
if (ctype_digit($strWidth) and ctype_digit($strHeight)) {
	// Check if the image dimensions are over 9999 pixel.
	if (($strWidth > 9999) or ($strHeight > 9999)) {
		die('<b>The maximum picture size can be 9999x9999px.</b>');
	}

	// Let's define the font (size. And NEVER go above 9).
	$intFontSize = $strWidth / 16;
	if ($intFontSize < 9) $intFontSize = 9;
	$strFont = "DroidSansMono.ttf";
	$strText = $strWidth . 'x' . $strHeight;

	// Create the picture.
	$objImg = @imagecreatetruecolor($strWidth, $strHeight) or die('Sorry, there is a problem with the GD lib.');

	// Color stuff.
	function html2rgb($strColor) {
		if (strlen($strColor) == 6) {
			list($strRed, $strGreen, $strBlue) = array($strColor[0].$strColor[1], $strColor[2].$strColor[3], $strColor[4].$strColor[5]);
		} elseif (strlen($strColor) == 3) {
			list($strRed, $strGreen, $strBlue) = array($strColor[0].$strColor[0], $strColor[1].$strColor[1], $strColor[2].$strColor[2]);
		}

		$strRed   = hexdec($strRed);
		$strGreen = hexdec($strGreen);
		$strBlue  = hexdec($strBlue);

		return array($strRed, $strGreen, $strBlue);
	}

	$strBgRgb    = html2rgb($strBg);
	$strColorRgb = html2rgb($strColor);
	$strBg       = imagecolorallocate($objImg, $strBgRgb[0], $strBgRgb[1], $strBgRgb[2]);
	$strColor    = imagecolorallocate($objImg, $strColorRgb[0], $strColorRgb[1], $strColorRgb[2]);

	// Create the actual image.
	imagefilledrectangle($objImg, 0, 0, $strWidth, $strHeight, $strBg);

	// Insert the text.
	$arrTextBox    = imagettfbbox($intFontSize, 0, $strFont, $strText);
	$strTextWidth  = $arrTextBox[4] - $arrTextBox[1];
	$strTextHeight = abs($arrTextBox[7]) + abs($arrTextBox[1]);
	$strTextX      = ($strWidth - $strTextWidth) / 2;
	$strTextY      = ($strHeight - $strTextHeight) / 2 + $strTextHeight;
	imagettftext($objImg, $intFontSize, 0, $strTextX, $strTextY, $strColor, $strFont, $strText);

	// Give out the requested type.
	switch ($strType) {
		case 'png':
			header('Content-Type: image/png');
			imagepng($objImg);
			break;
		case 'gif':
			header('Content-Type: image/gif');
			imagegif($objImg);
			break;
		case 'jpg':
			header('Content-Type: image/jpeg');
			imagejpeg($objImg);
			break;
	}

	// Free some memory.
	imagedestroy($objImg);
} else {
	die('<b>You have to provide the size of the image.</b> Example: 250x320.</b>');
}
