const express = require('express');
const bodyParser = require('body-parser');
const { sendMail } = require('./mailer');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3001;

app.use(bodyParser.json());

app.post('/contact', async (req, res) => {
  const { name, email, message } = req.body;
  if (!name || !email || !message) {
    return res.status(400).json({ error: 'Missing name, email or message' });
  }
  try {
    await sendMail(name, email, message);
    res.status(200).json({ success: true });
  } catch (err) {
    console.error('Error sending mail:', err);
    res.status(500).json({ error: 'Failed to send email' });
  }
});

app.listen(PORT, () => {
  console.log(`Server listening on port ${PORT}`);
});

