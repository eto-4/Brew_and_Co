const BASE_URL = import.meta.env.VITE_API_URL

export async function getProducts() {
  const res = await fetch(`${BASE_URL}/api/product`)
  if (!res.ok) throw new Error('Error carregant productes')
  return res.json()
}