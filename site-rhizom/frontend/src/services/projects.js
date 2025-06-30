import axios from 'axios'

const BASE_URL = '/api/projects'

export function getAll(auth) {
  return axios.get(BASE_URL, { auth }).then(r => r.data)
}

export function create(formData, auth) {
  return axios.post(BASE_URL, formData, {
    auth,
    headers: { 'Content-Type': 'multipart/form-data' }
  }).then(r => r.data)
}

export function update(id, formData, auth) {
  return axios.put(`${BASE_URL}/${id}`, formData, {
    auth,
    headers: { 'Content-Type': 'multipart/form-data' }
  }).then(r => r.data)
}

export function remove(id, auth) {
  return axios.delete(`${BASE_URL}/${id}`, { auth }).then(r => r.data)
}
