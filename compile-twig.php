#!/usr/bin/php
<?php

use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . "/vendor/autoload.php";

$loader = new Twig_Loader_Filesystem([
  __DIR__ . "/twig",
  __DIR__ . "/page"
]);
$twig = new Twig_Environment($loader, array("debug" => true));

$data = Yaml::parse(file_get_contents(__DIR__ . "/resume.yaml"));
$data["cssVersion"] = hash("crc32", file_get_contents(__DIR__ . "/less/style.css"));

$twigPages = array_diff(scandir(__DIR__ . "/page"), [".",".."]);
array_map(function($twigPage) use($data, $twig) {
  $template = $twig->load($twigPage);
  $html = $template->render(["data" => $data]);
  $filename = pathinfo($twigPage, PATHINFO_FILENAME);
  file_put_contents(__DIR__ . "/$filename.html", $html);
}, $twigPages);
