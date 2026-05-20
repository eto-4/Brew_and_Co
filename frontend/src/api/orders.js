const BASE_URL = import.meta.env.VITE_API_URL

export async function getOrders(token) {
  const res = await fetch(`${BASE_URL}/api/orders`, {
    headers: { 
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function getOrder(token, id) {
  const res = await fetch(`${BASE_URL}/api/orders/${id}`, {
    headers: { 
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'   
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function createOrder(token, orderLines) {
  const res = await fetch(`${BASE_URL}/api/orders`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ order_lines: orderLines }),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function updateOrder(token, id, orderLines) {
  const res = await fetch(`${BASE_URL}/api/orders/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ order_lines: orderLines }),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}


export async function cancelOrder(token, id) {
  const res = await fetch(`${BASE_URL}/api/orders/${id}`, {
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