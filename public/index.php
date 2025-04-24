<?php

require_once "../vendor/autoload.php";

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Config\Router;
use MiniECommers\Backend\Controllers\CustomerController;
use MiniECommers\Backend\Controllers\HomeController;
use MiniECommers\Backend\Controllers\ProductController;

Database::getConnection("development");

header("Content-Type: application/json");

Router::get("/", HomeController::class, "index", []);

Router::get("/api/customers", CustomerController::class, "getAllCustomers", []);
Router::get("/api/customer/([1-9a-zA-Z]*)", CustomerController::class, "getCustomerById", []);
Router::post("/api/customer", CustomerController::class, "createCustomer", []);
Router::delete("/api/customer/([1-9]*)", CustomerController::class, "deleteCustomerById", []);

Router::get("/api/products", ProductController::class, "getAllProduct", []);
Router::get("/api/product/([1-9a-zA-Z]*)", ProductController::class, "getProductById", []);
Router::post("/api/product", ProductController::class, "saveProduct", []);
Router::put("/api/product/([1-9]*)", ProductController::class, "updateProduct", []);

Router::run();
