const BASE_URL = import.meta.env.VITE_API_URL

export async function processPayment(token, orderId, payload) {
  const res = await fetch(`${BASE_URL}/api/payments/${orderId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify(payload),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function validateDiscount(token, codi) {
  const res = await fetch(`${BASE_URL}/api/discounts/validate`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ codi }),
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export async function getPaymentPrefill(token) {
  const res = await fetch(`${BASE_URL}/api/admin/payment-prefill`, {
    headers: { 
      Authorization: `Bearer ${token}`,
      'Accept': 'application/json'
    },
  })
  const data = await res.json()
  if (!res.ok) throw data
  return data
}