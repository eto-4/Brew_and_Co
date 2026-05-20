import { orderModalStyles } from '../../styles/js/orders'

export default function OrderSummary({ lines, products, onSave, onDelete, loading, isEditing }) {
  const total = lines.reduce((acc, line) => {
    const product = products.find(p => p.id === line.producte_id)
    if (!product) return acc
    return acc + parseFloat(product.preu) * line.quantitat
  }, 0)

  return (
    <div className={orderModalStyles.summarySection}>
      <div className={orderModalStyles.summaryHeader}>
        <span className={orderModalStyles.summaryTitle}>Resum</span>
      </div>
      <div className={orderModalStyles.summaryBody}>
        {lines.length === 0 && (
          <span className="order-summary-item-name" style={{ opacity: 0.5 }}>
            Encara no has afegit cap producte.
          </span>
        )}
        {lines.map(line => {
          const product = products.find(p => p.id === line.producte_id)
          if (!product) return null
          const subtotal = (parseFloat(product.preu) * line.quantitat).toFixed(2)
          return (
            <div key={line.producte_id} className={orderModalStyles.summaryItem}>
              <span className={orderModalStyles.summaryItemName}>
                x{line.quantitat} {product.nom}
              </span>
              <span className={orderModalStyles.summaryItemPrice}>{subtotal}€</span>
            </div>
          )
        })}
        {lines.length > 0 && (
          <>
            <div className={orderModalStyles.summaryDivider} />
            <div className={orderModalStyles.summaryTotalRow}>
              <span className={orderModalStyles.summaryTotalLabel}>Total</span>
              <span className={orderModalStyles.summaryTotalValue}>{total.toFixed(2)}€</span>
            </div>
          </>
        )}
      </div>
      <div className={orderModalStyles.summaryFooter}>
        {isEditing && (
          <button className={orderModalStyles.deleteBtn} onClick={onDelete}>
            Eliminar ordre
          </button>
        )}
        <button
          className={orderModalStyles.saveBtn}
          onClick={onSave}
          disabled={loading || lines.length === 0}
        >
          {loading ? 'Guardant...' : isEditing ? 'Guardar canvis' : 'Crear ordre'}
        </button>
      </div>
    </div>
  )
}