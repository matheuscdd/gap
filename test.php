<?php

$storage = [
    [
        "type" => 1,
        "name" => "Argamassa",
        "weight" => 266,
        "quantity" => 100
    ],
    [
        "type" => 2,
        "name" => "Cimento",
        "weight" => 266,
        "quantity" => 100,
        "extra" => "ok"
    ]
];

$partials = [
    [
        "type" => 2,
        "name" => "Cimento",
        "weight" => 100,
        "quantity" => 10,
        "extra" => "ok"
    ],
    [
        "type" => 1,
        "name" => "Argamassa",
        "weight" => 266,
        "quantity" => 100
    ],
];

$invalid1 = [
    [
        "type" => 1,
        "name" => "Cimento",
        "weight" => 267,
        "quantity" => 100
    ],
];


$a = $invalid1[0];

$avaliable_storage = $storage;
# load storage partials
foreach($avaliable_storage as &$ref) {
    foreach($partials as $el) {
        if ($el["type"] !== $ref["type"] || $el["name"] !== $ref["name"]) {
            continue;
        }
        $ref["weight"] -= $el["weight"];
        $ref["quantity"] -= $el["quantity"];

    }
}





$stock = [];
foreach($storage as &$ref) {
    foreach($partials as $el) {
        if ($a["type"] !== $ref["type"] || $el["name"] !== $ref["name"]) {
            continue;
        }
    
        if ($el["weight"] > $ref["weight"] || $el["quantity"] > $ref["quantity"]) {
            throw new Exception("A entrega parcial não pode ser maior que a original");
        }
    
        $ref["weight"] -= $el["weight"];
        $ref["quantity"] -= $el["quantity"];
    }    
}

var_dump($avaliable_storage);
// retorna erro
