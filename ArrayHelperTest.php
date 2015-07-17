<?php

namespace yadjet\test;

require 'vendor/autoload.php';

$array = array('', 1, 2, 3, 'a', 'b', null, false);
\yadjet\helpers\ArrayHelper::removeEmpty($array);
$array = array(
    [
        'id' => 1,
        'name' => '11'
    ],
    [
        'id' => 2,
        'name' => '22'
    ]
);
\yadjet\helpers\ArrayHelper::getCols($array, 'id');
var_dump($array);
