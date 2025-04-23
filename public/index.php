<?php

require_once "../vendor/autoload.php";

use MiniECommers\Backend\Config\Router;
use MiniECommers\Backend\Controllers\HomeController;

Router::get("/", HomeController::class, "index", []);

Router::run();
