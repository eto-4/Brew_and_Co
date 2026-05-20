const BASE_URL = import.meta.env.VITE_API_URL

export async function getProfile(token) {
  const res = await fetch(`${BASE_URL}/api/user/profile`, {
    headers: { 
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json' 
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function updateProfile(token, fields) {
  const res = await fetch(`${BASE_URL}/api/user/profile`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify(fields),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function updatePassword(token, fields) {
  const res = await fetch(`${BASE_URL}/api/user/password`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify(fields),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function getAddresses(token) {
  const res = await fetch(`${BASE_URL}/api/user/addresses`, {
    headers: { 
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function createAddress(token, fields) {
  const res = await fetch(`${BASE_URL}/api/user/addresses`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify(fields),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function updateAddress(token, id, fields) {
  const res = await fetch(`${BASE_URL}/api/user/addresses/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify(fields),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function deleteAddress(token, id) {
  const res = await fetch(`${BASE_URL}/api/user/addresses/${id}`, {
    method: 'DELETE',
    headers: { 
      Authorization: `Bearer ${token}`, 
      'Accept': 'application/json'
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function setDefaultAddress(token, id) {
  const res = await fetch(`${BASE_URL}/api/user/addresses/${id}/predeterminada`, {
    method: 'PATCH',
    headers: { 
      Authorization: `Bearer ${token}`, 
      'Accept': 'application/json'
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}