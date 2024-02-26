<?php
    session_start();

    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });

    if (BaseUsers::getUser()) {
        $user = BaseUsers::getUser();
        if ($user->REDUCTION !== VIPUsers::REDUCTION) {
            $user->convertToClientVIP();

            $_SESSION['user'] = $user;
            $_SESSION['message'] .= " Votre compte a été converti en compte VIP";
        } else {
            $_SESSION['error'] = "Déjà VIP !";
        }
    } else {
        $_SESSION['error'] = "Vous devez être connecté pour convertir votre compte en VIP";
    }

    header('Location: index.php');
    exit;