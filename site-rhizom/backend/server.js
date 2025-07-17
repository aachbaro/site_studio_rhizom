const express = require("express");
const bodyParser = require("body-parser");
const { sendMail } = require("./mailer");
const db = require("./db/database");
require("dotenv").config();

const app = express();
const PORT = process.env.PORT || 3001;
const cors = require("cors");
app.use(cors());

app.use(bodyParser.json());

const multer = require("multer");

// Dossier de destination pour les images (ajuste selon ta structure)
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, "./public/images/");
  },
  filename: function (req, file, cb) {
    // Garde le nom d'origine, ou adapte si tu veux éviter les collisions
    cb(null, Date.now() + "-" + file.originalname);
  },
});

const upload = multer({ storage: storage });

// ----- ROUTE EMAIL CONTACT -----
app.post("/contact", async (req, res) => {
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

app.use("/images", express.static(__dirname + "/public/images"));

// ----- ROUTE API CAROUSEL (BDD) -----
app.get("/api/carousel", (req, res) => {
  try {
    const images = db.prepare("SELECT * FROM carousel_images").all();
    res.json(images);
  } catch (err) {
    res.status(500).json({ error: "Failed to fetch carousel images" });
  }
});

app.post("/api/carousel", upload.single("image"), (req, res) => {
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
});

const fs = require("fs");
app.delete("/api/carousel/:id", (req, res) => {
  const id = req.params.id;
  try {
    const img = db.prepare("SELECT url FROM carousel_images WHERE id = ?").get(id);
    if (!img) return res.status(404).json({ error: "Image non trouvée" });

    // Supprime le fichier physique
    const path = __dirname + "/public" + img.url;
    if (fs.existsSync(path)) fs.unlinkSync(path);

    // Supprime la ligne BDD
    db.prepare("DELETE FROM carousel_images WHERE id = ?").run(id);

    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: "Erreur lors de la suppression" });
  }
});

// GET : liste tous les projets
app.get("/api/projects", (req, res) => {
  try {
    const projects = db.prepare("SELECT * FROM projects").all();
    res.json(projects);
  } catch (err) {
    res.status(500).json({ error: "Failed to fetch projects" });
  }
});

app.post("/api/projects", upload.single("image"), (req, res) => {
  const { title } = req.body;
  if (!req.file || !title) {
    return res.status(400).json({ error: "Image et titre obligatoires." });
  }
  const url = `/images/${req.file.filename}`;
  try {
    const stmt = db.prepare("INSERT INTO projects (url, title) VALUES (?, ?)");
    const info = stmt.run(url, title);
    res.status(201).json({ id: info.lastInsertRowid, url, title });
  } catch (err) {
    res.status(500).json({ error: "Failed to insert project" });
  }
});

// ----- Ajoute ici tes futures routes API (projects, upload, admin, etc.) -----

// ----- SERVER START -----
app.listen(PORT, "0.0.0.0", () => {
  console.log(`Server listening on port ${PORT}`);
});
