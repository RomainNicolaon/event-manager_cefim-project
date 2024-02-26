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
    <title>Voir tout les events</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <?php
        include_once 'includes/header.php';

        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['event_added'])) {
            $event = $_SESSION['event_added'];
            echo "<div class='alert alert-success'>L'événement " . $event . " a bien été ajouté !</div>";
            unset($_SESSION['event_added']);
        }

        if (isset($_SESSION['event_deleted'])) {
            $event = $_SESSION['event_deleted'];
            echo "<div class='alert alert-success'>L'événement " . $event . " a bien été supprimé !</div>";
            unset($_SESSION['event_deleted']);
        }

        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }

        $events = Event::getAllEvents(); 
    ?>

    <div id="alert_box"></div>

    <main class="container">
        <h1>Accueil</h1>

        <p>
            <?php
                if ($user = BaseUsers::getUser()) {
                    echo "Bonjour " . $user->getEmail() . "<br>";

                    if ($user instanceof PremiumUsers) {
                        echo "Votre réduction est de " . PremiumUsers::getAbonnementReduction() * 100 . "%<br>";
                    } else if ($user instanceof VIPUsers) {
                        echo "Votre réduction est de " . VIPUsers::getAbonnementReduction() * 100 . "%<br>";
                    }

                    if ($user instanceof PremiumUsers || $user instanceof StandardUsers) {
                        echo "<br><a href='convert_to_vip.php' class='btn btn-primary mt-2' id='convert_to_vip'>
                        Evoluer vers un compte VIP
                        </a><br>";
                    }
                } else {
                    echo "Bonjour, vous n'êtes pas connecté.<br>";
                }
            ?>
        </p>

        <section>
            <h2>Evénements à venir</h2>

            <?php
                echo "<table class='table table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Nom</th>";
                echo "<th>Date</th>";
                echo "<th>Lieu</th>";
                echo "<th>Prix</th>";
                echo "<th>Places disponibles</th>";
                echo "<th>Status</th>";
                if ($user instanceof AdminUsers) {
                    echo "<th>Actions</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                if (empty($events)) {
                    echo "<tr><td colspan='6' class='text-center'>Aucun événement à venir.</td></tr>";
                } else {
                    foreach ($events as $event) {
                        $event_obj = Event::getEventById($event['id']);

                        echo "<tr>";
                        echo "<td>" . $event_obj->getNom() . "</td>";
                        echo "<td>" . $event_obj->getDate() . "</td>";
                        echo "<td>" . $event_obj->getLieu() . "</td>";
                        echo "<td>" . $event_obj->getPrix() . "</td>";
                        echo "<td>" . $event_obj->getAvailablePlaces() . "</td>";
                        echo "<td>" . $event_obj->getStatus() . "</td>";
                        if ($user instanceof AdminUsers) {
                            echo "<td><a href='delete_event.php?id=" . $event_obj->getId() . "' class='btn btn-danger'>Supprimer</a></td>";
                        }
                        echo "</tr>";
                    }
                }

                echo "</tbody>";
                echo "</table>";

                if (isset($_SESSION['email'])) {
                    echo "<a href='add_event.php' class='btn btn-primary'>Ajouter un événement</a>";
                }
            ?>
        </section>
    </main>

    <?php include_once 'includes/footer.php'; ?>
</body>
</html>