import { useEffect, useState } from 'react'
import { getProducts } from '../../api/products'
import { productGrid } from '../../styles/js/home'
import { Link } from 'react-router-dom'

export default function ProductGrid({ user }) {
  const [products, setProducts] = useState([])

  useEffect(() => {
    getProducts().then(setProducts).catch(console.error)
  }, [])

  return (
    <section className={productGrid.section}>
      <div className={productGrid.title_container}>
        <h2 className={productGrid.title}>Inventari a la teva disposició</h2>
      </div>
      <div className={productGrid.grid}>
        {products.map(product => (
          <div key={product.id} className={productGrid.card}>
            <img
              src={`${import.meta.env.VITE_API_URL}${product.imatge}`}
              alt=""
              draggable={false}
              className={productGrid.image}
            />
          </div>
        ))}
      </div>
      <div className={productGrid.ctaWrap}>
        <Link to={user ? '/orders' : '/register'} className={productGrid.cta}>
          {user ? 'Fer una nova comanda' : 'Fer la meva primera comanda'}
        </Link>
      </div>
    </section>
  )
}