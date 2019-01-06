#!/usr/bin/php
<?php

namespace Resume;

define("BASEDIR", __DIR__);

use \Symfony\Component\Yaml\Yaml;

require_once __DIR__ . "/vendor/autoload.php";

$factory = new Factory();
$filesystem = $factory->getFilesystem();
$twig = $factory->getTwig();

$buildDir = __DIR__ . "/build";
//$filesystem->deleteTree($buildDir);

//$less = new \lessc;
//$css = $less->compileFile(BASEDIR . "/less/style.less");
//$success = file_put_contents("$buildDir/style.css", $css);
//if (!$success) { throw new \Exception("Failed to write CSS file"); }

$data = Yaml::parse(file_get_contents(__DIR__ . "/resume.yaml"));
$data["cssVersion"] = hash("crc32", file_get_contents(__DIR__ . "/build/style.css"));

$twigPages = $filesystem->scanDir(__DIR__ . "/page");
array_map(function($twigPage) use($data, $twig, $buildDir) {
  $filename = pathinfo($twigPage, PATHINFO_FILENAME);
  $twig->renderTemplate($twigPage, $data, "$buildDir/$filename.html");
}, $twigPages);

$buildPages = $filesystem->scanDir($buildDir);
$twig->renderTemplate(
  "layout-index.twig",
  [ "pages" => $buildPages ],
  __DIR__ . "/index.html"
);
