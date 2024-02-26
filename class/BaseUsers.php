<?php
    spl_autoload_register(function ($class_name) {
        require $class_name . '.php';
    });

    abstract class BaseUsers {
        public const REDUCTION = 0;

        public function __construct(
            protected string $email,
            protected string $password,
            protected float $reduction = self::REDUCTION
        ) {}

        public function getEmail(): string {
            return $this->email;
        }

        public function getPassword(): string {
            return $this->password;
        }

        public function getReduction(): float {
            return $this->reduction;
        }

        public function setEmail(string $email): void {
            $this->email = $email;
        }

        public function setPassword(string $password): void {
            $this->password = $password;
        }

        public function setReduction(float $reduction): void {
            $this->reduction = $reduction;
        }

        abstract public function afficheMessageAccueil(): void;

        public function getUserMail() {
            return $this->email;
        }
        
        public function getUserPassword() {
            return $this->password;
        }

        public function getDateFinAbonnement(): string {
            return '';
        }

		/**
		 * @param string $email
		 * @return |bool
		 * Get user by mail
		*/
        public static function getUser() {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
            } else {
                return false;
            }
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam("email", $email);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            if($result){
                $user_type = ucfirst($result['subscription_type']).'Users';
                return new $user_type($result['email'], $result['password'], $result['end_subscription_date']);
            } else {
                return false;
            }
        }
        
        /**
		 * @param int $user_id
		 * @return |bool
		 * Get user by id
		*/
        public static function getUserById($user_id) {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id");
            $stmt->bindParam("user_id", $user_id);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            if($result){
                return new StandardUsers($result['email'], $result['password'], $result['end_subscription_date']);
            } else {
                return false;
            }
        }
        
		/**
		 * @param string $email
		 * @param string $password
		 * @return bool
		 * Create user
		*/
        public static function createUser($email, $password, $subscription_type = 'standard') {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }

            $password = password_hash($password, PASSWORD_BCRYPT);
            $token = bin2hex(random_bytes(16));
            $date = date('Y-m-d', strtotime('+1 month'));
            $null_date = null;

            $stmt = $db->prepare("INSERT INTO users (email, password, token, subscription_type, end_subscription_date) VALUES (:email, :password, :token, :subscription_type, :end_subscription_date)");
            $stmt->bindParam("email", $email);
            $stmt->bindParam("password", $password);
            $stmt->bindParam("token", $token);
            $stmt->bindParam("subscription_type", $subscription_type);
            if ($subscription_type === 'premium' || $subscription_type === 'vip') {
                $stmt->bindParam("end_subscription_date", $date);
            } else {
                $stmt->bindParam("end_subscription_date", $null_date);
            }
            $stmt->execute();
            $db->destroy();
            return true;
        }
        
		/**
		 * @param string $email
		 * @param string $password
		 * @return bool
		 * Check if user exists
		*/
        public static function userExists($email, $password) {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("SELECT password FROM users WHERE email = :email");
            $stmt->bindParam("email", $email);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            if($result){
                if ($result[0] != null) {
                    if (password_verify($password, $result[0])) {
                        return true;
                    }
                }
            }
            return false;
        }
        
		/**
		 * Logout user
		*/
		public static function logout() {
            session_destroy();
        }

		/**
		 * @return array|bool
		 * Get user infos
		*/
        public function getUserInfos() {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam("email", $this->email);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            if($result){
                return $result;
            } else {
                return false;
            }
        }
        
		/**
		 * @return string
		 * Register session for user with cookies
		*/
        public static function registerSession($email) {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("SELECT token, subscription_type FROM users WHERE email = :email");
            $stmt->bindParam("email", $email);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            $_SESSION['email'] = $email;
            $_SESSION['token'] = $result['token'];
            $_SESSION['subscription_type'] = $result['subscription_type'];
            return true;
        }

        /**
         * @return bool
         * Check if user is logged in
         */
        public static function isLoggedIn() {
            if (isset($_SESSION['token'])) {
                $db = Database::getInstance();
                if ($error = $db->getError()) {
                    die($error);
                }
                $stmt = $db->prepare("SELECT * FROM users WHERE token = :token");
                $stmt->bindParam("token", $_SESSION['token']);
                $stmt->execute();
                $result = $stmt->fetch();
                $db->destroy();
                if($result){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function __toString(): string {
            $reduction = static::REDUCTION;
            $str = <<<EOT
            <div>
                <p>Identité : {$this->getUserMail()}</p>
                <p>Mot de passe : {$this->getUserPassword()}</p>
                <p>Réduction : {$reduction}</p>
            </div>
            <hr>
            EOT;
    
            return $str;
        }

        public function updateUser($email, $password, $subscription_type) {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("UPDATE users SET subscription_type = :subscription_type WHERE email = :email AND password = :password");
            $stmt->bindParam("email", $email);
            $stmt->bindParam("password", $password);
            $stmt->bindParam("subscription_type", $subscription_type);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            if ($result) {
                $_SESSION['subscription_type'] = $subscription_type;
                
                echo 'Votre compte a été mis à jour'.PHP_EOL;
            } else {
                echo 'Erreur lors de la mise à jour de votre compte'.PHP_EOL;
            }
            return true;
        }
    }