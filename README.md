
# didactica-knowledge-test

Prueba de Conocimientos para Escuela Didáctica

## Parte 1 PHP (Backend) - API REST

### Estructura de la API

La API está organizada en varias carpetas, cada una con una función específica:

- **config/**: Contiene archivos de configuración que definen parámetros globales y ajustes de la aplicación.
- **controllers/**: Aquí se encuentran los controladores que manejan la lógica de negocio y las interacciones con los modelos.
- **models/**: Esta carpeta alberga los modelos que representan la estructura de los datos y las interacciones con la base de datos.
- **routes/**: Define las rutas de la API, mapeando las solicitudes HTTP a los controladores correspondientes.
- **utils/**: Contiene funciones utilitarias que son utilizadas en diferentes partes de la aplicación.
- **index.php**: El punto de entrada de la aplicación, donde se inicializa la configuración y se manejan las solicitudes.

### Funcionamiento

La API sigue el patrón MVC, lo que permite una separación de responsabilidades. Las solicitudes entrantes son gestionadas por el archivo `index.php`, que dirige las peticiones a las rutas definidas en la carpeta `routes/`. Cada ruta está asociada a un controlador en `controllers/`, que a su vez interactúa con los modelos en `models/` para acceder y manipular los datos.

La API fue lanzada utilizando XAMPP, la carpeta se colocó dentro de la carpeta `htdocs` de la siguiente forma `/C:/xampp/htdocs/apiDidactico` se iniciaron los servicios de Apache y MySQL, con la base de datos ya creada y algunos registros ingresados. Algunos de los endpoints válidos son:

-  GET `apiDidactico/users` recupera la lista de todos los usuarios
- GET `apiDidactico/tasks` recupera la lista de todas las tareas
- GET `apiDidactico/users/{id}` recupera un usuario en específico por su ID
- GET `apiDidactico/users/{id}/tasks` recupera las tareas específicas de un usuario
- POST `apiDidactico/users` crea un nuevo usuario en la base de datos

### Algunas decisiones técnicas

- **Uso de MVC**: Facilita la mantenibilidad y escalabilidad del código.
- **Estructura modular**: Cada componente de la API está separado, lo que permite un desarrollo más ágil.
- **Configuración centralizada**: La configuración se maneja en un solo lugar, lo que simplifica la gestión de parámetros y ajustes.

Esta estructura y enfoque permiten un desarrollo eficiente y una comprensión fácil del código, que es importante para trabajar en equipo y la colaboración.

### ¿Como se podría mejorar el rendimiento de la API utilizado caché o índices en la base de datos?

Sin índices, una base de datos tendría que realizar busquedas completas en una tabla para poder encontrar filas que coincidan con la consulta ingresada, utilizando índices estas consultas podrían ser mejoradas sobretodo cuando hacen uso de clausulas como `WHERE`, por ejemplo si se planea filtrar tareas cuyo estado en `completed`  sea verdadero, esta columna sería frecuentemente utilizada en las condiciones de búsqueda, con los índices la base de datos podra acceder más rapidamente.

## Parte 2: React (Frontend) - Aplicación de Lista de Tareas

### Estructura del Proyecto 

Brevemente, la estructura del proyecto desarrollado en React es la siguiente

didacticatest/
│
├── public/  Archivos estáticos
├── src/ Código fuente
│ ├── components/  -> Componentes de la aplicación
│ │ ├── CreateUser.js -> Componente para crear nuevos usuarios
│ │ └── ListUsers.js -> Componente para listar usuarios y sus tareas
│ └── styles/ -> Archivos CSS para estilos
├── package.json -> Dependencias y scripts del proyecto
├── package-lock.json -> Versión exacta de las dependencias instaladas
├── README.md -> Documentación del proyecto
└── .gitignore

### Funcionamiento 

La aplicación cuenta con una página principal, donde a traves de un navegador, se podra acceder a las correspondientes funcionalidades: crear un usuario, visualizar a los usuarios existentes, seleccionar un usuario y mostrar sus tareas, permitir agregar nuevas tareas, marcar tareas como completadas, filtrar tareas y eliminarlas.

Para iniciar la ejecución solo es necesario ejecutar en la consola:

`npm start`

La aplicación se comunica con la apiDidactico para poder obtener y manipular los datos a traves de los endpoints mencionados anteriormente.

![image](https://github.com/user-attachments/assets/51104181-3588-4b8d-bb0e-25247800f8f3)

![image](https://github.com/user-attachments/assets/f925fa07-de6e-4225-8079-054b023af2e3)

![image](https://github.com/user-attachments/assets/7f8cf229-a3a2-4410-b4f9-71fbb8de2ed7)

![image](https://github.com/user-attachments/assets/2f91ca9b-203c-49bf-a97b-8f8bffd05ba3)

## Parte 3: SǪL (Base de Datos) - Optimización y Consultas

La consulta SQL optimizada para obtener todas las tareas pendientes de un usuario es la siguiente.

```sql
SELECT t.id, t.title, t.created_at
FROM tasks t
WHERE t.user_id = ?
AND t.completed = 0 
ORDER BY t.created_at DESC;
```
Con un un indíce compuesto como por ejemplo:

```sql
CREATE INDEX idx_tasks_user_completed_created 
ON tasks (user_id, completed, created_at DESC);
```

La base de datos puede localizar directamente las filas del usuario específico, filtrando solo las tareas pendientes y ordenando los resultados sin la necesidad de tener que examinar todas las filas de la tabla.

## Parte 4 JavaScript (Lógica Frontend)

Este código ubicado en la carpeta ` javaScriptArray4` contiene una función llamada `filterTasks`, como se solicita, esta función acepta dos parámetros `tasks` un array de objetos que representan tareas y `filter` una cadena de texto que puede tomar los valores *all*, *completed* o *pending*.

En primer lugar, se valida que `tasks` sea un array y que `filter` sea una cadena, si no, advierte del error. De ser necesario se convierte la cadena de texto a minúsculas.

A continuación se utiliza un `switch` para devolver todas las tareas si el filtro es *all*, solo las tareas completas si el filtro es *completed* y las tareas pendientes si el filtro es *pending*.

Se añadieron varios ejemplos para hacer llamados a `filterTasks` para mostrar cómo se filtran las tareas según el criterio enviado. Además se incluyo un bloque `try-catch ` para capturar y mostrar errores si se pasa un filtro inválido.

## Parte 5 HTML y CSS (Interfaz de Usuario)

Para el desarollo del login se optó por usar un diseño que transmitiera flexibilidad y modernidad, con un fondo degradado y un esquema de colores que corresponden a los de Escuela Didáctica. Los elementos están diseñados para tener un enfoque de usabilidad, se evidencia en el uso de bordes y sombras en los inputs del formulario. Se incluyen efectos de hover en el botón de envío para darle un toque adicional, además de reglas en el css para el diseño responsive.

![image](https://github.com/user-attachments/assets/65d38f08-3a00-4475-9d29-f23d6646ab2b) ![image](https://github.com/user-attachments/assets/f046ad05-d170-47d7-bb0e-c55c1a8dc19e)

## Parte 6 Motores de Inteligencia Artificial (Innovación)

TensorFlow es el resultado de DistBelief, un sistema de aprendizaje automático desarrollado por Google y que fue reconstruido para ser más rápido y robusto. TensorFlow permite crear e implementar modelos de aprendizaje automático de una forma relativamente sencilla, mediante el uso de APIs. 

En mi experiencia, la inteligencia artificial suele percibirse como un obstáculo para el aprendizaje, ya que se piensa de ella como un remplazo a los procesos de formación, por ejemplo, la prueba y error, los estudiantes ya no se interesan por equivocarse y aprender sino por obtener la respuesta correcta desde el primer momento.

Así las cosas, propongo que una plataforma educativa que haga uso de la IA debe fomentar el aprendizaje reflexivo. En lugar de mostrar cual es la respuesta correcta, sin dar espacio al porque de un error, la IA deberia analizar los intentos de la persona y ayudar a ofrecer pistas o formular preguntas que ayuden a encontrar la solución por cuenta propia.

Por ejemplo, en un entorno de aprendizaje de matemáticas, si un estudiante comete un error al resolver ecuaciones, el sistema no señalaria simplemente el error (como despejes mal hechos o signos incorrectos) sino que aprendería a analizar los errores cometidos y que principios de álgebra son necesarios para corregir dichos errores.

De esta forma la IA se convertiria en un mentor personalizado, que ayude al proceso de aprendizaje en vez de remplazar el esfuerzo con respuestas inmediatas.

## Parte 7: Moodle y Error en Plugin Estado de Finalización

### 1. Instalación de Moodle 4.5

El link que se encuentra en la prueba no estaba disponible, por lo que se descargo Moodle 4.5.5 desde la página oficial de Moodle. Se actualizo XAMPP ya que esta versión de Moodle requeria de una versión PHP 8.1 y MariaDB 10.6.7.  En primer lugar se actualizo la versión de MariaDB a la versión necesaria por Moodle 4.5.5. 

![image](https://github.com/user-attachments/assets/5b077a34-0067-464f-8863-39ff234d12ac)

Despues en XAMPP se añadio el archivo .zip descomprimido de Moodle a la carpeta htdocs, a traves de `localhost/moodle` accedemos para comenzar con la instalación.

![image](https://github.com/user-attachments/assets/cd0b6d08-47a5-4be1-be84-9423d98737b1)

Se escoge como database driver MariaDB, además de crear la base de datos en phpMyAdmin con el nombre moodleDB.

![image](https://github.com/user-attachments/assets/5b27550a-4627-4770-99fd-4dbd7d97a70e)
![image](https://github.com/user-attachments/assets/66c3842f-3ad7-44cb-a671-09e8513090af)

A continuación, se necesito corregir los errores para continuar con la instalación el primero fue `max_input_vars` cuyo valor se cambio a 5000, descomentar `extension=gd`, añadir la `extension=php_intl.dll`, se activo la `extension=sodium`, agregar la extensión `zend_extension=php_opcache.dll` y activar la extension `extension=soap`.

![image](https://github.com/user-attachments/assets/ddf81146-f421-4e2c-8e92-c8c34fc19370)
![image](https://github.com/user-attachments/assets/c681f3bf-9a47-46aa-a1ef-72ff238b7b4c)

Se configura el perfil de administrador.

![image](https://github.com/user-attachments/assets/45ac5209-c7a7-48d0-9ec9-7dbfdeedeeb9)

Esta la aplicación lista para instalar el plugin.

![image](https://github.com/user-attachments/assets/e064e0ee-fa50-4980-a727-87301e0e1b0c)






