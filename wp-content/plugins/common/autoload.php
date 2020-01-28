<?php

spl_autoload_register(function($class_name){
    // if(file_exists($class_name.".php")){

        include_once $class_name.".php";
    // }
})
?>