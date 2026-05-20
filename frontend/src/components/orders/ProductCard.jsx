import { productCardStyles } from '../../styles/js/orders'

export default function ProductCard({ product, quantity, onAdd, onRemove }) {
  const available = product.disponible
  const imageUrl = `${import.meta.env.VITE_API_URL}${product.imatge}`

  return (
    <div className={available ? productCardStyles.card : productCardStyles.cardUnavailable}>
      <img
        src={imageUrl}
        alt=""
        className={available ? productCardStyles.image : productCardStyles.imageGray}
        draggable={false}
      />
      <div className={productCardStyles.info}>
        <span className={productCardStyles.name}>{product.nom}</span>
        <div className={productCardStyles.bottom}>
          <div className={productCardStyles.qtyRow}>
            <button className={productCardStyles.qtyBtn} onClick={() => onRemove(product.id)}>−</button>
            <span className={productCardStyles.qtyValue}>{quantity}</span>
            <button className={productCardStyles.qtyBtn} onClick={() => onAdd(product.id)}>+</button>
          </div>
          <span className={productCardStyles.price}>{parseFloat(product.preu).toFixed(2)}€</span>
        </div>
      </div>
    </div>
  )
}