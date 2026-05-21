import { useState } from 'react'
import { useAuth } from '../../hooks/useAuth'
import { useOrderPolling } from '../../hooks/useOrderPolling'
import { Check } from 'lucide-react'

const STEPS = [
  {
    key: 'empaquetant',
    label: 'Empaquetant',
    icon: `${import.meta.env.BASE_URL}steps/step-empaquetant.webp`,
    iconDone: `${import.meta.env.BASE_URL}steps/step-empaquetant-done.webp`,
  },
  {
    key: 'en_enviament',
    label: 'En enviament',
    icon: `${import.meta.env.BASE_URL}steps/step-enviament.webp`,
    iconDone: `${import.meta.env.BASE_URL}steps/step-enviament-done.webp`,
  },
  {
    key: 'entregada',
    label: 'Entregada',
    icon: `${import.meta.env.BASE_URL}steps/step-entregada.webp`,
    iconDone: `${import.meta.env.BASE_URL}steps/step-entregada-done.webp`,
  },
]

function getStepStatus(stepIdx, currentStepIdx) {
  if (stepIdx < currentStepIdx) return 'done'
  if (stepIdx === currentStepIdx) return 'active'
  return 'pending'
}

function formatCountdown(temps_estimat) {
  if (!temps_estimat) return '--:--'
  const diff = Math.max(0, new Date(temps_estimat) - new Date())
  const mins = Math.floor(diff / 60000)
  const secs = Math.floor((diff % 60000) / 1000)
  return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`
}

export default function OrderTrackerCard({ order: initialOrder, onCompleted }) {
  const { token } = useAuth()
  const [order, setOrder] = useState(initialOrder)

  useOrderPolling(token, order.id, (updated) => {
    setOrder(updated)
    if (updated.estat === 'entregada' || updated.estat === 'incidencia') {
      onCompleted?.()
    }
  })

  const currentStepIdx = STEPS.findIndex(
    step => step.key === order.estat
  )

  return (
    <div className="tracker-card flex flex-col gap-4 p-5">
      <div className="flex items-center justify-between">
        <span className="tracker-order-id">Ordre #{order.id}</span>
        <span className="tracker-order-total">{parseFloat(order.total).toFixed(2)}€</span>
      </div>

      <div className="flex items-start gap-2">
        {STEPS.map((step, idx) => {
          const status = getStepStatus(idx, currentStepIdx)
          const isLast = idx === STEPS.length - 1

          return (
            <div key={step.key} className="flex items-start" style={{ flex: isLast ? 'none' : 1 }}>
              <div className="flex flex-col items-center gap-2" style={{ minWidth: 72 }}>
                <img
                  src={status === 'done' || status === 'active' ? step.iconDone : step.icon}
                  alt={step.label}
                  className={`tracker-step-icon ${status === 'pending' ? 'pending' : ''}`}
                />
                <span className={`tracker-step-label ${status}`}>
                  {step.label}
                </span>
                <span className={`tracker-countdown ${status}`}>
                  {status === 'active'
                    ? formatCountdown(order.temps_estimat)
                    : status === 'done'
                    ? ( <Check size={18} strokeWidth={3} /> )
                    : ( '--:--' )
                  }
                </span>
              </div>

              {!isLast && (
                <div className="tracker-bar-wrapper">
                  <div className={`tracker-bar-fill ${status === 'done' ? 'done' : status === 'active' ? 'active' : ''}`} />
                </div>
              )}
            </div>
          )
        })}
      </div>

      {order.missatge && (
        <p className="order-incidencia-msg">{order.missatge}</p>
      )}
    </div>
  )
}