import { useState, useEffect, useMemo, useCallback } from 'react'

import Navbar from '../components/navbar/Navbar'
import DraftsTab from '../components/orders/DraftsTab'
import ActiveOrdersTab from '../components/orders/ActiveOrdersTab'
import HistoryTab from '../components/orders/HistoryTab'
import OrderModal from '../components/orders/OrderModal'
import PaymentModal from '../components/payment/PaymentModal'

import { getOrders } from '../api/orders'
import { useAuth } from '../hooks/useAuth'
import { useToast } from '../hooks/useToast'

import { ordersLayout } from '../styles/js/orders'

const TABS = [
  { key: 'drafts', label: 'Borradors' },
  { key: 'active', label: 'En moviment' },
  { key: 'history', label: 'Historial' },
]

const STATE_GROUPS = {
  drafts: ['pendent'],
  active: ['empaquetant', 'en_enviament'],
  history: ['entregada', 'incidencia'],
}

export default function OrdersPage() {
  const { user, token } = useAuth()
  const { addToast } = useToast()
  const [paymentModal, setPaymentModal] = useState(null)
  const [activeTab, setActiveTab] = useState('drafts')
  const [orders, setOrders] = useState([])
  const [loadingOrders, setLoadingOrders] = useState(false)

  const [modal, setModal] = useState({
    type: null, // 'create' | 'edit'
    order: null,
  })

  const loadOrders = useCallback(async (signal) => {
    setLoadingOrders(true)

    try {
      const data = await getOrders(token, { signal })
      setOrders(data)
    } catch (err) {
      if (err.name === 'AbortError') return

      console.error(err)
      addToast(err?.message || 'Error carregant ordres.')
    } finally {
      setLoadingOrders(false)
    }
  }, [token, addToast])

  useEffect(() => {
    if (!token) return

    const controller = new AbortController()

    loadOrders(controller.signal)

    return () => controller.abort()
  }, [token, loadOrders])

  const { draftOrders, activeOrders, historyOrders } = useMemo(() => {
    const draft = []
    const active = []
    const history = []

    for (const order of orders) {
      if (STATE_GROUPS.drafts.includes(order.estat)) {
        draft.push(order)
      } else if (STATE_GROUPS.active.includes(order.estat)) {
        active.push(order)
      } else if (STATE_GROUPS.history.includes(order.estat)) {
        history.push(order)
      }
    }

    return {
      draftOrders: draft,
      activeOrders: active,
      historyOrders: history,
    }
  }, [orders])

  function handleEdit(order) {
    setModal({
      type: 'edit',
      order,
    })
  }

  function handleCreate() {
    setModal({
      type: 'create',
      order: null,
    })
  }

  function handleProcess(order) {
    setPaymentModal(order)
  }

  function handlePaymentSuccess() {
    setPaymentModal(null)
    setActiveTab('active')
    loadOrders()
  }

  return (
    <div className={ordersLayout.page}>
      <Navbar user={user} />

      <div className={ordersLayout.content}>
        <div className={ordersLayout.container}>

          {/* Tabs */}
          <div className={ordersLayout.tabs}>
            {TABS.map(tab => (
              <button
                key={tab.key}
                className={
                  activeTab === tab.key
                    ? ordersLayout.tabActive
                    : ordersLayout.tab
                }
                onClick={() => setActiveTab(tab.key)}
              >
                {tab.label}
              </button>
            ))}
          </div>

          {/* Loading state */}
          {loadingOrders && (
            <div className="p-4">Carregant ordres...</div>
          )}

          {/* Content */}
          {!loadingOrders && (
            <>
              {activeTab === 'drafts' && (
                <DraftsTab
                  orders={draftOrders}
                  onEdit={handleEdit}
                  onProcess={handleProcess}
                  onCreateNew={handleCreate}
                />
              )}

              {activeTab === 'active' && (
                <ActiveOrdersTab 
                  orders={activeOrders}
                  onOrderCompleted={loadOrders} 
                />
              )}

              {activeTab === 'history' && (
                <HistoryTab orders={historyOrders} />
              )}
            </>
          )}

          {/* Footer CTA */}
          {activeTab === 'drafts' && draftOrders.length > 0 && (
            <div className={ordersLayout.footer}>
              <button
                className="orders-create-btn px-5 py-2.5"
                onClick={handleCreate}
              >
                + Nova ordre
              </button>
            </div>
          )}

        </div>
      </div>

      {/* Modal */}
      {modal.type && (
        <OrderModal
          order={modal.type === 'edit' ? modal.order : null}
          onClose={() => setModal({ type: null, order: null })}
          onSaved={() => loadOrders()}
        />
      )}
      {paymentModal && (
        <PaymentModal
          order={paymentModal}
          onClose={() => setPaymentModal(null)}
          onSuccess={handlePaymentSuccess}
        />
      )}
    </div>
  )
}