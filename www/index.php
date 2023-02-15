<?php
$MAX_SIZE = 1024;

$images = array_slice(scandir(__DIR__ . '/source'), 2);
$signIM = new Imagick(__DIR__ . '/sign.png');
$waterMarkIM = new Imagick(__DIR__ . '/watermark40.png');
clearFolder('target');

foreach($images as $imageName){
    $imageIM = new Imagick();
    $imageIM->readImage(__DIR__ . DIRECTORY_SEPARATOR . 'source' . DIRECTORY_SEPARATOR . $imageName);

    if (isset($_GET['full'])) {
        $imageIM->compositeImage($signIM, IMagick::COMPOSITE_OVER, 0, 0);
        $waterMarkX = ($imageIM->getImageWidth() - $MAX_SIZE) / 2;
    }

    if (isset($_GET['full'])) {
        $waterMarkIM->resizeImage($MAX_SIZE, $MAX_SIZE, IMagick::FILTER_LANCZOS, 0);
        $imageIM->compositeImage($waterMarkIM, IMagick::COMPOSITE_OVER, $waterMarkX, 0);
        $imageIM->compositeImage($waterMarkIM, IMagick::COMPOSITE_OVER, $waterMarkX, $MAX_SIZE);
    }

    $imageIM->resizeImage($MAX_SIZE, $MAX_SIZE, IMagick::FILTER_LANCZOS, 0, true);
    $imageIM->writeImage(__DIR__ . DIRECTORY_SEPARATOR . 'target' . DIRECTORY_SEPARATOR . 'wat_' . $imageName);
}
clearFolder('source');


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