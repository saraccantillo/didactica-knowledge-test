import {BrowserRouter, Route, Routes, Link} from 'react-router-dom';
import './styles/App.css';
import CreateUser from './components/createUser';
import ListUsers from './components/listUsers';
import Home from './components/home';

function App() {
  return (
    <div className="App">


      <header className="header">
        <h1 className="title">Escuela Didáctica</h1>
      </header>

      <BrowserRouter>
      <nav className="navbar">
        <ul className="nav-list">
          <li className="nav-item">
            <Link to="/" className="nav-link active">Inicio</Link>
          </li>
          <li className="nav-item">
            <Link to="/createUser" className="nav-link">Crear usuario</Link>
          </li>
          <li className="nav-item">
            <Link to="/listUsers" className="nav-link">Usuarios y tareas</Link>
          </li>
        </ul>
      </nav>

      <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/createUser" element={<CreateUser />} />
            <Route path="/listUsers" element={<ListUsers />} />
      </Routes>

      </BrowserRouter>

    </div>
  );
}

export default App;
