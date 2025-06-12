
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


