<?php

    class Shopping_cart{
        private $idUser;
        private $id;

        public function __construct($idUser, $id){
            $this->idUser = $idUser;
            $this->id = $id;
        }

        public function getIdUser(){
            return $this->idUser;
        }

        public function getId(){
            return $this->id;
        }
}