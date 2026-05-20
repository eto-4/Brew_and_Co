import { useState, useEffect } from 'react'
import { getAddresses, createAddress } from '../../api/user'
import { getPaymentPrefill } from '../../api/payments'
import { useAuth } from '../../hooks/useAuth'
import { paymentModalStyles as s } from '../../styles/js/payment'
import AddressModal from '../settings/AddressModal'
import { useToast } from '../../hooks/useToast'
import { CreditCard, Banknote } from 'lucide-react'

export default function PaymentStep1({ data, onChange, onNext }) {
  const { token, user } = useAuth()
  const { addToast } = useToast()
  const isAdmin = user?.rol === 'admin'
  const [addresses, setAddresses] = useState([])
  const [showAddressModal, setShowAddressModal] = useState(false)
  const [errors, setErrors] = useState({})

  async function loadAddresses() {
    try {
      const data = await getAddresses(token)
      setAddresses(data)
      if (data.length > 0 && !onChange.adreca_id) {
        const defaultAddr = data.find(a => a.predeterminada) || data[0]
        onChange({ adreca_id: defaultAddr.id })
      }
    } catch (err) {
      console.error(err)
    }
  }

  useEffect(() => {
    loadAddresses()
  }, [token])

  async function handlePrefill() {
    try {
      const prefill = await getPaymentPrefill(token)
      onChange({ targeta: prefill.targeta })
      if (prefill.adreca_id) {
        onChange({ adreca_id: prefill.adreca_id })
      } else if (prefill.adreca && addresses.length === 0) {
        await createAddress(token, prefill.adreca)
        await loadAddresses()
      }
    } catch (err) {
      console.error(err)
      addToast(
        err?.message || 'Error modificant les teves adreçes.', 'error'
      )
    }
  }

  async function handleSaveAddress(fields) {
    try {
      await createAddress(token, fields)
      await loadAddresses()
      setShowAddressModal(false)
    } catch (err) {
      console.error(err)
      addToast(
        err?.message || 'Error guardant l’adreça.', 'error'
      )
    }
  }

  function validate() {
    const errs = {}
    if (!data.metode) errs.metode = 'Selecciona un mètode de pagament.'
    if (!data.adreca_id) errs.adreca_id = 'Selecciona una adreça.'
    return errs
  }

  function handleNext() {
    const errs = validate()
    if (Object.keys(errs).length) return setErrors(errs)
    onNext()
  }

  return (
    <>
      <div className={s.body}>
        {isAdmin && (
          <div className="flex justify-end">
            <button className={s.btnPrefill} onClick={handlePrefill}>
              Prefill admin
            </button>
          </div>
        )}

        <div className={s.section}>
          <p className={s.sectionTitle}>Mètode de pagament</p>
          <div className={s.methodRow}>
            <button
              className={data.metode === 'targeta' ? s.methodBtnSelected : s.methodBtn}
              onClick={() => onChange({ metode: 'targeta' })}
            >
              <CreditCard size={16} style={{ display: 'inline', marginRight: 6 }} />
              Targeta
            </button>
            <button
              className={data.metode === 'efectiu' ? s.methodBtnSelected : s.methodBtn}
              onClick={() => onChange({ metode: 'efectiu' })}
            >
              <Banknote size={16} style={{ display: 'inline', marginRight: 6 }} />
              Efectiu
            </button>
          </div>
          {errors.metode && <span className={s.error}>{errors.metode}</span>}
        </div>

        <div className={s.section}>
          <p className={s.sectionTitle}>Adreça d'enviament</p>
          <div className="flex flex-col gap-2 max-h-[240px] overflow-y-auto">
            {addresses.length >= 5 && (
              <button className="payment-address-add" onClick={() => setShowAddressModal(true)}>
                + Afegir adreça
              </button>
            )}
            {addresses.map(addr => (
              <button
                key={addr.id}
                className={data.adreca_id === addr.id ? 'payment-address-card selected' : 'payment-address-card'}
                onClick={() => onChange({ adreca_id: addr.id })}
              >
                <span className="payment-address-tag">
                  {addr.etiqueta || addr.adreca}
                  {addr.predeterminada ? ' ★' : ''}
                </span>
                <span className="payment-address-info">
                  {addr.adreca}, {addr.codi_postal} {addr.ciutat}
                </span>
              </button>
            ))}
            {addresses.length < 5 && (
              <button className="payment-address-add" onClick={() => setShowAddressModal(true)}>
                + Afegir adreça
              </button>
            )}
          </div>
          {errors.adreca_id && <span className={s.error}>{errors.adreca_id}</span>}
        </div>
      </div>

      <div className={s.footer}>
        <div />
        <button className={s.btnPrimary} onClick={handleNext}>
          Següent →
        </button>
      </div>

      {showAddressModal && (
        <AddressModal
          address={null}
          onClose={() => setShowAddressModal(false)}
          onSave={handleSaveAddress}
        />
      )}
    </>
  )
}