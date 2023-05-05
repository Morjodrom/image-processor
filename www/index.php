<?php
$MAX_SIZE = 1024;

$images = array_slice(scandir(__DIR__ . '/source'), 2);
$signIM = new Imagick(__DIR__ . '/sign.png');
$waterMarkIM = new Imagick(__DIR__ . '/watermark40.png');

$input = $_GET;

if (!isset($input['preserve'])) {
    clearFolder('target');
}

if (isset($input['selling'])) {
    $input['opacity'] = 1;
}
$watermarkOpacity = ((float) $input['opacity']) ?: .5;
$waterMarkIM->evaluateImage(\Imagick::EVALUATE_MULTIPLY, $watermarkOpacity, \Imagick::CHANNEL_ALPHA);


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
    $text = (chr($index + 65)) . ($index + 1);
    $draw = new \ImagickDraw();
    $draw->setFont(__DIR__ . '/assets/Foglihtenno07-8qnn.otf');
    $draw->setFontSize(100);
    $draw->setFillColor('rgba(255, 255, 255, 0.15)');
    $draw->setStrokeColor('black');
    $draw->setStrokeWidth(2);

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

        $start = $index + (int) $input['startIndex'];
        drawImageId($start, $imageIM, $textX, $textY);
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