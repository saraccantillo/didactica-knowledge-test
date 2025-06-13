<?php
require_once './models/User.php';
require_once './utils/Response.php';

class UserController {
    private $db;
    private $user;
    private $response;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
        $this->response = new Response();
    }

    // GET /api/users
    public function index() {
        $stmt = $this->user->read();
        $num = $stmt->rowCount();

        if($num > 0) {
            $users_arr = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email
                );
                array_push($users_arr, $user_item);
            }
            
            $this->response->success($users_arr, "Usuarios obtenidos correctamente");
        } else {
            $this->response->success(array(), "No se encontraron usuarios");
        }
    }

    // GET /api/users/{id}
    public function show($id) {
        $this->user->id = $id;
        
        if($this->user->readOne()) {
            $user_arr = array(
                "id" => $id,
                "name" => $this->user->name,
                "email" => $this->user->email
            );
            $this->response->success($user_arr, "Usuario obtenido correctamente");
        } else {
            $this->response->error("Usuario no encontrado", 404);
        }
    }

    // POST /api/users
    public function store() {
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->name) && !empty($data->email)) {
            $this->user->name = $data->name;
            $this->user->email = $data->email;

            // Verificar si el email ya existe
            if($this->user->emailExists()) {
                $this->response->error("El email ya está registrado", 400);
                return;
            }

            if($this->user->create()) {
                $user_arr = array(
                    "id" => $this->user->id,
                    "name" => $this->user->name,
                    "email" => $this->user->email
                );
                $this->response->success($user_arr, "Usuario creado correctamente", 201);
            } else {
                $this->response->error("No se pudo crear el usuario", 503);
            }
        } else {
            $this->response->error("Datos incompletos. Se requieren name y email", 400);
        }
    }

    // PUT /api/users/{id}
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->name) && !empty($data->email)) {
            $this->user->id = $id;
            $this->user->name = $data->name;
            $this->user->email = $data->email;

            // Verificar si el usuario existe
            if(!$this->user->readOne()) {
                $this->response->error("Usuario no encontrado", 404);
                return;
            }

            // Verificar si el email ya existe (excluyendo el usuario actual)
            if($this->user->emailExists()) {
                $this->response->error("El email ya está registrado", 400);
                return;
            }

            $this->user->name = $data->name;
            $this->user->email = $data->email;

            if($this->user->update()) {
                $user_arr = array(
                    "id" => $id,
                    "name" => $this->user->name,
                    "email" => $this->user->email
                );
                $this->response->success($user_arr, "Usuario actualizado correctamente");
            } else {
                $this->response->error("No se pudo actualizar el usuario", 503);
            }
        } else {
            $this->response->error("Datos incompletos. Se requieren name y email", 400);
        }
    }

    // DELETE /api/users/{id}
    public function destroy($id) {
        $this->user->id = $id;

        if($this->user->readOne()) {
            if($this->user->delete()) {
                $this->response->success(null, "Usuario eliminado correctamente");
            } else {
                $this->response->error("No se pudo eliminar el usuario", 503);
            }
        } else {
            $this->response->error("Usuario no encontrado", 404);
        }
    }
}
?>