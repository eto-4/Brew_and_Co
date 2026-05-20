import { useState } from 'react'
import { X } from 'lucide-react'
import { paymentModalStyles as s } from '../../styles/js/payment'
import PaymentStep1 from './PaymentStep1'
import PaymentStep2 from './PaymentStep2'
import PaymentStep3 from './PaymentStep3'

const STEP_LABELS = ['Enviament', 'Pagament', 'Confirmació']

export default function PaymentModal({ order, onClose, onSuccess }) {
  const [step, setStep] = useState(0)
  const [data, setData] = useState({
    metode: null,
    adreca_id: null,
    targeta: { numero: '', nom: '', caducitat: '', cvv: '' },
    codi_descompte: '',
    discount: null,
  })

  function handleChange(partial) {
    setData(prev => ({ ...prev, ...partial }))
  }

  return (
    <div className={s.overlay} onClick={onClose}>
      <div className={s.modal} onClick={e => e.stopPropagation()}>
        <div className={s.header}>
          <div className={s.headerLeft}>
            <h2 className={s.title}>Processar comanda #{order.id}</h2>
            <span className={s.stepLabel}>
              Pas {step + 1} de 3 — {STEP_LABELS[step]}
            </span>
          </div>
          <button onClick={onClose}><X size={20} /></button>
        </div>

        {step === 0 && (
          <PaymentStep1
            data={data}
            onChange={handleChange}
            onNext={() => setStep(1)}
          />
        )}
        {step === 1 && (
          <PaymentStep2
            data={data}
            onChange={handleChange}
            onNext={() => setStep(2)}
            onBack={() => setStep(0)}
          />
        )}
        {step === 2 && (
          <PaymentStep3
            data={data}
            order={order}
            onBack={() => setStep(1)}
            onSuccess={onSuccess}
          />
        )}
      </div>
    </div>
  )
}