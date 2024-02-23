<?php
    session_start();

    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <?php include_once 'includes/header.php'; ?>

    <main class="container">
        <section>
            <h1>Créer un événement</h1>

            <form method="post" action="action.php">
                <label for="nom" class="form-label">Nom de l'événement</label>
                <input type="text" name="nom" id="nom" class="form-control" required>

                <label for="lieu" class="form-label">Lieu</label>
                <input type="text" name="lieu" id="lieu" class="form-control" required>

                <label for="places" class="form-label">Nombre de places</label>
                <input type="number" name="places" id="places" class="form-control" min="1" required>
                
                <label for="inscrits" class="form-label">Nombre d'inscrits</label>
                <input type="number" name="inscrits" id="inscrits" class="form-control" required>
                
                <label for="prix" class="form-label">Prix</label>
                <input type="number" name="prix" id="prix" class="form-control" min="1" required>

                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" required>

                <label for="status" class="form-label">Statut</label>
                <select name="status" id="status" class="form-select">
                    <option value="0">Fermé</option>
                    <option value="1">Ouvert</option>
                    <option value="2">Complet</option>
                </select>

                <div class="d-grid gap-2">
                    <?php if (!BaseUsers::isLoggedIn()) { ?>
                        <div class="alert alert-danger mt-2">Vous devez être connecté pour ajouter un événement.</div>
                    <?php } else { ?>
                        <input type="submit" value="Soumettre" class="btn btn-success mt-4">
                    <?php } ?>
                </div>
            </form>
        </section>
    </main>

    <?php include_once 'includes/footer.php'; ?>
</body>
</html>