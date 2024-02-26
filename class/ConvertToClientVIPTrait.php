<?php

    trait ConvertToClientVIPTrait {
        public function convertToClientVIP(): VIPUsers {
            $_SESSION['message'] = "Conversion vers ClientVIP...".PHP_EOL;
            parent::updateUser($this->getUserMail(), $this->getUserPassword(), 'vip');
            return new VIPUsers($this->getUserMail(), $this->getUserPassword(), $this->getDateFinAbonnement());
        }
    }