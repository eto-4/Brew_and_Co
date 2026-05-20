import { ordersLayout, ordersEmptyStyles } from '../../styles/js/orders'
import OrderTrackerCard from './OrderTrackerCard'

export default function ActiveOrdersTab({ orders, onOrderCompleted }) {
  if (orders.length === 0) {
    return (
      <div className={ordersEmptyStyles.wrapper}>
        <p className={ordersEmptyStyles.text}>No tens cap ordre en curs.</p>
      </div>
    )
  }

  return (
    <div className={ordersLayout.tabContent}>
      {orders.map(order => (
        <OrderTrackerCard
          key={order.id}
          order={order}
          onCompleted={onOrderCompleted}
        />
      ))}
    </div>
  )
}