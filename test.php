<?php
namespace App;

spl_autoload_register(function (string $classname) {
    $classname = substr($classname, 3);
    include __DIR__ . $classname . '.php';
});

use App\Tests\Test2;

$aClass = new Test2();
$aClass->sayThing('Dickbutt');

// $str1 = "App is an App is an App";
// echo (substr($str1, 3));