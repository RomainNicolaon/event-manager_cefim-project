<?php
    session_start();

    spl_autoload_register(function ($class_name) {
        require 'class/' . $class_name . '.php';
    });

    require_once 'functions.php';

    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['subscription_type']) && isset($_POST['cgu'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $subscription_type = $_POST['subscription_type'];
        $cgu = $_POST['cgu'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "L'adresse email n'est pas valide.";
        } else if (!$cgu) {
            $_SESSION['error'] = "Vous devez cocher la pitite case pour vous inscrire.";
        } else {
            if ($subscription_type === 'standard') {
                $user = new BaseUsers($email, $password);
            } else if ($subscription_type === 'premium') {
                $user = new PremiumUsers($email, $password, date('Y-m-d', strtotime('+1 month')));
            } else if ($subscription_type === 'vip') {
                $user = new VIPUsers($email, $password, date('Y-m-d', strtotime('+1 month')));
            }
            $user->createUser($email, $password, $subscription_type);
            $user->registerSession($email);
            $_SESSION['success'] = "Votre compte a bien été créé ! <a href='login.php'>Connectez-vous</a>.";
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <?php
        include_once 'includes/header.php';

        successHandler();

        errorHandler();
    ?>

    <main class="container">
        <section>
            <h1>Créer un compte</h1>
            <form action="signin.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="subscription_type">Type d'abonnement</label>
                    <select name="subscription_type" id="subscription_type" class="form-select">
                        <option value="standard">Standard (Gratuit)</option>
                        <option value="premium">Premium (9,99€ / mois)</option>
                        <option value="vip">VIP (19,99€ / mois)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="checkbox" id="cgu" name="cgu" class="form-check-input" required>
                    <label for="cgu">Je reconnais ne pas aimer le front</label>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary width-100">Créer mon compte</button>
                </div>
            </form>
        </section>
    </main>

    <?php include_once 'includes/footer.php'; ?>

    <script>
        let cgu_input = document.getElementById('cgu');
        let cgu_label = document.querySelector('label[for="cgu"]');

        cgu_input.addEventListener('change', function() {
            if (cgu_input.checked) {
                cgu_label.innerHTML += ' <bold style="color: green;">(Ah merci, on est d\'accord ! ~ Le php c\'est le meilleur language)</bold>';
            } else {
                cgu_label.innerHTML = 'Je reconnais ne pas aimer le front';
            }
        });
    </script>
</body>
</html>