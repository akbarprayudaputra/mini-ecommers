<?php

function getDatabaseConfig(): array
{
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=localhost:3306;dbname=mini_ecommers_test",
                "username" => "root",
                "password" => "",
            ],
            "development" => [
                "url" => "mysql:host=localhost:3306;dbname=mini_ecommers",
                "username" => "root",
                "password" => "",
            ]
        ]
    ];
}
