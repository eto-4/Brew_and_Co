# ☕ Brew & Co. — Frontend

Frontend de la aplicación web **Brew & Co.**, una plataforma digital para una cafetería moderna donde los usuarios pueden consultar productos, realizar pedidos y hacer seguimiento en tiempo real.

---

## 🚀 Tecnologías utilizadas

* **React** → Librería principal de frontend
* **Vite** → Entorno de desarrollo y build
* **Tailwind CSS** → Estilado rápido y responsive
* **React Router DOM** → Navegación entre páginas
* **Fetch API / Axios (opcional)** → Comunicación con backend
* **Laravel (backend)** → API REST (separado)

---

## 📁 Estructura del proyecto

```
src/
├── api/           # Comunicación con backend
├── components/    # Componentes reutilizables
├── pages/         # Vistas principales (Home, Catalog, etc.)
├── layouts/       # Estructura de páginas
├── context/       # Estado global (auth, carrito...)
├── hooks/         # Hooks personalizados
├── router/        # Configuración de rutas
├── styles/        # Estilos (ui.js + animaciones)
│   ├── ui.js
│   ├── animations.css
├── App.jsx
├── main.jsx
```

---

## 🎨 Sistema de estilos

El proyecto utiliza un enfoque híbrido:

* **Tailwind CSS** → Layout y estilos base
* **Mapa de estilos (`ui.js`)** → Reutilización y limpieza del JSX
* **CSS global** → Animaciones (`animations.css`)

Ejemplo:

```jsx
className={`${ui.button.base} ${ui.button.primary}`}
```

---

## 🔌 Comunicación con backend

El frontend consume una API REST desarrollada en Laravel.

Ejemplo de petición:

```js
fetch("http://localhost:8000/api/productes")
```

Las respuestas se manejan en formato JSON.

---

## ⚙️ Instalación

1. Clonar el repositorio:

```bash
git clone <repo-url>
cd frontend
```

2. Instalar dependencias:

```bash
npm install
```

3. Ejecutar en desarrollo:

```bash
npm run dev
```

---

## 🌐 Variables de entorno

Crear un archivo `.env` si es necesario:

```
VITE_API_URL=http://localhost:8000/api
```

---

## 🧩 Funcionalidades principales

* Página principal pública
* Autenticación (login / registro)
* Catálogo de productos
* Carrito de compra
* Creación de pedidos
* Seguimiento en tiempo real
* Panel de administración

---

## 🧠 Arquitectura

* **Frontend desacoplado** del backend
* Comunicación mediante **API REST**
* Separación clara de responsabilidades:

  * Frontend → UI, estado, navegación
  * Backend → lógica, base de datos, seguridad

---

## 📦 Build para producción

```bash
npm run build
```

Genera la carpeta `dist/` lista para deploy.

---

## 🐳 Docker (opcional)

El proyecto está preparado para integrarse en un entorno Docker junto al backend Laravel.

---

## 👨‍💻 Autor

Proyecto desarrollado como trabajo final de curso.

---

## 📌 Notas

* No incluir `node_modules` en la entrega
* El proyecto debe ser ejecutable con:

  ```
  npm install
  npm run dev
  ```
