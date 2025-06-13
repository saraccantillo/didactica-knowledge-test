<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Incluir archivos necesarios
require_once 'config/database.php';
require_once 'routes/api.php';
require_once 'utils/Response.php';

// Inicializar base de datos
$database = new Database();
$db = $database->getConnection();

// Verificar conexión a base de datos
if($db === null) {
    $response = new Response();
    $response->error("Error de conexión a la base de datos", 500);
    exit();
}

// Manejar la petición
handleRequest($db);
?>