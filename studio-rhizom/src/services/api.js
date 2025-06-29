export async function sendContact({ name, email, objet, message }) {
  const response = await fetch('/api/contact', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ name, email, objet, message })
  });
  if (!response.ok) {
    const text = await response.text();
    throw new Error(text || 'Erreur lors de l\'envoi');
  }
  return response.json();
}
