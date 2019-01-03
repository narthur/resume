#!/usr/bin/php
<?php

namespace Resume;

define("BASEDIR", __DIR__);

use \Symfony\Component\Yaml\Yaml;

require_once __DIR__ . "/vendor/autoload.php";

$buildDir = __DIR__ . "/build";
delTree($buildDir);
mkdir($buildDir);

$less = new \lessc;
$css = $less->compileFile(BASEDIR . "/less/style.less");
$success = file_put_contents("$buildDir/style.css", $css);
if (!$success) { throw new \Exception("Failed to write CSS file"); }

$twig = new Twig();

$data = Yaml::parse(file_get_contents(__DIR__ . "/resume.yaml"));
$data["cssVersion"] = hash("crc32", file_get_contents(__DIR__ . "/build/style.css"));

$twigPages = scanDirectory(__DIR__ . "/page");
array_map(function($twigPage) use($data, $twig, $buildDir) {
  $filename = pathinfo($twigPage, PATHINFO_FILENAME);
  $twig->renderTemplate($twigPage, $data, "$buildDir/$filename.html");
}, $twigPages);

$buildPages = scanDirectory($buildDir);
$twig->renderTemplate(
  "layout-index.twig",
  [ "pages" => $buildPages ],
  __DIR__ . "/index.html"
);

function scanDirectory($path) {
  return array_diff(scandir($path), [".",".."]);
}

function delTree($dir) {
 $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
  }
  return rmdir($dir);
}