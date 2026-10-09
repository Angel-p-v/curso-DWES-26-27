<?php

    class Order {
        private $id;
        private $date;
        private $state;
        private $totalAmount;
        private $userId;

        public function __construct($id, $date, $state, $totalAmount, $userId) {
            $this->id = $id;
            $this->date = $date;
            $this->state = $state;
            $this->totalAmount = $totalAmount;
            $this->userId = $userId;
        }

        public function getId() {
            return $this->id;
        }

        public function getDate() {
            return $this->date;
        }

        public function getState() {
            return $this->state;
        }

        public function getTotalAmount() {
            return $this->totalAmount;
        }

        public function getUserId() {
            return $this->userId;
        }
    }