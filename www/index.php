<?php
$MAX_SIZE = 1024;

$images = array_slice(scandir(__DIR__ . '/source'), 2);
$signIM = new Imagick(__DIR__ . '/sign.png');
$waterMarkIM = new Imagick(__DIR__ . '/watermark40.png');

$input = $_GET;

if (!isset($input['preserve'])) {
    clearFolder('target');
}


/**
 * @param $index
 * @param Imagick $imageIM
 * @param int $textX
 * @param $textY
 * @return void
 * @throws ImagickDrawException
 * @throws ImagickException
 */
function drawImageId($index, Imagick $imageIM, int $textX, $textY): void
{
    $draw = new ImagickDraw();
    $text = (chr($index + 65)) . ($index + 1);
    $draw->setFontSize(100);
    $draw->setFillColor(new ImagickPixel('black'));
    $imageIM->annotateImage($draw, $textX + 2, $textY + 2, 0, $text);
    $imageIM->annotateImage($draw, $textX - 2, $textY - 2, 0, $text);
    $draw->setFontSize(100);
    $draw->setFillColor(new ImagickPixel('white'));
    $imageIM->annotateImage($draw, $textX, $textY, 0, $text);
}

foreach($images as $index => $imageName){
    $imageIM = new Imagick();
    $imageIM->readImage(__DIR__ . DIRECTORY_SEPARATOR . 'source' . DIRECTORY_SEPARATOR . $imageName);
    $imageIM->resizeImage($MAX_SIZE, $MAX_SIZE, IMagick::FILTER_LANCZOS, 0, true);

    if (isset($input['full'])) {
        $imageIM->compositeImage($signIM, IMagick::COMPOSITE_OVER, 0, 0);
        $waterMarkX = ($imageIM->getImageWidth() - $MAX_SIZE) / 2;
        $offsetX = $input['offsetX'] ?: 0;
        $offsetY = $input['offsetY'] ?: 0;

        $iterationsMax = $input['full'] ?: 1;
        $waterMarkIM->resizeImage($MAX_SIZE, $MAX_SIZE, IMagick::FILTER_LANCZOS, 0);

        $watermarkHeight = $waterMarkIM->getImageHeight();
        $yStart = ($imageIM->getImageHeight() - $watermarkHeight) / 2;
        for($i = 0; $i < $iterationsMax; $i++){
            $x = $waterMarkX + ($offsetX * $i);
            $y = $offsetY * $i + $yStart;
            $imageIM->compositeImage($waterMarkIM, IMagick::COMPOSITE_OVER, $x, $y);
            $imageIM->compositeImage($waterMarkIM, IMagick::COMPOSITE_OVER, $x, $MAX_SIZE + $y);
        }


        $textY = $MAX_SIZE / 2 + $yStart;
        $textX = 50;

        drawImageId($index, $imageIM, $textX, $textY);
    }

    $imageIM->writeImage(__DIR__ . DIRECTORY_SEPARATOR . 'target' . DIRECTORY_SEPARATOR . 'wat_' . $imageName);

}

if (!isset($input['preserve'])) {
    clearFolder('source');
}


if (empty($images)) {
    echo "Empty images list";
} else {
    echo "Done";
}

function clearFolder(string $folderName){
    $files = glob($folderName . '/*');
    foreach($files as $file){
        if(is_file($file)) {
            unlink($file);
        }
    }
}