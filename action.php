<?php
    session_start();
    
    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });

    require_once 'functions.php';

    if (!BaseUsers::isLoggedIn()) {
        $_SESSION['error'] = "Vous devez être connecté pour ajouter un événement.";
        header('Location: index.php');
        exit;
    }

    if (isset($_POST['nom']) && isset($_POST['date']) && isset($_POST['lieu']) && isset($_POST['prix']) && isset($_POST['places']) && isset($_POST['inscrits']) && isset($_POST['status'])) {
        $nom = $_POST['nom'];
        $date = $_POST['date'];
        $lieu = $_POST['lieu'];
        $prix = $_POST['prix'];
        $places = $_POST['places'];
        $inscrits = $_POST['inscrits'];
        $status = $_POST['status'];

        $date = date('Y-m-d', strtotime($date));

        $nom = sanitize($nom);
        $date = sanitize($date);
        $lieu = sanitize($lieu);
        $prix = sanitize($prix);
        $places = sanitize($places);
        $inscrits = sanitize($inscrits);
        $status = sanitize($status);

        if ($inscrits > $places) {
            $_SESSION['error'] = "Le nombre d'inscrits ne peut pas être supérieur au nombre de places.";
            header('Location: index.php');
            exit;
        }

        if ($prix < 0) {
            $_SESSION['error'] = "Le prix ne peut pas être négatif.";
            header('Location: index.php');
            exit;
        }

        if ($places < 0) {
            $_SESSION['error'] = "Le nombre de places ne peut pas être négatif.";
            header('Location: index.php');
            exit;
        }

        if ($inscrits < 0) {
            $_SESSION['error'] = "Le nombre d'inscrits ne peut pas être négatif.";
            header('Location: index.php');
            exit;
        }

        if ($status < 0 || $status > 2) {
            $_SESSION['error'] = "Le statut n'est pas valide.";
            header('Location: index.php');
            exit;
        }

        if (Event::addEvent($nom, $lieu, $places, $inscrits, $prix, $date, $status)) {
            $_SESSION['event_added'] = $nom;
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'ajout de l'événement.";
        }

        header('Location: index.php');
        exit;
    }
    else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header('Location: index.php');
    }