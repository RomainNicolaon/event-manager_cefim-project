<?php
    session_start();

    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $event = Event::getEventById($id);
        if ($event) {
            $event->deleteEvent();
            $_SESSION['event_deleted'] = $event->getNom();
        } else {
            $_SESSION['error'] = "Cet événement n'existe pas.";
        }
    } else {
        $_SESSION['error'] = "Aucun événement sélectionné.";
    }

    header('Location: index.php');