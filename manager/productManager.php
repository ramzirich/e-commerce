<?php

    include(__DIR__ . '/../configuration/configuration.php');
    class ProductManager{
        private $conn;
        private $table;

        public function __construct(){
            $db = new Configuration();
            $this->conn = $db->connect();
            $this->table = "products";
        }

        public function getAllProducts(){
            $sql_query = "Select id,name,image_url,category_id,price,seller_id,description from ".$this->table;
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->execute();
            $array = $sql_obj->get_result();
            $response = [];
            while( $row = $array->fetch_assoc() ){
                $response[] = $row;
            }
            return $response;
        }

        public function getProductById($product_id){
            $sql_query = "Select name,image_url,category_id,price,seller_id,description from ".$this->table. 
                " where id = ?";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param('i', $product_id);
            if(!$sql_obj->execute()){
                die("Error: ". $sql_obj->error);
            }
            $data = $sql_obj->get_result();
            return $data->fetch_assoc();
        }

        public function updateProduct($product_id, $name, $image_url, $category_id, $price, $desciption){
            $sql_query = "Update ".$this->table." set name=?, image_url=?, category_id=?, price=?, description=?  
                where id =?";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param("ssidsi", $name, $image_url, $category_id, $price, $desciption, $product_id);
            if(!$sql_obj->execute()){
                die("Error: ".$sql_obj->error);
            }
            return true;
        }

        public function deleteProduct($id){
            $sql_query = "Delete from ".$this->table." where id=?";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param("i", $id);
            if(!$sql_obj->execute()){
                die("Error: ".$sql_obj->error);
            }
            return true;
        }

        public function addProduct($seller_id, $name, $image_url, $category_id, $price, $desciption){
            $sql_query = "Insert ".$this->table."(name, image_url, category_id, price, description, seller_id)
                Values(?, ?, ?, ?, ?, ?)";
            $sql_obj = $this->conn->prepare($sql_query);
            $sql_obj->bind_param("ssidsi", $name, $image_url, $category_id, $price, $desciption, $seller_id);
            if(!$sql_obj->execute()){
                die("Error: ".$sql_obj->error);
            }
            return true;
        }
    }