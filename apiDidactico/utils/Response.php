<?php
class Response {
    
    public function success($data = null, $message = "Operación exitosa", $status_code = 200) {
        http_response_code($status_code);
        header('Content-Type: application/json');
        
        $response = array(
            "success" => true,
            "message" => $message
        );
        
        if($data !== null) {
            $response["data"] = $data;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function error($message = "Error en la operación", $status_code = 400) {
        http_response_code($status_code);
        header('Content-Type: application/json');
        
        $response = array(
            "success" => false,
            "error" => $message,
            "code" => $status_code
        );
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
?>