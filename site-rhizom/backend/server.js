const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");
const multer = require("multer");
const fs = require("fs");
const { sendMail } = require("./mailer");
const db = require("./db/database");
const bcrypt = require("bcrypt");
const jwt = require("jsonwebtoken");
require("dotenv").config();

const app = express();
const PORT = process.env.PORT || 3001;

// --- CONFIG GLOBAL ---
app.use(cors());
app.use(bodyParser.json());

// --- CONSTANTES SÉCURITÉ ADMIN ---
const ADMIN_PASSWORD_HASH = process.env.ADMIN_PASSWORD_HASH;
const JWT_SECRET = process.env.JWT_SECRET;

// --- MULTER : UPLOAD D'IMAGES ---
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, "./public/images/");
  },
  filename: function (req, file, cb) {
    cb(null, Date.now() + "-" + file.originalname);
  },
});
const upload = multer({ storage: storage });

// --- MIDDLEWARE AUTH ADMIN ---
function requireAdminAuth(req, res, next) {
  const authHeader = req.headers.authorization;
  if (!authHeader || !authHeader.startsWith("Bearer ")) {
    return res.status(401).json({ error: "Accès admin non autorisé" });
  }
  const token = authHeader.split(" ")[1];
  try {
    const payload = jwt.verify(token, JWT_SECRET);
    if (!payload.admin) throw new Error("Not admin");
    next();
  } catch (err) {
    return res.status(403).json({ error: "Token invalide ou expiré" });
  }
}

// --- ROUTES PUBLIQUES ---

// Contact
app.post("/api/contact", async (req, res) => {
  const { name, email, message } = req.body;
  if (!name || !email || !message) {
    return res.status(400).json({ error: "Missing name, email or message" });
  }
  try {
    await sendMail(name, email, message);
    res.status(200).json({ success: true });
  } catch (err) {
    console.error("Error sending mail:", err);
    res.status(500).json({ error: "Failed to send email" });
  }
});

// Images statiques (public)
app.use("/images", express.static(__dirname + "/public/images"));

// Carousel - GET (public)
app.get("/api/carousel", (req, res) => {
  try {
    const images = db.prepare("SELECT * FROM carousel_images").all();
    res.json(images);
  } catch (err) {
    res.status(500).json({ error: "Failed to fetch carousel images" });
  }
});

// Projects - GET (public)
app.get("/api/projects", (req, res) => {
  try {
    const projects = db.prepare("SELECT * FROM projects").all();
    res.json(projects);
  } catch (err) {
    res.status(500).json({ error: "Failed to fetch projects" });
  }
});

// --- ROUTE LOGIN ADMIN ---
app.post("/api/admin/login", async (req, res) => {
  const { password } = req.body;
  if (!password) {
    return res.status(400).json({ error: "Mot de passe requis" });
  }
  const match = await bcrypt.compare(password, ADMIN_PASSWORD_HASH);
  if (!match) {
    return res.status(401).json({ error: "Mot de passe incorrect" });
  }
  const token = jwt.sign({ admin: true }, JWT_SECRET, { expiresIn: "24h" });
  res.json({ token });
});

// --- ROUTES ADMIN SÉCURISÉES ---

// Carousel - POST (upload) [Admin]
app.post(
  "/api/carousel",
  requireAdminAuth,
  upload.single("image"),
  (req, res) => {
    const { title } = req.body;
    if (!req.file || !title) {
      return res.status(400).json({ error: "Image et titre obligatoires." });
    }
    const url = `/images/${req.file.filename}`;
    try {
      const stmt = db.prepare(
        "INSERT INTO carousel_images (url, title) VALUES (?, ?)"
      );
      const info = stmt.run(url, title);
      res.status(201).json({ id: info.lastInsertRowid, url, title });
    } catch (err) {
      res.status(500).json({ error: "Failed to insert image" });
    }
  }
);

// Carousel - DELETE [Admin]
app.delete("/api/carousel/:id", requireAdminAuth, (req, res) => {
  const id = req.params.id;
  try {
    const img = db
      .prepare("SELECT url FROM carousel_images WHERE id = ?")
      .get(id);
    if (!img) return res.status(404).json({ error: "Image non trouvée" });
    const path = __dirname + "/public" + img.url;
    if (fs.existsSync(path)) fs.unlinkSync(path);
    db.prepare("DELETE FROM carousel_images WHERE id = ?").run(id);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: "Erreur lors de la suppression" });
  }
});

// Projects - POST (upload) [Admin]
app.post(
  "/api/projects",
  requireAdminAuth,
  upload.single("image"),
  (req, res) => {
    const { title } = req.body;
    if (!req.file || !title) {
      return res.status(400).json({ error: "Image et titre obligatoires." });
    }
    const url = `/images/${req.file.filename}`;
    try {
      const stmt = db.prepare(
        "INSERT INTO projects (url, title) VALUES (?, ?)"
      );
      const info = stmt.run(url, title);
      res.status(201).json({ id: info.lastInsertRowid, url, title });
    } catch (err) {
      res.status(500).json({ error: "Failed to insert project" });
    }
  }
);

// Projects - DELETE [Admin]
app.delete("/api/projects/:id", requireAdminAuth, (req, res) => {
  const id = req.params.id;
  try {
    const proj = db.prepare("SELECT url FROM projects WHERE id = ?").get(id);
    if (!proj) return res.status(404).json({ error: "Projet non trouvé" });
    const path = __dirname + "/public" + proj.url;
    if (fs.existsSync(path)) fs.unlinkSync(path);
    db.prepare("DELETE FROM projects WHERE id = ?").run(id);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: "Erreur lors de la suppression du projet" });
  }
});

console.log("Hash depuis .env :", ADMIN_PASSWORD_HASH);
bcrypt
  .compare("TON_MOT_DE_PASSE_ADMIN", ADMIN_PASSWORD_HASH)
  .then((result) => console.log("bcrypt compare:", result));

// --- Lancer le serveur ---
app.listen(PORT, "0.0.0.0", () => {
  console.log(`Server listening on port ${PORT}`);
});
