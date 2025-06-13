<?php
require_once './models/Task.php';
require_once './utils/Response.php';

class TaskController {
    private $db;
    private $task;
    private $response;

    public function __construct($db) {
        $this->db = $db;
        $this->task = new Task($db);
        $this->response = new Response();
    }

    // GET /api/tasks o /api/tasks?completed=1
    public function index() {
        // Verificar si hay filtro por completed
        $completed_filter = isset($_GET['completed']) ? $_GET['completed'] : null;
        
        if($completed_filter !== null) {
            // Usar índice para filtrar por completed
            $completed_value = ($completed_filter == '1' || $completed_filter == 'true') ? 1 : 0;
            $stmt = $this->task->readByStatus($completed_value);
        } else {
            // Sin filtro, obtener todas
            $stmt = $this->task->read();
        }
        
        $num = $stmt->rowCount();

        if($num > 0) {
            $tasks_arr = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $task_item = array(
                    "id" => $id,
                    "user_id" => $user_id,
                    "title" => $title,
                    "completed" => (bool)$completed,
                    "created_at" => $created_at
                );
                
                // Agregar user_name si existe (cuando no se filtra por completed)
                if(isset($user_name)) {
                    $task_item["user_name"] = $user_name;
                }
                
                array_push($tasks_arr, $task_item);
            }
            
            $filter_message = $completed_filter !== null ? " filtradas por estado" : "";
            $this->response->success($tasks_arr, "Tareas obtenidas correctamente" . $filter_message);
        } else {
            $this->response->success(array(), "No se encontraron tareas");
        }
    }

    // GET /api/users/{user_id}/tasks o /api/users/{user_id}/tasks?completed=1
    public function indexByUser($user_id) {
        $this->task->user_id = $user_id;
        
        // Verificar si hay filtro por completed
        $completed_filter = isset($_GET['completed']) ? $_GET['completed'] : null;
        
        if($completed_filter !== null) {
            // Usar índice compuesto user_id + completed
            $completed_value = ($completed_filter == '1' || $completed_filter == 'true') ? 1 : 0;
            $stmt = $this->task->readByUserAndStatus($completed_value);
        } else {
            // Sin filtro
            $stmt = $this->task->readByUser();
        }
        
        $num = $stmt->rowCount();

        if($num > 0) {
            $tasks_arr = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $task_item = array(
                    "id" => $id,
                    "user_id" => $user_id,
                    "title" => $title,
                    "completed" => (bool)$completed,
                    "created_at" => $created_at
                );
                array_push($tasks_arr, $task_item);
            }
            
            $filter_message = $completed_filter !== null ? " filtradas por estado" : "";
            $this->response->success($tasks_arr, "Tareas del usuario obtenidas correctamente" . $filter_message);
        } else {
            $this->response->success(array(), "No se encontraron tareas para este usuario");
        }
    }

    // GET /api/tasks/{id}
    public function show($id) {
        $this->task->id = $id;
        
        if($this->task->readOne()) {
            $task_arr = array(
                "id" => $id,
                "user_id" => $this->task->user_id,
                "title" => $this->task->title,
                "completed" => (bool)$this->task->completed,
                "created_at" => $this->task->created_at
            );
            $this->response->success($task_arr, "Tarea obtenida correctamente");
        } else {
            $this->response->error("Tarea no encontrada", 404);
        }
    }

    // POST /api/users/{user_id}/tasks
    public function store($user_id) {
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->title)) {
            $this->task->user_id = $user_id;
            $this->task->title = $data->title;
            $this->task->completed = isset($data->completed) ? $data->completed : false;

            // Verificar si el usuario existe
            if(!$this->task->userExists()) {
                $this->response->error("Usuario no encontrado", 404);
                return;
            }

            if($this->task->create()) {
                $task_arr = array(
                    "id" => $this->task->id,
                    "user_id" => $this->task->user_id,
                    "title" => $this->task->title,
                    "completed" => (bool)$this->task->completed,
                    "created_at" => date('Y-m-d H:i:s')
                );
                $this->response->success($task_arr, "Tarea creada correctamente", 201);
            } else {
                $this->response->error("No se pudo crear la tarea", 503);
            }
        } else {
            $this->response->error("Datos incompletos. Se requiere title", 400);
        }
    }

    // PUT /api/tasks/{id}
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->title)) {
            $this->task->id = $id;

            // Verificar si la tarea existe
            if(!$this->task->readOne()) {
                $this->response->error("Tarea no encontrada", 404);
                return;
            }

            $this->task->title = $data->title;
            $this->task->completed = isset($data->completed) ? $data->completed : $this->task->completed;

            if($this->task->update()) {
                $task_arr = array(
                    "id" => $id,
                    "user_id" => $this->task->user_id,
                    "title" => $this->task->title,
                    "completed" => (bool)$this->task->completed,
                    "created_at" => $this->task->created_at
                );
                $this->response->success($task_arr, "Tarea actualizada correctamente");
            } else {
                $this->response->error("No se pudo actualizar la tarea", 503);
            }
        } else {
            $this->response->error("Datos incompletos. Se requiere title", 400);
        }
    }

    // DELETE /api/tasks/{id}
    public function destroy($id) {
        $this->task->id = $id;

        if($this->task->readOne()) {
            if($this->task->delete()) {
                $this->response->success(null, "Tarea eliminada correctamente");
            } else {
                $this->response->error("No se pudo eliminar la tarea", 503);
            }
        } else {
            $this->response->error("Tarea no encontrada", 404);
        }
    }
}
?>