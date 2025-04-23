<?php

namespace MiniECommers\Backend\Controllers;

class HomeController
{
    public function index()
    {
        header("Content-Type: application/json");
        http_response_code(200);
        echo json_encode([
            "message" => "Hello World",
        ]);
    }
}
