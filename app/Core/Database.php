<?php
    trait Database {
        private function connect() {
            $string = "mysql:hostname=" . DB_HOST . ";dbname="  . DB_NAME . ";charset=utf8mb4";
            return new PDO($string, DB_USER, DB_PASS);
        }

        public function query($query, $data = []) {
            $con = $this->connect();
            $stm = $con->prepare($query);
            $check = $stm->execute($data);
            if ($check) {
                $result =  $stm->fetchAll(PDO::FETCH_OBJ);
                if (is_array($result) && count($result))  {
                    return $result;
                }
            }
            return false;
        }

        public function get_row($query, $data = []) {
            $con = $this->connect();
            $stm = $con->prepare($query);
            $check = $stm->execute($data);
            if ($check) {
                $result =  $stm->fetchAll(PDO::FETCH_OBJ);
                if (is_array($result) && count($result))  {
                    return $result[0];
                }
            }
            return false;
        }
    }
    