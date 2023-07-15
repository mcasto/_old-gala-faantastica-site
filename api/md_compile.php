<?php

use Cocur\Chain\Chain;

require_once(__DIR__ . '/vendor/autoload.php');

$path = __DIR__ . '/site-contents/en';


$output = [];
function pushOutput($item)
{
    global $output;
    $output[] = $item;
}

(new Chain(glob($path . "/*", GLOB_ONLYDIR)))
    ->map(function ($path) {
        $elements = glob($path . '/*.md');

        foreach ($elements as $element) {
            pushOutput([
                'path' => $element,
                'contents' => strip_tags(file_get_contents($element))
            ]);
        }
    });

$pathList = (new Chain($output))
    ->map(function ($item) {
        return $item['path'];
    })->join("\n||||||||||\n");

$contentList = (new Chain($output))
    ->map(function ($item) {
        return $item['contents'];
    })->join("\n||||||||||\n");

file_put_contents(__DIR__ . '/md-path-list.txt', $pathList);
file_put_contents(__DIR__ . '/md-content-list.txt', $contentList);
