import axios from 'axios';

export function sendContact({ name, email, message }) {
  return axios.post('/api/contact', { name, email, message });
}
