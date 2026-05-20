const BASE_URL = import.meta.env.VITE_API_URL

export async function getDashboard(token) {
  const res = await fetch(`${BASE_URL}/api/admin/dashboard`, {
    headers: { 
        Authorization: `Bearer ${token}`,
      'Accept': 'application/json' 
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function toggleProduct(token, id) {
  const res = await fetch(`${BASE_URL}/api/admin/products/${id}/toggle`, {
    method: 'PUT',
    headers: { 
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json' 
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function sendAiMessage(token, message) {
  const res = await fetch(`${BASE_URL}/api/admin/ai/chat`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ message }),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}