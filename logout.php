<?php
    session_start();

    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });

    BaseUsers::logout();
    
    header('Location: login.php');

?>