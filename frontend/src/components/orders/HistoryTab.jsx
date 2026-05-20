import { ordersLayout, ordersEmptyStyles, orderCardStyles } from '../../styles/js/orders'

const ESTAT_LABELS = {
  entregada: 'Entregada',
  incidencia: 'Incidència',
}

const ESTAT_CLASSES = {
  entregada: 'order-status-success',
  incidencia: 'order-status-danger',
}

export default function HistoryTab({ orders }) {
  if (orders.length === 0) {
    return (
      <div className={ordersEmptyStyles.wrapper}>
        <p className={ordersEmptyStyles.text}>Encara no tens cap ordre completada.</p>
      </div>
    )
  }

  return (
    <div className={ordersLayout.tabContent}>
      {orders.map(order => (
        <div key={order.id} className={orderCardStyles.card}>
          <div className={orderCardStyles.header}>
            <span className={orderCardStyles.id}>Ordre #{order.id}</span>
            <span className={ESTAT_CLASSES[order.estat]}>
              {ESTAT_LABELS[order.estat] || order.estat}
            </span>
          </div>
          <span className={orderCardStyles.products}>
            {order.order_lines.map(l => `x${l.quantitat} ${l.product.nom}`).join(', ')}
          </span>
          {order.missatge && (
            <span className="order-incidencia-msg">{order.missatge}</span>
          )}
          <div className={orderCardStyles.footer}>
            <span className={orderCardStyles.total}>
              {parseFloat(order.total).toFixed(2)}€
            </span>
          </div>
        </div>
      ))}
    </div>
  )
}