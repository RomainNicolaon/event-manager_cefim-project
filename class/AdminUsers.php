<?php
    spl_autoload_register(function ($class_name) {
        require $class_name . '.php';
    });
    
    class AdminUsers extends BaseUsers {
        public const REDUCTION = 0;

        public function __construct(
            protected string $email,
            protected string $password,
            protected string $dateFinAbonnement,
        ) {
            parent::__construct($email, $password, self::REDUCTION);
        }
        
        public function afficheMessageAccueil(): void {
            echo "Je vous souhaites la bienvenue oh grand Admin".PHP_EOL;
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
    }
