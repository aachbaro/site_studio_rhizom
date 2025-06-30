# Site Rhizom

This repository contains the frontend and backend for the Rhizom project. Everything lives inside the `site-rhizom` folder with two sub directories:

```
site-rhizom/
├── backend/
│   ├── server.js
│   ├── mailer.js
│   ├── package.json
│   ├── .env.example
│   └── Dockerfile
└── frontend/
    ├── src/
    ├── public/
    ├── package.json
    ├── vite.config.js
    └── Dockerfile
```

A single `docker-compose.yml` orchestrates the two services.

## Usage

From the repository root run:

```bash
cd site-rhizom
docker compose up --build
```

The backend will be available on `http://localhost:3001` and the frontend on `http://localhost:5173`.

Environment variables for the backend should be defined in `backend/.env` (you can copy `backend/.env.example`).
