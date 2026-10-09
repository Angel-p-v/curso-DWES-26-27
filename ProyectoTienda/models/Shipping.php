<?php 

    class Shipping {
        private $shipping_id;
        private $date;
        private $idOrder;

        public function __construct($shipping_id, $date, $idOrder) {
            $this->shipping_id = $shipping_id;
            $this->date = $date;
            $this->idOrder = $idOrder;
        }

        public function getShippingId() {
            return $this->shipping_id;
        }

        public function getDate() {
            return $this->date;
        }

        public function getIdOrder() {
            return $this->idOrder;
        }
    }