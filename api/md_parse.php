<?php

use Cocur\Chain\Chain;

require_once(__DIR__ . '/vendor/autoload.php');

$translatedItems = explode("||||||||||", file_get_contents(__DIR__ . '/md-translated-list.txt'));

$pathList = explode("||||||||||", file_get_contents(__DIR__  . '/md-path-list.txt'));

$translatedItems = (new Chain($translatedItems))->map(function ($item) {
    return trim($item);
})->array;

$pathList = (new Chain($pathList))->map(function ($item) {
    return trim($item);
})->array;

$rootPath = __DIR__ . '/site-contents/es';
for ($i = 0; $i < count($translatedItems); $i++) {
    $filePath = $rootPath . explode("/en", $pathList[$i])[1];
    $path = dirname($filePath);
    if (!file_exists($filePath)) mkdir($path, 0777, true);
    $t = $translatedItems[$i];
    $t = preg_replace("/\(enlace[^\)]+\)/", "(mail-link)", $t);
    file_put_contents($filePath, $t);
}
