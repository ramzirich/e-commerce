<?php
    include('../../configuration/headers.php');
    include('../../manager/userManager.php');
    require __DIR__ . '/../../vendor/autoload.php';

    use Firebase\JWT\JWT;

    $userManager = new UserManager();
    $response = [];
    $array_response = [];
    $data = json_decode(file_get_contents("php://input"), true);
    // echo $data[""] ;
    if(!isset($data["email"]) || $data["email"] =="" ){
        $array_response["message"] = "email";
    }

    if(!isset($data["password"]) || $data["password"] =="" ){
        $array_response["message"] .= " password ";
    }

    if(!empty($array_response)){
        $array_response["message"] .= " were not found ";
        $json_response = json_encode($array_response);
        echo $json_response;
        return $json_response;  
    }
    $response =  $userManager->getUserbyEmail($data["email"]);
    // echo $response["firstname"];
    // echo count($response);
    if($response != null) {
        if (password_verify($data["password"], $response["password"])) {
            $key = "your_secret";
            $payload_array = [];
            $payload_array["id"] = $response["id"];
            $payload_array["firstname"] = $response["firstname"];
            $payload_array["lastname"] = $response["lastname"];
            $payload_array["email"] = $response["email"];
            $payload_array["role_id"] = $response["role_id"];
            $payload_array["exp"] = time() + 3600;
            $payload = $payload_array;
            $response['status'] = 'logged in';
            $jwt = JWT::encode($payload, $key, 'HS256');
            $response['jwt'] = $jwt;
            echo json_encode($response);
        } else {
            $response['status'] = 'wrong credentials';
            echo json_encode($response);
        }
    }