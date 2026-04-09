# ☕ Brew & Co. — Frontend

Frontend de l'aplicació web **Brew & Co.**, una plataforma digital per a una cafeteria moderna on els usuaris poden consultar productes, fer comandes i fer el seguiment en temps real.

---

## 🚀 Tecnologies utilitzades

* **React** → Llibreria principal de frontend
* **Vite** → Entorn de desenvolupament i build
* **Tailwind CSS** → Estilització ràpida i responsive
* **React Router DOM** → Navegació entre pàgines
* **Fetch API / Axios (opcional)** → Comunicació amb backend
* **Laravel (backend)** → API REST (separat)

---

## 📁 Estructura del projecte

```
src/
├── api/              # Comunicació amb backend
├── components/       # Components reutilitzables
├── pages/            # Vistes principals (Home, Catalog, etc.)
├── layouts/          # Estructura de pàgines
├── context/          # Estat global (auth, carretó...)
├── hooks/            # Hooks personalitzats
├── router/           # Configuració de rutes
├── styles/           # Estils (ui.js + animacions)
│ ├── ui.js
│ ├── animations.css
├── App.jsx
├── main.jsx
```

---

## 🎨 Sistema d'estils

El projecte utilitza un enfocament híbrid:

* **Tailwind CSS** → Maquetació i estils base
* **Mapa d'estils (`ui.js`)** → Reutilització i neteja del JSX
* **CSS global** → Animacions (`animations.css`)

Exemple:

```jsx
className={`${ui.button.base} ${ui.button.primary}`}
```

---

## 🔌 Comunicació amb backend

El frontend fa servir una API REST desenvolupada amb Laravel.

Exemple de petició:

```js
fetch("http://localhost:8000/api/productes")
```

Les respostes es gestionen en format JSON.

---

## ⚙️ Instal·lació

1. Clonar el repositori:

```bash
git clone <repo-url>
cd frontend
```

2. Instal·lar dependències:

```bash
npm install
```

3. Executar en desenvolupament:

```bash
npm run dev
```

---

## 🌐 Variables d'entorn

Crear un fitxer `.env` si és necessari:

```
VITE_API_URL=http://localhost:8000/api
```

---

## 🧩 Funcionalitats principals

* Pàgina principal pública
* Autenticació (inici de sessió / registre)
* Catàleg de productes
* Carretó de compra
* Creació de comandes
* Seguiment en temps real
* Panell d'administració

---

## 🧠 Arquitectura

* **Frontend desacoblat** del backend
* Comunicació mitjançant **API REST**
* Separació clara de responsabilitats:

  * Frontend → UI, estat, navegació
  * Backend → lògica, base de dades, seguretat

---

## 📦 Build per a producció

```bash
npm run build
```

Genera la carpeta `dist/` preparada per a desplegament.