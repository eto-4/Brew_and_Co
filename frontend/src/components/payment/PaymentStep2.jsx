import { useState } from 'react'
import { validateDiscount } from '../../api/payments'
import { useAuth } from '../../hooks/useAuth'
import { paymentModalStyles as s } from '../../styles/js/payment'

function validateCard(card) {
  const errors = {}
  if (!card.numero || card.numero.replace(/\s/g, '').length < 16)
    errors.numero = 'Número de targeta invàlid.'
  if (!card.nom) errors.nom = 'El nom és obligatori.'
  if (!card.caducitat || !/^\d{2}\/\d{2}$/.test(card.caducitat))
    errors.caducitat = 'Format MM/AA.'
  if (!card.cvv || card.cvv.length < 3) errors.cvv = 'CVV invàlid.'
  return errors
}

export default function PaymentStep2({ data, onChange, onNext, onBack }) {
  const { token } = useAuth()
  const [errors, setErrors] = useState({})
  const [discountLoading, setDiscountLoading] = useState(false)
  const [discountValid, setDiscountValid] = useState(null)

  function handleCardChange(field, value) {
    onChange({ targeta: { ...data.targeta, [field]: value } })
    if (errors[field]) setErrors(prev => ({ ...prev, [field]: null }))
  }

  async function handleValidateDiscount() {
    if (!data.codi_descompte) return
    setDiscountLoading(true)
    setDiscountValid(null)
    try {
      const res = await validateDiscount(token, data.codi_descompte)
      onChange({ discount: res })
      setDiscountValid(`Descompte del ${res.percentatge}% aplicat.`)
    } catch (err) {
      setErrors(prev => ({ ...prev, discount: err.message }))
      onChange({ discount: null })
    } finally {
      setDiscountLoading(false)
    }
  }

  function handleNext() {
    if (data.metode === 'targeta') {
      const cardErrors = validateCard(data.targeta || {})
      if (Object.keys(cardErrors).length) return setErrors(cardErrors)
    }
    onNext()
  }

  return (
    <>
      <div className={s.body}>
        {data.metode === 'targeta' && (
          <div className={s.section}>
            <p className={s.sectionTitle}>Dades de la targeta</p>
            <div className={s.field}>
              <input
                className={errors.numero ? s.inputError : s.input}
                placeholder="Número de targeta"
                value={data.targeta?.numero || ''}
                onChange={e => handleCardChange('numero', e.target.value)}
                maxLength={19}
              />
              {errors.numero && <span className={s.error}>{errors.numero}</span>}
            </div>
            <div className={s.field}>
              <input
                className={errors.nom ? s.inputError : s.input}
                placeholder="Nom del titular"
                value={data.targeta?.nom || ''}
                onChange={e => handleCardChange('nom', e.target.value)}
              />
              {errors.nom && <span className={s.error}>{errors.nom}</span>}
            </div>
            <div className={s.inputRow}>
              <div className={s.field} style={{ flex: 1 }}>
                <input
                  className={errors.caducitat ? s.inputError : s.input}
                  placeholder="MM/AA"
                  value={data.targeta?.caducitat || ''}
                  onChange={e => handleCardChange('caducitat', e.target.value)}
                  maxLength={5}
                />
                {errors.caducitat && <span className={s.error}>{errors.caducitat}</span>}
              </div>
              <div className={s.field} style={{ flex: 1 }}>
                <input
                  className={errors.cvv ? s.inputError : s.input}
                  placeholder="CVV"
                  value={data.targeta?.cvv || ''}
                  onChange={e => handleCardChange('cvv', e.target.value)}
                  maxLength={4}
                />
                {errors.cvv && <span className={s.error}>{errors.cvv}</span>}
              </div>
            </div>
          </div>
        )}

        <div className={s.section}>
          <p className={s.sectionTitle}>Codi de descompte (opcional)</p>
          <div className="flex gap-2">
            <input
              className={s.input}
              placeholder="BREWCO10"
              value={data.codi_descompte || ''}
              onChange={e => {
                onChange({ codi_descompte: e.target.value, discount: null })
                setDiscountValid(null)
                setErrors(prev => ({ ...prev, discount: null }))
              }}
              style={{ flex: 1 }}
            />
            <button
              className={s.btnSecondary}
              onClick={handleValidateDiscount}
              disabled={discountLoading || !data.codi_descompte}
            >
              {discountLoading ? '...' : 'Validar'}
            </button>
          </div>
          {discountValid && <span className={s.discountValid}>{discountValid}</span>}
          {errors.discount && <span className={s.error}>{errors.discount}</span>}
        </div>
      </div>

      <div className={s.footer}>
        <button className={s.btnSecondary} onClick={onBack}>← Anterior</button>
        <button className={s.btnPrimary} onClick={handleNext}>Següent →</button>
      </div>
    </>
  )
}