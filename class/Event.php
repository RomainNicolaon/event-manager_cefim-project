<?php
    spl_autoload_register(function ($class_name) {
        require $class_name . '.php';
    });
    
    class Event {
        public const STATUS = [
            'Fermé' => '0',
            'Ouvert' => '1',
            'Complet' => '2'
        ];

        public function __construct(
            private string $nom,
            private string $lieu,
            private int $places,
            private int $inscrits,
            private float $prix,
            private string $date,
            private int $status
        ) {}

        public function getNom() {
            return $this->nom;
        }

        public function getLieu() {
            return $this->lieu;
        }

        public function getPlaces() {
            return $this->places;
        }

        public function getInscrits() {
            return $this->inscrits;
        }

        public function getPrix() {
            $subscription_type = $_SESSION['subscription_type'] ?? 'standard';
            if ($subscription_type === 'premium') {
                return $this->prix * PremiumUsers::getAbonnementReduction() . "€ " . "<span class='badge bg-warning'>-" . PremiumUsers::getAbonnementReduction() * 100 . "%</span>";
            } else if ($subscription_type === 'vip') {
                return $this->prix * VIPUsers::getAbonnementReduction() . "€ " . "<span class='badge bg-danger'>-" . VIPUsers::getAbonnementReduction() * 100 . "%</span>";
            } else if ($subscription_type === 'admin') {
                return $this->prix * AdminUsers::getAbonnementReduction() . "€ " . "<span class='badge bg-success'>-" . AdminUsers::getAbonnementReduction() + 100 . "%</span>";
            }
            return $this->prix . "€";
        }

        public function getDate() {
            $date = new DateTime($this->date);
            return $date->format('d/m/Y');
        }

        public function getStatus() {
            return array_search($this->status, self::STATUS);
        }

        public function getId() {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("SELECT id FROM events_table WHERE nom = :nom AND lieu = :lieu AND date = :date");
            $stmt->bindParam("nom", $this->nom);
            $stmt->bindParam("lieu", $this->lieu);
            $stmt->bindParam("date", $this->date);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            return $result['id'];
        }

        /**
         * @param string $nom
         * @param string $lieu
         * @param int $places
         * @param int $inscrits
         * @param float $prix
         * @param string $date
         * @return bool
         * Add event
         * @throws Exception
         * @throws PDOException
         */
        public static function addEvent($nom, $lieu, $places, $inscrits, $prix, $date, $status = self::STATUS['CLOSED']) {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("INSERT INTO events_table (nom, lieu, places, inscrits, prix, date, status) VALUES (:nom, :lieu, :places, :inscrits, :prix, :date, :status)");
            $stmt->bindParam("nom", $nom);
            $stmt->bindParam("lieu", $lieu);
            $stmt->bindParam("places", $places);
            $stmt->bindParam("inscrits", $inscrits);
            $stmt->bindParam("prix", $prix);
            $stmt->bindParam("date", $date);
            $stmt->bindParam("status", $status);
            $stmt->execute();
            $db->destroy();
            return true;
        }

        /**
         * @return array
         * Get events
         * @throws Exception
         * @throws PDOException
         */
        public static function getAllEvents() {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("SELECT * FROM events_table");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $db->destroy();
            return $result;
        }

        /**
         * @param int $id
         * @return object|bool
         * Get event by id
         * @throws Exception
         * @throws PDOException
         */
        public static function getEventById($id) {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("SELECT * FROM events_table WHERE id = :id");
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $result = $stmt->fetch();
            $db->destroy();
            if ($result) {
                return new Event($result['nom'], $result['lieu'], $result['places'], $result['inscrits'], $result['prix'], $result['date'], $result['status']);
            } else {
                return false;
            }
        }

        /**
         * @return bool
         * Delete event
         * @throws Exception
         * @throws PDOException
         */
        public function deleteEvent() {
            $db = Database::getInstance();
            if ($error = $db->getError()) {
                die($error);
            }
            $stmt = $db->prepare("DELETE FROM events_table WHERE nom = :nom AND lieu = :lieu AND date = :date");
            $stmt->bindParam("nom", $this->nom);
            $stmt->bindParam("lieu", $this->lieu);
            $stmt->bindParam("date", $this->date);
            $stmt->execute();
            $db->destroy();
            return true;
        }

        /**
         * @return int
         * Get left places
         * @throws Exception
         * @throws PDOException
         */
        public function getAvailablePlaces() {
            $total = $this->places - $this->inscrits;
            return $total;
        }
    }