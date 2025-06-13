import { useState } from "react";
import axios from "axios";

function CreateUser() {

  const [inputs, setInputs] = useState({name:'', email:''})

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs(values => ({...values, [name]: value}));
  }

  const handleSubmit = async (event) => {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
    const formData = { name: inputs.name, email: inputs.email };
    
    try {
        const response = await axios.post('http://localhost/apiDidactico/users', formData, {
          headers: {
            'Content-Type': 'application/json'
          }
        });

        // Manejar la respuesta aquí
        console.log('Usuario creado:', response.data); // Muestra la respuesta de la API

        // Opcional: Reiniciar el formulario después de crear el usuario
        setInputs({ name: '', email: '' });

    } catch (error) {
        if (error.response) {
            console.error('Error al crear el usuario:', error.response.data);
        } else if (error.request) {
            console.error('No se recibió respuesta del servidor:', error.request);
        } else {
            console.error('Error al configurar la solicitud:', error.message);
        }
    }
}

    return (
        <div>
            <main className="main-content">
        <div className="content-container">
          <h2>Crear usuario</h2>
          <p>Bienvenido</p>
          <form onSubmit={handleSubmit} style={{ marginTop: "2rem", textAlign: "left" }}>
            <div style={{ marginBottom: "1rem" }}>
              <label style={{ display: "block", marginBottom: "0.5rem", fontWeight: "bold" }}>Nombre:</label>
              <input
                type="text"
                name="name"
                onChange={handleChange}
                value={inputs.name}
                style={{
                  width: "100%",
                  padding: "0.8rem",
                  border: "2px solid #ddd",
                  borderRadius: "5px",
                  fontSize: "1rem",
                }}
              />
            </div>
            <div style={{ marginBottom: "1rem" }}>
              <label style={{ display: "block", marginBottom: "0.5rem", fontWeight: "bold" }}>Email:</label>
              <input
                type="email"
                name="email"
                value={inputs.email}
                onChange={handleChange}
                style={{
                  width: "100%",
                  padding: "0.8rem",
                  border: "2px solid #ddd",
                  borderRadius: "5px",
                  fontSize: "1rem",
                }}
              />
            </div>
            <button
              type="submit"
              style={{
                backgroundColor: "#3498db",
                color: "white",
                padding: "1rem 2rem",
                border: "none",
                borderRadius: "5px",
                fontSize: "1rem",
                cursor: "pointer",
                transition: "background-color 0.3s",
              }}
            >
              Crear Usuario
            </button>
          </form>
        </div>
      </main>

        </div>
    )
}

export default CreateUser;