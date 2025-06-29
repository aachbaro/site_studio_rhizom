# Rhizom Backend

This Express server exposes a single `/contact` endpoint used by the Vue.js frontend.
It sends an email using SMTP credentials defined in the `.env` file.

## Setup

```bash
cp .env.example .env
npm install
npm start
```

## Endpoint

`POST /contact`

Send a JSON body with:

```json
{
  "name": "Your Name",
  "email": "you@example.com",
  "message": "Hello"
}
```

