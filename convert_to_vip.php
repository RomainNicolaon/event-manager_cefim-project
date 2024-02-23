<?php
    session_start();

    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });

    if (BaseUsers::getUser() && !$user instanceof VIPUsers) {
        $user = BaseUsers::getUser();
        $user->convertToClientVIP();

        $_SESSION['user'] = $user;
        $_SESSION['message'] = 'Votre compte a été converti en compte VIP';
    }

    header('Location: index.php');
    exit;