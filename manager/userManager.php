<?php

    include(__DIR__ . '/../configuration/configuration.php');
    class UserManager{
        private $conn;
        private $table;

        public function __construct(){
            $db = new Configuration();
            $this->conn = $db->connect();
            $this->table = "users";
        }

        public function getUserId($id){
            $sql_query = "Select firstname, lastname, email, image_url, role_id".$this->table. 
                " where id = ?";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param('i', $id);
            if(!$sql_obj->execute()){
                die("Error: ". $sql_obj->error);
            }
            $data = $sql_obj->get_result();
            return $data->fetch_assoc();
        }

        public function getUserbyEmail($email){
            $sql_query = "Select id, firstname, lastname, password, image_url, role_id from ".$this->table. 
                " where email = ?";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param('s', $email);
            if(!$sql_obj->execute()){
                die("Error: ". $sql_obj->error);
            }
            $data = $sql_obj->get_result();
            return $data->fetch_assoc();
        }

        public function updateUser($id, $firstname, $lastname, $email, $image_url, $role_id){
            $sql_query = "Update ".$this->table." set firstname=?, lastname=?, email =?, image_url=?, role_id=?, 
                where id =?";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param("sssssi", $firstname, $lastname, $image_url, $email, $image_url, $role_id);
            if(!$sql_obj->execute()){
                die("Error: ".$sql_obj->error);
            }
            return true;
        }

        public function deleteUser($id){
            $sql_query = "Delete from ".$this->table." where id=?";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param("i", $id);
            if(!$sql_obj->execute()){
                die("Error: ".$sql_obj->error);
            }
            return true;
        }

        public function addUser($firstname, $lastname, $email, $password, $image_url){
            $sql_query = "Insert ".$this->table."(firstname, lastname, email, password, image_url, role_id)
                Values(?, ?, ?, ?, ?, 2)";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param("sssss", $firstname, $lastname, $email, $password, $image_url);
            if(!$sql_obj->execute()){
                die("Error: ".$sql_obj->error);
            }
            return true;
        }

        public function checkEmailIfExist($email){
            $sql_query="Select id from ".$this->table." where Email = ?";
            $sql_obj = $this->conn->prepare($sql_query);
            if (!$sql_obj) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            $sql_obj->bind_param("s", $email);

            if(!$sql_obj->execute()){
                die("Error: " . $sql_obj->error);
            }
            $id= null;
            $sql_obj->bind_result($id);
            $sql_obj->fetch();
            $sql_obj->close();
            return $id;
        }
    }