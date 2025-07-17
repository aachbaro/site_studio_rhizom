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

// ----- ROUTE API CAROUSEL (BDD) -----
app.get("/api/carousel", (req, res) => {
  try {
    const images = db.prepare("SELECT * FROM carousel_images").all();
    res.json(images);
  } catch (err) {
    res.status(500).json({ error: "Failed to fetch carousel images" });
  }
});

app.post("/api/carousel", (req, res) => {
  const { url, title } = req.body;
  if (!url || !title) {
    return res.status(400).json({ error: "Missing url or title" });
  }
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

// ----- Ajoute ici tes futures routes API (projects, upload, admin, etc.) -----

// ----- SERVER START -----
app.listen(PORT, () => {
  console.log(`Server listening on port ${PORT}`);
});
