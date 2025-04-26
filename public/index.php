<?php

require_once "../vendor/autoload.php";

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Config\Router;
use MiniECommers\Backend\Controllers\CategoryController;
use MiniECommers\Backend\Controllers\CustomerController;
use MiniECommers\Backend\Controllers\HomeController;
use MiniECommers\Backend\Controllers\OrderController;
use MiniECommers\Backend\Controllers\ProductController;

Database::getConnection("development");

header("Content-Type: application/json");

Router::get("/", HomeController::class, "index", []);

Router::get("/api/customers", CustomerController::class, "getAllCustomers", []);
Router::get("/api/customer/([0-9a-zA-Z]*)", CustomerController::class, "getCustomerById", []);
Router::post("/api/customer", CustomerController::class, "createCustomer", []);
Router::delete("/api/customer/([0-9]*)", CustomerController::class, "deleteCustomerById", []);

Router::get("/api/products", ProductController::class, "getAllProduct", []);
Router::get("/api/product/([0-9a-zA-Z]*)", ProductController::class, "getProductById", []);
Router::post("/api/product", ProductController::class, "saveProduct", []);
Router::put("/api/product/([0-9]*)", ProductController::class, "updateProduct", []);
Router::delete("/api/product/([0-9]*)", ProductController::class, "deleteProduct", []);

Router::get("/api/categories", CategoryController::class, "getAllCategories", []);
Router::get("/api/category/([0-9a-zA-Z]*)", CategoryController::class, "getCategoryById", []);
Router::post("/api/category", CategoryController::class, "addCategory", []);
Router::delete("/api/category/([0-9a-zA-Z]*)", CategoryController::class, "deleteCategory", []);

Router::get("/api/orders", OrderController::class, "getAllOrders", []);
Router::get("/api/order/([0-9]*)", OrderController::class, "getOrderById", []);
Router::post("/api/order/([0-9]*)", OrderController::class, "createOrder", []);
Router::delete("/api/order/([0-9]*)", OrderController::class, "deleteOrder", []);

Router::run();
