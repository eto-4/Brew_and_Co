const BASE_URL = import.meta.env.VITE_API_URL

export async function login({ email, password }) {
  const res = await fetch(`${BASE_URL}/api/login`, {
    method: 'POST',
    headers: { 
      'Content-Type': 'application/json', 
      'Accept': 'application/json' 
    },
    body: JSON.stringify({ email, password }),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function register({ nom, email, password, password_confirmation }) {
  const res = await fetch(`${BASE_URL}/api/register`, {
    method: 'POST',
    headers: { 
      'Content-Type': 'application/json', 
      'Accept': 'application/json' 
    },
    body: JSON.stringify({ nom, email, password, password_confirmation }),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}