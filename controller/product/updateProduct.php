<?php
    include('../../configuration/headers.php');
    include('../../manager/productManager.php');
    require __DIR__ . '/../../vendor/autoload.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\ExpiredException;
    use Firebase\JWT\Key;


    $headers = getallheaders();
    if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["error" => "unauthorized"]);
        exit();
    }

    $authorizationHeader = $headers['Authorization'];
    $token = null;

    $token = trim(str_replace("Bearer", '', $authorizationHeader));
    if (!$token) {
        http_response_code(401);
        echo json_encode(["error" => "unauthorized"]);
        exit();
    }
    try {
        $key = "your_secret";
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $data = json_decode(file_get_contents("php://input"), true);
        $productManager = new ProductManager();
        $response = $productManager->getProductById($data["id"]);
        // $response_seller_id = $response["seller_id"];
        // echo $decoded->id;
        if ($decoded->role_id == 1 && $response["seller_id"]== $decoded->id) {
    
            $array_response = [];

            if(isset($data["id"])){
                $id = $data["id"];
            }else{
                $array_response["message"] = "Id";
            }

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
        
            if(isset($data["category_id"])){
                $name = $data["category_id"];
            }else{
                $array_response["message"] .= ", category";
            }

            if(!empty($array_response)){
                $array_response .= " was not found";
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
        } 
        else {
            $response = [];
            $response["permissions"] = false;
        }
    } catch (ExpiredException $e) {
        http_response_code(401);
        echo json_encode(["error" => "expired"]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid token"]);
    }
  
    

    







   





