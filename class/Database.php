<?php
    require_once "includes/config.php";
    
    class Database {
        private static $instance;
        private $pdo;
        private $error;
        
        private function __construct() {
            // Set DSN
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            // Set options
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            // Create a new PDO instance
            try {
                $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            }
            // Catch any errors
            catch(PDOException $e) {
                $this->error = $e->getMessage();
            }
        }
        
        static function getInstance() {
            if(is_null(self::$instance)) {
                self::$instance = new Database;
            }
            return self::$instance;
        }
        
        public function prepare($sql) {
            return $this->pdo->prepare($sql);
        }
        
        public function getError() {
            return $this->error;
        }
        
        public function destroy() {
            self::$instance = null;
        }
    }
