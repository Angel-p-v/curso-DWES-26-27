<?php

    class User{
        private $name;
        private $email;
        private $direction;
        private $id;

            public function __construct($name, $email, $direction, $id){
        $this->name = $name;
        $this->email = $email;
        $this->direction = $direction;
        $this->id = $id;
    }
    

    public function getName(){
        return $this->name;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getDirection(){
        return $this->direction;
    }

    public function getId(){
        return $this->id;
    }
}

