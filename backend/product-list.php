<?php
    require_once __DIR__ . '/vendor/autoload.php';

    use TECWEB\MYAPI\read\Read;

    $productos = new Read('marketzone');
    $productos->list();
    echo $productos->getData();
?>