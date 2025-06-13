<?php
require_once './config/database.php';

class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $user_id;
    public $title;
    public $completed;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las tareas
    public function read() {
        $query = "SELECT t.id, t.user_id, t.title, t.completed, t.created_at, u.name as user_name 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.user_id = u.id 
                  ORDER BY t.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener tareas por usuario
    public function readByUser() {
        $query = "SELECT id, user_id, title, completed, created_at 
                  FROM " . $this->table_name . " 
                  WHERE user_id = ? 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    // Obtener tareas por usuario y estado (usando indice compuesto)
    public function readByUserAndStatus($status) {
        $query = "SELECT id, user_id, title, completed, created_at 
                  FROM " . $this->table_name . " 
                  WHERE user_id = ? AND completed = ? 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $status);
        $stmt->execute();
        return $stmt;
    }
    
    // Obtener todas las tareas filtradas por estado
    public function readByStatus($status) {
        $query = "SELECT t.id, t.user_id, t.title, t.completed, t.created_at, u.name as user_name 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.user_id = u.id 
                  WHERE t.completed = ? 
                  ORDER BY t.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->execute();
        return $stmt;
    }    

    // Obtener una tarea por ID
    public function readOne() {
        $query = "SELECT t.id, t.user_id, t.title, t.completed, t.created_at, u.name as user_name 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.user_id = u.id 
                  WHERE t.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->user_id = $row['user_id'];
            $this->title = $row['title'];
            $this->completed = $row['completed'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Crear tarea
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, title=:title, completed=:completed";
        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->completed = $this->completed ? 1 : 0;

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":completed", $this->completed);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Actualizar tarea
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title=:title, completed=:completed WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->completed = $this->completed ? 1 : 0;
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':completed', $this->completed);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar tarea
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Verificar si el usuario existe
    public function userExists() {
        $query = "SELECT id FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
?>