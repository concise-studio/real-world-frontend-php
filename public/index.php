<?php

use RealWorldFrontendPhp\App;

error_reporting(E_ALL);
ini_set("display_errors", "1");

require __DIR__ . "/../src/App.php";

$App = new \RealWorldFrontendPhp\App();
$App->run();
