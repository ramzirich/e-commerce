<?php
    include('../../configuration/headers.php');
    include('../../manager/productManager.php');

    $productManager = new ProductManager();

    $result = $productManager->getAllProducts();
    if($result){
        http_response_code(200);
        echo json_encode($result);
        return $result;
    }else{
        http_response_code(500);
        echo json_encode($result);
    }
