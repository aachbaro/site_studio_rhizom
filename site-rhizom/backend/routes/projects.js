const express = require('express');
const multer = require('multer');
const db = require('../models/project');
const basicAuth = require('../middleware/basicAuth');

const upload = multer({ dest: 'uploads/' });
const router = express.Router();

router.get('/', basicAuth, (req, res) => {
  const rows = db.prepare('SELECT * FROM projects').all();
  res.json(rows);
});

router.post('/', basicAuth, upload.single('image'), (req, res) => {
  const { title, description } = req.body;
  const imagePath = req.file ? req.file.path : null;
  const info = db
    .prepare('INSERT INTO projects (title, description, imagePath) VALUES (?, ?, ?)')
    .run(title, description, imagePath);
  const project = db.prepare('SELECT * FROM projects WHERE id = ?').get(info.lastInsertRowid);
  res.json(project);
});

router.put('/:id', basicAuth, upload.single('image'), (req, res) => {
  const { id } = req.params;
  const current = db.prepare('SELECT * FROM projects WHERE id = ?').get(id);
  if (!current) return res.status(404).json({ error: 'Not found' });
  const { title = current.title, description = current.description } = req.body;
  const imagePath = req.file ? req.file.path : current.imagePath;
  db.prepare('UPDATE projects SET title = ?, description = ?, imagePath = ? WHERE id = ?')
    .run(title, description, imagePath, id);
  const project = db.prepare('SELECT * FROM projects WHERE id = ?').get(id);
  res.json(project);
});

router.delete('/:id', basicAuth, (req, res) => {
  const { id } = req.params;
  db.prepare('DELETE FROM projects WHERE id = ?').run(id);
  res.json({ success: true });
});

module.exports = router;
