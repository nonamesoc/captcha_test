<?php

session_start();

$imagick = new Imagick();
$bg = new ImagickPixel();
$bg->setColor('white');

$imagickDraw = new ImagickDraw();
$imagickDraw->setFont('DejaVu-Sans');
$imagickDraw->setFontSize(20);

$alphanum = 'ABXZRMHTL23456789';
$string = substr(str_shuffle($alphanum), 2, 6);
$_SESSION['captcha_code'] = $string;

$imagick->newImage(85, 30, $bg);
$imagick->annotateImage($imagickDraw, 4, 20, 0, $string);
$imagick->swirlImage(20);

$imagickDraw->line(rand(0, 70), rand(0, 30), rand(0, 70), rand(0, 30));
$imagickDraw->line(rand(0, 70), rand(0, 30), rand(0, 70), rand(0, 30));
$imagickDraw->line(rand(0, 70), rand(0, 30), rand(0, 70), rand(0, 30));
$imagickDraw->line(rand(0, 70), rand(0, 30), rand(0, 70), rand(0, 30));
$imagickDraw->line(rand(0, 70), rand(0, 30), rand(0, 70), rand(0, 30));

$imagick->drawImage($imagickDraw);
$imagick->setImageFormat('png');

header("Content-Type: image/{$imagick->getImageFormat()}");
echo $imagick->getImageBlob();
