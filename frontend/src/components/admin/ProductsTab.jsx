import { useEffect, useState } from 'react'
import { getProducts } from '../../api/products'
import { toggleProduct } from '../../api/admin'
import { useAuth } from '../../hooks/useAuth'
import { useToast } from '../../hooks/useToast'
import { productsAdminStyles, adminLayout } from '../../styles/js/admin'

export default function ProductsTab() {
  const { token } = useAuth()
  const { addToast } = useToast()
  const [products, setProducts] = useState([])

  useEffect(() => {
    getProducts().then(data => {
      setProducts(data.slice().sort((a, b) => b.disponible - a.disponible))
    }).catch(console.error)
  }, [token])

  async function handleToggle(product) {
    try {
      const updated = await toggleProduct(token, product.id)
      setProducts(prev =>
        prev.map(p => p.id === updated.id ? updated : p)
           .sort((a, b) => b.disponible - a.disponible)
      )
      addToast(
        updated.disponible
          ? `${updated.nom} activat.`
          : `${updated.nom} desactivat.`
      )
    } catch (err) {
      addToast(err.message || 'Error en canviar l\'estat.', 'error')
    }
  }

  return (
    <div>
      <h2 className={adminLayout.sectionTitle}>Gestió de productes</h2>
      <div className={productsAdminStyles.grid}>
        {products.map(product => (
          <div
            key={product.id}
            className={product.disponible
              ? productsAdminStyles.card
              : productsAdminStyles.cardUnavailable
            }
          >
            <img
              src={`${import.meta.env.VITE_API_URL}${product.imatge}`}
              alt=""
              className={productsAdminStyles.image}
              draggable={false}
            />
            <div className={productsAdminStyles.info}>
              <span className={productsAdminStyles.name}>{product.nom}</span>
              <span className={productsAdminStyles.price}>
                {Number(product.preu).toFixed(2)}€
              </span>
            </div>
            <label className={productsAdminStyles.switch}>
              <input
                type="checkbox"
                checked={product.disponible}
                onChange={() => handleToggle(product)}
              />
              <span className={productsAdminStyles.switchSlider} />
            </label>
          </div>
        ))}
      </div>
    </div>
  )
}