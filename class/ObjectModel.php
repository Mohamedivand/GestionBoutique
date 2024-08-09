<?php
    class ObjectModel{
        protected $existe = false;
        protected PDO $objetPDO;
        protected $json_array;

        public function getExiste(){
            return $this->existe;
        }
        public function getObjetPDO(){
            return $this->objetPDO;
        }
        public function getJson_array(){
            return $this->json_array;
        }
    }
?>