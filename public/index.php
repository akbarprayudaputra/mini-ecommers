<?php

require_once "../vendor/autoload.php";

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Config\Router;
use MiniECommers\Backend\Controllers\CustomerController;
use MiniECommers\Backend\Controllers\HomeController;

Database::getConnection("development");

header("Content-Type: application/json");

Router::get("/", HomeController::class, "index", []);

Router::get("/api/customers", CustomerController::class, "getAllCustomers", []);
Router::get("/api/customer/([1-9a-zA-Z]*)", CustomerController::class, "getCustomerById", []);
Router::post("/api/customer", CustomerController::class, "createCustomer", []);
Router::delete("/api/customer/([1-9]*)", CustomerController::class, "deleteCustomerById", []);

Router::run();
