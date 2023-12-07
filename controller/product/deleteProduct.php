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
        $respose_seller_id = $response["seller_id"];
        echo $decoded->id;
        if ($decoded->role_id == 1 && $respose_seller_id == $decoded->id) {
    
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
        } 
        else {
            $response = [];
            $response["permissions"] = false;
        }
        echo json_encode($response);
    } catch (ExpiredException $e) {
        http_response_code(401);
        echo json_encode(["error" => "expired"]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid token"]);
    }



    
    

    







   