<?php

function getContents($db, $request, $util)
{
  $path = dirname(__DIR__) . '/site-contents/' . $request->params->language;

  $dirList = glob($path . '/*', GLOB_ONLYDIR);
  $pages = array_map(function ($path) {
    $elements = glob($path . '/*.md');
    $ret = json_decode(file_get_contents($path . '/info.json'), true);

    foreach ($elements as $element) {
      $name = pathinfo($element, PATHINFO_FILENAME);
      $ret[$name] = file_get_contents($element);
    }

    if (isset($ret['image_path'])) {
      $imageList = glob($_SERVER['DOCUMENT_ROOT'] . $ret['image_path'] . "/*.{jpg,jpeg,png}", GLOB_BRACE);
      $ret['image_list'] = array_map(function ($image) use ($ret) {
        return $ret['image_path'] . '/' . basename($image);
      }, $imageList);
    }

    return $ret;
  }, $dirList);

  $util->success($pages, true);
}
