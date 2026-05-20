import { useState } from 'react'
import { processPayment } from '../../api/payments'
import { useAuth } from '../../hooks/useAuth'
import { paymentModalStyles as s } from '../../styles/js/payment'
import { CheckCircle, XCircle } from 'lucide-react'

export default function PaymentStep3({ data, order, onBack, onSuccess }) {
  const { token } = useAuth()
  const [loading, setLoading] = useState(false)
  const [result, setResult] = useState(null)

  const total = parseFloat(order.total)
  const discount = data.discount ? parseFloat(data.discount.percentatge) : 0
  const discountAmount = total * (discount / 100)
  const finalTotal = total - discountAmount

  async function handleConfirm() {
    setLoading(true)
    try {
      const payload = {
        metode: data.metode,
        adreca_id: data.adreca_id,
        codi_descompte: data.codi_descompte || null,
      }
      const res = await processPayment(token, order.id, payload)
      setResult({ success: res.result.estat === 'exit', descripcio: res.result.descripcio })
      if (res.result.estat === 'exit') {
        setTimeout(() => onSuccess(), 2000)
      }
    } catch (err) {
      setResult({ success: false, descripcio: err.message || 'Error en processar el pagament.' })
    } finally {
      setLoading(false)
    }
  }

  if (result) {
    return (
      <>
        <div className={s.body}>
          <div className={s.resultWrapper}>
            <span className={s.resultIcon}>{
              result.success 
                ? <CheckCircle size={48} color="var(--color-success)" /> 
                : <XCircle size={48} color="var(--color-danger)" />  
            }</span>
            <p className={result.success ? s.resultSuccess : s.resultError}>
              {result.descripcio}
            </p>
          </div>
        </div>
        {!result.success && (
          <div className={s.footer}>
            <button className={s.btnSecondary} onClick={() => setResult(null)}>
              Tornar a intentar
            </button>
          </div>
        )}
      </>
    )
  }

  return (
    <>
      <div className={s.body}>
        <div className={s.section}>
          <p className={s.sectionTitle}>Resum de la comanda</p>
          {order.order_lines.map(line => (
            <div key={line.id} className={s.summaryRow}>
              <span>x{line.quantitat} {line.product.nom}</span>
              <span>{(parseFloat(line.preu_unitari) * line.quantitat).toFixed(2)}€</span>
            </div>
          ))}
          <div className={s.summaryDivider} />
          <div className={s.summaryRow}>
            <span>Subtotal</span>
            <span>{total.toFixed(2)}€</span>
          </div>
          {discount > 0 && (
            <div className={s.summaryDiscount}>
              <span>Descompte ({discount}%)</span>
              <span>-{discountAmount.toFixed(2)}€</span>
            </div>
          )}
          <div className={s.summaryDivider} />
          <div className={s.summaryTotal}>
            <span>Total</span>
            <span>{finalTotal.toFixed(2)}€</span>
          </div>
        </div>

        <div className={s.section}>
          <p className={s.sectionTitle}>Detalls</p>
          <div className={s.summaryRow}>
            <span>Mètode</span>
            <span>{data.metode === 'targeta' ? 'Targeta' : 'Efectiu'}</span>
          </div>
        </div>
      </div>

      <div className={s.footer}>
        <button className={s.btnSecondary} onClick={onBack}>← Anterior</button>
        <button className={s.btnConfirm} onClick={handleConfirm} disabled={loading}>
          {loading ? 'Processant...' : 'Confirmar pagament'}
        </button>
      </div>
    </>
  )
}