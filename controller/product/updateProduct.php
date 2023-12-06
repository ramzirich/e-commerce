<?php
    include('../../configuration/headers.php');
    include('../../manager/productManager.php');

    $productManager = new ProductManager();

    $data = json_decode(file_get_contents("php://input"), true);
    $array_response = [];
    if(isset($data["name"])){
        $name = $data["name"];
    }else{
        $array_response["message"] = "Name";
    }

    if(isset($data["image_url"])){
        $name = $data["image_url"];
    }else{
        $array_response["message"] .= ", image_url";
    }

    if(isset($data["price"])){
        $name = $data["price"];
    }else{
        $array_response["message"] .= ", price";
    }

    if(isset($data["seller_id"])){
        $name = $data["seller_id"];
    }else{
        $array_response["message"] .= ", seller";
    }

    if(isset($data["price"])){
        $name = $data["price"];
    }else{
        $array_response["message"] .= ", price";
    }

    if(isset($data["category_id"])){
        $name = $data["category_id"];
    }else{
        $array_response["message"] .= ", category";
    }

    if(!empty($array_response)){
        $array_response .= " were not found";
        $jason_response = json_encode($array_response);
        echo $json_response;
        return $json_response;
    }

    $result = $productManager->updateProduct($data["id"], $data["name"], $data["image_url"],
                                    $data["category_id"], $data["price"], $data["description"]);

    if($result){
        http_response_code(200);
        $array_response['message'] = "Product updated succesfully";
        echo json_encode($array_response);  
        return json_encode($array_response);  
    }
    http_response_code(500);
    $array_response['message'] = "Failed to update product";
    echo json_encode($array_response);
    return json_encode($array_response); 