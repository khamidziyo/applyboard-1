<?php

spl_autoload_register(function ($class_name) {
    // echo dirname(__DIR__).'/common/';die;
    if (file_exists(dirname(__DIR__) . '/common/' . $class_name . ".php")) {

        include_once dirname(__DIR__) . '/common/' . $class_name . ".php";
    }
})
?>
