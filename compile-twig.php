#!/usr/bin/php
<?php

use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . "/vendor/autoload.php";

$loader = new Twig_Loader_Filesystem(__DIR__ . "/twig");
$twig = new Twig_Environment($loader, array("debug" => true));

$data = Yaml::parse(file_get_contents(__DIR__ . "/resume.yaml"));
$data["cssVersion"] = hash("crc32", file_get_contents(__DIR__ . "/less/style.css"));

$template = $twig->load("resume.twig");
$html = $template->render(["data" => $data]);

file_put_contents(__DIR__ . "/resume.html", $html);