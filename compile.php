#!/usr/bin/php
<?php

namespace Resume;

define("BASEDIR", __DIR__);

require_once __DIR__ . "/vendor/autoload.php";

$factory = new Factory();
$app = $factory->get("App");
$app->compile();
