import { orderCardStyles } from '../../styles/js/orders'

function summarizeProducts(orderLines) {
  return orderLines.map(line => `x${line.quantitat} ${line.product.nom}`).join(', ')
}

export default function DraftOrderCard({ order, onEdit, onProcess }) {
  return (
    <div className={orderCardStyles.card}>
      <div className={orderCardStyles.header}>
        <span className={orderCardStyles.id}>Ordre #{order.id}</span>
      </div>
      <span className={orderCardStyles.products}>
        {summarizeProducts(order.order_lines)}
      </span>
      <div className={orderCardStyles.footer}>
        <span className={orderCardStyles.total}>{parseFloat(order.total).toFixed(2)}€</span>
        <div className={orderCardStyles.actions}>
          <button className={orderCardStyles.btnEdit} onClick={() => onEdit(order)}>
            Editar
          </button>
          <button className={orderCardStyles.btnProcess} onClick={() => onProcess(order)}>
            Processar
          </button>
        </div>
      </div>
    </div>
  )
}