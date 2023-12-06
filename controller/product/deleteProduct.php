<?php
    include('../../configuration/headers.php');
    include('../../manager/productManager.php');

    $productManager = new ProductManager();

    $data = json_decode(file_get_contents("php://input"), true);
    $array_response = [];

    if(isset($data["id"])){
        $name = $data["id"];
    }else{
        $array_response["message"] = "id";
    }

    if(!empty($array_response)){
        $array_response .= " was not found";
        $jason_response = json_encode($array_response);
        echo $json_response;
        return $json_response;
    }

    $result = $productManager->deleteProduct($data["id"]);
    if($result==true){
        http_response_code(200);
        $array_response['message'] = "Product deleted succesfully";
        echo json_encode($array_response);  
        return json_encode($array_response);  
    }
    http_response_code(500);
    $array_response['message'] = "Failed to delete product";
    echo json_encode($array_response);
    return json_encode($array_response); 