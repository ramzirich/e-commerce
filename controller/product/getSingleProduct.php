<?php
    include('../../configuration/headers.php');
    include('../../manager/productManager.php');

    $productManager = new ProductManager();
    $data = json_decode(file_get_contents("php://input"));

    try{
        $result = $productManager->getProductById($data->id);
        if($result){
            http_response_code(200);
            echo json_encode($result);
            return $result;
        }else{
            http_response_code(500);
            echo json_encode($result);
        }
    }catch(Exception $ex){
        http_response_code(500); 
        echo json_encode(["error" => $e->getMessage()]); 
    }
    