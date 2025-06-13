"use client"

import React, { useState, useEffect } from "react"
import axios from "axios"
import "../styles/listUsers.css"

export default function ListUsers() {
  const [users, setUsers] = useState([])
  const [selectedUser, setSelectedUser] = useState(null)
  const [showTaskForm, setShowTaskForm] = useState(false)
  const [newTask, setNewTask] = useState({
    title: "",
  })
  const [taskFilter, setTaskFilter] = useState("all") // Estado para el filtro de tareas

  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const response = await axios.get("http://localhost/apiDidactico/users")
        setUsers(response.data.data)
      } catch (error) {
        console.error("Error al obtener los usuarios:", error)
      }
    }

    fetchUsers()
  }, [])

  const fetchUserTasks = async (userId, filter = "all") => {
    try {
      const response = await axios.get(`http://localhost/apiDidactico/users/${userId}/tasks`, {
        params: filter === "all" ? {} : { completed: filter === "completed" ? 1 : 0 }
      });
      return response.data.data; // Retornar las tareas obtenidas
    } catch (error) {
      console.error("Error al obtener las tareas del usuario:", error);
      return []; // Retornar un array vacío en caso de error
    }
  }

  const handleUserSelect = async (user) => {
    setSelectedUser(user)
    const tasks = await fetchUserTasks(user.id) // Obtener tareas del usuario seleccionado
    setSelectedUser({ ...user, tasks: tasks || [] }) // Asegurarse de que tasks sea un array
  }

  const handleCreateTask = async (e) => {
    e.preventDefault()
    if (!selectedUser) return

    try {
      const response = await axios.post(`http://localhost/apiDidactico/users/${selectedUser.id}/tasks`, {
        user_id: selectedUser.id,
        title: newTask.title,
        completed: 0 // Inicialmente no completada
      });

      // Agregar la nueva tarea a la lista de tareas del usuario
      const newTaskData = response.data.data; 
      setSelectedUser((prev) => ({
        ...prev,
        tasks: [...prev.tasks, newTaskData],
      }));

      // Limpiar el formulario
      setNewTask({ title: "" });
      setShowTaskForm(false); // Ocultar el formulario
    } catch (error) {
      console.error("Error al crear la tarea:", error);
    }
  }

  const updateTaskStatus = async (taskId, completed, title) => {
    try {
      await axios.put(`http://localhost/apiDidactico/tasks/${taskId}`, {
        completed: completed ? 1 : 0, // 1 para completada, 0 para no completada
        title: title
      });
    } catch (error) {
      console.error("Error al actualizar el estado de la tarea:", error);
    }
  }

  const handleTaskToggle = async (taskId, title) => {
    if (!selectedUser) return

    const updatedTasks = selectedUser.tasks.map((task) => {
      if (task.id === taskId) {
        const updatedTask = { ...task, completed: !task.completed }
        updateTaskStatus(taskId, updatedTask.completed, title); // Actualizar el estado en la API
        return updatedTask;
      }
      return task;
    });

    const updatedUser = { ...selectedUser, tasks: updatedTasks }
    setSelectedUser(updatedUser)

    // Actualizar la lista de usuarios
    setUsers(users.map((user) => (user.id === selectedUser.id ? updatedUser : user)))

    // Volver a cargar las tareas según el filtro actual
    const tasks = await fetchUserTasks(selectedUser.id, taskFilter);
    setSelectedUser({ ...updatedUser, tasks: tasks || [] });
  }

  const deleteTask = async (taskId) => {
    if (!selectedUser) return

    try {
      await axios.delete(`http://localhost/apiDidactico/tasks/${taskId}`); // Llamada a la API para eliminar la tarea

      // Actualizar la lista de tareas del usuario
      const updatedTasks = selectedUser.tasks.filter((task) => task.id !== taskId);
      const updatedUser = { ...selectedUser, tasks: updatedTasks };
      setSelectedUser(updatedUser);

      // Actualizar la lista de usuarios
      setUsers(users.map((user) => (user.id === selectedUser.id ? updatedUser : user)));
    } catch (error) {
      console.error("Error al eliminar la tarea:", error);
    }
  }

  return (
    <div className="app">
      <main className="main-content">
        <div className="users-container">
          {/* Panel de usuarios */}
          <div className="users-panel">
            <h2>Lista de Usuarios</h2>
            <div className="users-list">
              {users.map((user) => (
                <div
                  key={user.id}
                  className={`user-card ${selectedUser?.id === user.id ? "selected" : ""}`}
                  onClick={() => handleUserSelect(user)}
                >
                  <div className="user-info">
                    <h3>{user.name}</h3>
                    <p>{user.email}</p>
                  </div>
                  <div className="user-status">
                    <div className="status-indicator"></div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Panel de tareas */}
          <div className="tasks-panel">
            {selectedUser ? (
              <>
                <div className="tasks-header">
                  <h2>Tareas de {selectedUser.name}</h2>
                  <button className="btn-primary" onClick={() => setShowTaskForm(!showTaskForm)}>
                    {showTaskForm ? "Cancelar" : "Nueva Tarea"}
                  </button>
                </div>

                <div className="tasks-filters">
                  <h3>Filtrar tareas:</h3>
                  <div className="filter-buttons">
                    <button
                      className={`filter-btn ${taskFilter === "all" ? "active" : ""}`}
                      onClick={async () => {
                        setTaskFilter("all");
                        const tasks = await fetchUserTasks(selectedUser.id, "all");
                        setSelectedUser({ ...selectedUser, tasks });
                      }}
                    >
                      Todas 
                    </button>
                    <button
                      className={`filter-btn ${taskFilter === "pending" ? "active" : ""}`}
                      onClick={async () => {
                        setTaskFilter("pending");
                        const tasks = await fetchUserTasks(selectedUser.id, "pending");
                        setSelectedUser({ ...selectedUser, tasks });
                      }}
                    >
                      Pendientes
                    </button>
                    <button
                      className={`filter-btn ${taskFilter === "completed" ? "active" : ""}`}
                      onClick={async () => {
                        setTaskFilter("completed");
                        const tasks = await fetchUserTasks(selectedUser.id, "completed");
                        setSelectedUser({ ...selectedUser, tasks });
                      }}
                    >
                      Completadas 
                    </button>
                  </div>
                </div>

                {showTaskForm && (
                  <div className="task-form-container">
                    <form className="task-form" onSubmit={handleCreateTask}>
                      <h3>Crear Nueva Tarea</h3>
                      <div className="form-group">
                        <label>Título de la tarea:</label>
                        <input
                          type="text"
                          value={newTask.title}
                          onChange={(e) => setNewTask({ ...newTask, title: e.target.value })}
                          required
                        />
                      </div>
                      <div className="form-actions">
                        <button type="submit" className="btn-primary">
                          Crear Tarea
                        </button>
                        <button type="button" className="btn-secondary" onClick={() => setShowTaskForm(false)}>
                          Cancelar
                        </button>
                      </div>
                    </form>
                  </div>
                )}

                <div className="tasks-list">
                  {selectedUser.tasks && selectedUser.tasks.length === 0 ? (
                    <div className="no-tasks">
                      <p>No hay tareas asignadas</p>
                    </div>
                  ) : (
                    selectedUser.tasks?.map((task) => (
                      <div key={task.id} className={`task-card ${task.completed ? "completed" : ""}`}>
                        <div className="task-checkbox">
                          <input
                            type="checkbox"
                            checked={task.completed}
                            onChange={() => handleTaskToggle(task.id, task.title)} // Llamar a la función para cambiar el estado
                            id={`task-${task.id}`}
                          />
                          <label htmlFor={`task-${task.id}`}></label>
                        </div>
                        <div className="task-content">
                          <h4>{task.title}</h4>
                          <p>{task.description}</p>
                          {task.created_at && (
                            <div className="task-due-date">
                              <span>Fecha de creación: {new Date(task.created_at).toLocaleDateString()}</span>
                            </div>
                          )}
                        </div>
                        <div className="task-status">
                          <span className={`status-badge ${task.completed ? "completed" : "pending"}`}>
                            {task.completed ? "Completada" : "Pendiente"}
                          </span>
                        </div>
                        <div className="task-actions">
                          <button className="btn-delete" onClick={() => deleteTask(task.id)} title="Eliminar tarea">
                            ✕
                          </button>
                        </div>
                      </div>
                    ))
                  )}
                </div>
              </>
            ) : (
              <div className="no-user-selected">
                <h2>Selecciona un usuario</h2>
                <p>Haz clic en un usuario de la lista para ver y gestionar sus tareas.</p>
              </div>
            )}
          </div>
        </div>
      </main>
    </div>
  )
}
