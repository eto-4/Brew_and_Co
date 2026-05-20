import { useState, useEffect, useMemo } from 'react'
import { X } from 'lucide-react'

import { getProducts } from '../../api/products'
import { createOrder, updateOrder, cancelOrder } from '../../api/orders'

import { useAuth } from '../../hooks/useAuth'
import { useToast } from '../../hooks/useToast'

import { orderModalStyles } from '../../styles/js/orders'

import ProductCard from './ProductCard'
import OrderSummary from './OrderSummary'
import ConfirmModal from '../ui/ConfirmModal'

export default function OrderModal({ order, onClose, onSaved }) {
  const { token } = useAuth()
  const { addToast } = useToast()

  const isEditing = !!order

  const [products, setProducts] = useState([])
  const [quantities, setQuantities] = useState({})
  const [loading, setLoading] = useState(false)
  const [loadingProducts, setLoadingProducts] = useState(true)
  const [confirmDelete, setConfirmDelete] = useState(false)

  useEffect(() => {
    let active = true

    async function loadProducts() {
      setLoadingProducts(true)

      try {
        const data = await getProducts()

        if (!active) return

        setProducts(data)

        if (order) {
          const initial = {}

          order.order_lines.forEach(line => {
            initial[line.producte_id] = line.quantitat
          })

          setQuantities(initial)
        } else {
          setQuantities({})
        }
      } catch (err) {
        if (!active) return

        console.error(err)

        addToast(
          err?.message || 'Error carregant els productes.', 'error'
        )
      } finally {
        if (active) {
          setLoadingProducts(false)
        }
      }
    }

    loadProducts()

    return () => {
      active = false
    }
  }, [order, addToast])

  function handleAdd(productId) {
    setQuantities(prev => ({
      ...prev,
      [productId]: (prev[productId] || 0) + 1,
    }))
  }

  function handleRemove(productId) {
    setQuantities(prev => {
      const current = prev[productId] || 0

      if (current <= 0) return prev

      const updated = {
        ...prev,
        [productId]: current - 1,
      }

      if (updated[productId] === 0) {
        delete updated[productId]
      }

      return updated
    })
  }

  const orderLines = useMemo(() => {
    return Object.entries(quantities)
      .filter(([, qty]) => qty > 0)
      .map(([id, qty]) => ({
        producte_id: parseInt(id, 10),
        quantitat: qty,
      }))
  }, [quantities])

  const byCategory = useMemo(() => {
    const grouped = products.reduce((acc, product) => {
      const category = product.categoria || 'Altres'

      if (!acc[category]) {
        acc[category] = []
      }

      acc[category].push(product)

      return acc
    }, {})

    Object.keys(grouped).forEach(category => {
      grouped[category].sort(
        (a, b) => b.disponible - a.disponible
      )
    })

    return grouped
  }, [products])

  async function handleSave() {
    if (orderLines.length === 0) {
      addToast('Afegeix almenys un producte.')
      return
    }

    setLoading(true)

    try {
      if (isEditing) {
        await updateOrder(token, order.id, orderLines)

        addToast('Ordre actualitzada correctament.')
      } else {
        await createOrder(token, orderLines)

        addToast('Ordre creada correctament.')
      }

      onSaved()
      onClose()
    } catch (err) {
      console.error(err)

      addToast(
        err?.message || 'Error guardant l’ordre.', 'error', 'error'
      )
    } finally {
      setLoading(false)
    }
  }

  async function handleDelete() {
    setLoading(true)

    try {
      await cancelOrder(token, order.id)

      addToast('Ordre eliminada correctament.')

      onSaved()
      onClose()
    } catch (err) {
      console.error(err)

      addToast(
        err?.message || 'Error eliminant l’ordre.', 'error'
      )
    } finally {
      setLoading(false)
      setConfirmDelete(false)
    }
  }

  return (
    <>
      <div
        className={orderModalStyles.overlay}
        onClick={onClose}
      >
        <div
          className={orderModalStyles.modal}
          onClick={e => e.stopPropagation()}
        >
          <div className={orderModalStyles.header}>
            <h2 className={orderModalStyles.title}>
              {isEditing
                ? `Editant ordre #${order.id}`
                : 'Nova ordre'}
            </h2>

            <button
              type="button"
              onClick={onClose}
              disabled={loading}
            >
              <X size={20} />
            </button>
          </div>

          <div className={orderModalStyles.body}>
            <div className={orderModalStyles.gridSection}>
              <div className={orderModalStyles.gridBg}>
                <div className={orderModalStyles.gridInner}>
                  {loadingProducts ? (
                    <div className="p-4">
                      Carregant productes...
                    </div>
                  ) : (
                    Object.entries(byCategory).map(
                      ([category, categoryProducts]) => (
                        <div
                          key={category}
                          className="flex flex-col gap-2 mb-4"
                        >
                          <span
                            className={
                              orderModalStyles.categoryLabel
                            }
                          >
                            {category}
                          </span>

                          <div className={orderModalStyles.grid}>
                            {categoryProducts.map(product => (
                              <ProductCard
                                key={product.id}
                                product={product}
                                quantity={
                                  quantities[product.id] || 0
                                }
                                onAdd={handleAdd}
                                onRemove={handleRemove}
                              />
                            ))}
                          </div>
                        </div>
                      )
                    )
                  )}
                </div>
              </div>
            </div>

            <OrderSummary
              lines={orderLines}
              products={products}
              onSave={handleSave}
              onDelete={() => setConfirmDelete(true)}
              loading={loading}
              isEditing={isEditing}
            />
          </div>
        </div>
      </div>

      {confirmDelete && (
        <ConfirmModal
          message="Estàs segur que vols eliminar aquesta ordre? Aquesta acció no es pot desfer."
          onConfirm={handleDelete}
          onCancel={() => setConfirmDelete(false)}
        />
      )}
    </>
  )
}