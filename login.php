<?php
    session_start();

    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });

    require_once("functions.php");

    if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
        if (BaseUsers::isLoggedIn()) {
            header('Location: index.php');
            exit;
        }
    }

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "L'adresse email n'est pas valide.";
        } else {
            if (BaseUsers::userExists($email, $password)) {
                BaseUsers::registerSession($email);
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['error'] = "L'adresse email ou le mot de passe est incorrect.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <?php
        include_once 'includes/header.php'; 

        successHandler();

        errorHandler();

        if (!BaseUsers::isLoggedIn()) {
    ?>

        <main class="container">
            <section>
                <h1>Se connecter</h1>
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary width-100">Se connecter</button>
                    </div>
                </form>
            </section>
        </main>

    <?php 
        } else {
            echo "<div class='alert alert-success'>Vous êtes déjà connecté.</div>";
        }

        include_once 'includes/footer.php'; 
    ?>
</body>
</html>