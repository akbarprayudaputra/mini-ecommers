<?php

require_once "../vendor/autoload.php";

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Config\Router;
use MiniECommers\Backend\Controllers\HomeController;

Database::getConnection("development");

Router::get("/", HomeController::class, "index", []);

Router::run();
