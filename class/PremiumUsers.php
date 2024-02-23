<?php
    spl_autoload_register(function ($class_name) {
        require $class_name . '.php';
    });

    class PremiumUsers extends BaseUsers implements ConvertToClientVIPInterface {
        public const REDUCTION = 0.5;

        public function __construct(
            protected string $email,
            protected string $password,
            protected string $dateFinAbonnement,
        ) {
            parent::__construct($email, $password, self::REDUCTION);
        }

        public function convertToClientVIP(): VIPUsers {
            parent::updateUser($this->getUserMail(), $this->getUserPassword(), 'vip');
            return new VIPUsers($this->getUserMail(), $this->getUserPassword(), $this->getDateFinAbonnement());
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
            echo "Hello client Premium".PHP_EOL;
        }
    }
