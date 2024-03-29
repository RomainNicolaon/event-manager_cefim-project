<?php
    spl_autoload_register(function ($class_name) {
        require $class_name . '.php';
    });

    class StandardUsers extends BaseUsers {
        use ConvertToClientVIPTrait;
        public const REDUCTION = 1;

        public function __construct(
            protected string $email,
            protected string $password,
            protected string $dateFinAbonnement,
        ) {
            parent::__construct($email, $password, self::REDUCTION);
        }
        
        public function afficheMessageAccueil(): void {
            echo "Bonjour simple client...".PHP_EOL;
        }
    }
