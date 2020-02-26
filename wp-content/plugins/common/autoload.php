<?php

spl_autoload_register(function($class_name){
    // if(file_exists($class_name.".php")){
        // echo dirname(__DIR__).'/'.$class_name.".php";
        // die;
        include_once dirname(__DIR__).'/common/'.$class_name.".php";
    // }
})
?>