/**
 * 
 * Filtrar un array de tareas según el criterio
 * @param {Array} tasks - Array de objetos tarea con propiedades id, title, completed
 * @param {string} filter - Criterio de filtrado: "all", "completed", "pending"
 * @returns {Array} Array filtrado de tareas
 * 
 */
function filterTasks(tasks, filter) {
    // Validar que la entrada sea un array
    if (!Array.isArray(tasks)) {
        throw new Error('El primer parametro debe ser un array');
    }
    
    if (typeof filter !== 'string') {
        throw new Error('El filtro debe ser una cadena de texto');
    }
    
    // Cambiar el filtro a minúsculas
    const normalizedFilter = filter.toLowerCase();
    
    switch (normalizedFilter) {
        case 'all':
            return tasks;
        case 'completed':
            return tasks.filter(task => task.completed === true);
        case 'pending':
            return tasks.filter(task => task.completed === false);
        default:
            throw new Error('Filtro no válido. Use: "all", "completed", o "pending"');
    }
}

// Array de ejemplo
const tasks = [
    { id: 1, title: "Task 1", completed: true },
    { id: 2, title: "Task 2", completed: false },
    { id: 3, title: "Task 3", completed: true },
    { id: 4, title: "Task 4", completed: false }
];

console.log('Todas las tareas:', filterTasks(tasks, "all"));
console.log('Tareas completadas:', filterTasks(tasks, "completed"));
console.log('Tareas pendientes:', filterTasks(tasks, "pending"));

// Pruebas adicionales
console.log('\n--- Pruebas ---');
console.log('Filtro "PENDING" (mayúsculas):', filterTasks(tasks, "PENDING"));
console.log('Filtro "COMPLETED" (mayúsculas):', filterTasks(tasks, "COMPLETED"));
console.log('Array vacio:', filterTasks([], "completed"));

// Manejo de errores
try {
    filterTasks(tasks, "invalid");
} catch (error) {
    console.log('Error capturado:', error.message);
}