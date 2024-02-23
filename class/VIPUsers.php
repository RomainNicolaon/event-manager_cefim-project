<?php
    spl_autoload_register(function ($class_name) {
        require $class_name . '.php';
    });
    
    class VIPUsers extends BaseUsers {
        public const REDUCTION = 0.25;

        public function __construct(
            protected string $email,
            protected string $password,
            protected string $dateFinAbonnement,
        ) {
            parent::__construct($email, $password, self::REDUCTION);
        }

        public static function getAbonnementReduction(): float {
            return self::REDUCTION;
        }
    
        public function getDateFinAbonnement(): string {
            return $this->dateFinAbonnement;
        }
    
        public function setDateFinAbonnement(string $dateFinAbonnement): void {
            $this->dateFinAbonnement = $dateFinAbonnement;
        }

        public function afficheMessageAccueil(): void {
            echo "Salutations client VIP !".PHP_EOL;
        }
    }
