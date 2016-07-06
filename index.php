<?php

spl_autoload_register(function($className){
    if(strpos($className, "Controller")) {
        require_once 'controllers' . DIRECTORY_SEPARATOR . $className . '.php';
    } else {
        require_once 'components' . DIRECTORY_SEPARATOR . $className . '.php';
    }
});


//var_dump($_SERVER);
//var_dump($_GET);

Router::getInstance()->handle($_SERVER['REQUEST_URI']);
