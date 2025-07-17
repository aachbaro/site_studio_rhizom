// backend/db/database.js
const Database = require('better-sqlite3');
const db = new Database('./data/data.sqlite');
// Création des tables si elles n'existent pas
db.prepare(`
    CREATE TABLE IF NOT EXISTS carousel_images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        url TEXT NOT NULL,
        title TEXT
    )
`).run();

db.prepare(`
    CREATE TABLE IF NOT EXISTS projects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        url TEXT NOT NULL,
        title TEXT
    )
`).run();

module.exports = db;