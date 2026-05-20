import { ordersLayout, ordersEmptyStyles } from '../../styles/js/orders'
import DraftOrderCard from './DraftOrderCard'

export default function DraftsTab({ orders, onEdit, onProcess, onCreateNew }) {
  if (orders.length === 0) {
    return (
      <div className={ordersEmptyStyles.wrapper}>
        <p className={ordersEmptyStyles.text}>No tens cap ordre registrada encara.</p>
        <button className={ordersEmptyStyles.btn} onClick={onCreateNew}>
          Clica'm per crear la teva primera ordre
        </button>
      </div>
    )
  }

  return (
    <div className={ordersLayout.tabContent}>
      {orders.map(order => (
        <DraftOrderCard
          key={order.id}
          order={order}
          onEdit={onEdit}
          onProcess={onProcess}
        />
      ))}
    </div>
  )
}