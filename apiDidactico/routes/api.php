<?php
require_once './controllers/UserController.php';
require_once './controllers/TaskController.php';

function handleRequest($db) {
    $request_method = $_SERVER["REQUEST_METHOD"];
    $request_uri = $_SERVER["REQUEST_URI"];
    
    // Remover query string si existe
    $path = parse_url($request_uri, PHP_URL_PATH);
    
    // Remover /apiDidactico del path si existe
    $path = str_replace('/apiDidactico', '', $path);
    
    // Dividir el path en segmentos
    $path_segments = array_filter(explode('/', $path));
    $path_segments = array_values($path_segments); // Reindexar array
    
    $response = new Response();
    
    try {
        switch($request_method) {
            case 'GET':
                handleGetRequest($db, $path_segments, $response);
                break;
            case 'POST':
                handlePostRequest($db, $path_segments, $response);
                break;
            case 'PUT':
                handlePutRequest($db, $path_segments, $response);
                break;
            case 'DELETE':
                handleDeleteRequest($db, $path_segments, $response);
                break;
            default:
                $response->error("Método no permitido", 405);
                break;
        }
    } catch(Exception $e) {
        $response->error("Error interno del servidor: " . $e->getMessage(), 500);
    }
}

function handleGetRequest($db, $segments, $response) {
    if(empty($segments)) {
        $response->error("Endpoint no válido", 404);
        return;
    }
    
    if($segments[0] === 'users') {
        $userController = new UserController($db);
        
        if(count($segments) === 1) {
            // GET /users
            $userController->index();
        } elseif(count($segments) === 2) {
            // GET /users/{id}
            $userController->show($segments[1]);
        } elseif(count($segments) === 3 && $segments[2] === 'tasks') {
            // GET /users/{user_id}/tasks
            $taskController = new TaskController($db);
            $taskController->indexByUser($segments[1]);
        } else {
            $response->error("Endpoint no válido", 404);
        }
    } elseif($segments[0] === 'tasks') {
        $taskController = new TaskController($db);
        
        if(count($segments) === 1) {
            // GET /tasks
            $taskController->index();
        } elseif(count($segments) === 2) {
            // GET /tasks/{id}
            $taskController->show($segments[1]);
        } else {
            $response->error("Endpoint no válido", 404);
        }
    } else {
        $response->error("Endpoint no válido", 404);
    }
}

function handlePostRequest($db, $segments, $response) {
    if($segments[0] === 'users') {
        if(count($segments) === 1) {
            // POST /users
            $userController = new UserController($db);
            $userController->store();
        } elseif(count($segments) === 3 && $segments[2] === 'tasks') {
            // POST /users/{user_id}/tasks
            $taskController = new TaskController($db);
            $taskController->store($segments[1]);
        } else {
            $response->error("Endpoint no válido", 404);
        }
    } else {
        $response->error("Endpoint no válido", 404);
    }
}

function handlePutRequest($db, $segments, $response) {
    if($segments[0] === 'users' && count($segments) === 2) {
        // PUT /users/{id}
        $userController = new UserController($db);
        $userController->update($segments[1]);
    } elseif($segments[0] === 'tasks' && count($segments) === 2) {
        // PUT /tasks/{id}
        $taskController = new TaskController($db);
        $taskController->update($segments[1]);
    } else {
        $response->error("Endpoint no válido", 404);
    }
}

function handleDeleteRequest($db, $segments, $response) {
    if($segments[0] === 'users' && count($segments) === 2) {
        // DELETE /users/{id}
        $userController = new UserController($db);
        $userController->destroy($segments[1]);
    } elseif($segments[0] === 'tasks' && count($segments) === 2) {
        // DELETE /tasks/{id}
        $taskController = new TaskController($db);
        $taskController->destroy($segments[1]);
    } else {
        $response->error("Endpoint no válido", 404);
    }
}
?>