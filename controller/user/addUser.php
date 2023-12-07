<?php
    include('../../configuration/headers.php');
    include('../../manager/userManager.php');

    $userManager = new UserManager();

    $data = json_decode(file_get_contents("php://input"), true);
    $array_response = [];
    $email_inuse_response = [];
    $final_response = [];
    $hashed_password = "";
    echo isset($data["firstname"]) ;
    if(isset($data["firstname"]) && $data["firstname"] !="" ){
        $firstname = $data["firstname"];
    }else{
        $array_response["message"] = "firstname";
    }

    // if(isset($data["image_url"])){
    //     $image_url = $data["image_url"];
    // }else{
    //     $array_response["message"] .= ", image_url";
    // }

    if(isset($data["lastname"]) && $data["lastname"] !="" ){
        $lastname = $data["lastname"];
    }else{
        $array_response["message"] .= ", lastname";
    }

    if(isset($data["email"])){
        $email = $data["email"];
        if($userManager->checkEmailIfExist($email) !=null){
            $email_inuse_response["email_message"] = "Email already in use";
        }
    }else{
        $array_response["message"] .= ", email";
    }

    if(isset($data["password"])){
        $password = $data["password"];
        $hashed_password = password_hash($data["password"], PASSWORD_DEFAULT);
    }else{
        $array_response["message"] .= ", password";
    }


    if(!empty($array_response)){
        $array_response["message"] .= " were not found";
        $final_response["message"] = $array_response;
    }

    if(!empty($email_inuse_response)){
        $final_response["error_message"] = $email_inuse_response;
    }

    if(!empty($final_response)){
        $json_response = json_encode($final_response);
        echo $json_response;
        return $json_response;  
    }

    echo $hashed_password;
    $result = $userManager->addUser($data["firstname"], $data["lastname"], $data["email"], $hashed_password,
                                        $data["image_url"]);

    if($result){
        http_response_code(200);
        $array_response['message'] = "user created succesfully";
        echo json_encode($array_response);  
        return json_encode($array_response);  
    }
    http_response_code(500);
    $array_response['message'] = "Failed to create user";
    echo json_encode($array_response);
    return json_encode($array_response); 
    

    
    

    

