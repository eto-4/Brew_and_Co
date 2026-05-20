import { useState } from 'react'
import { X } from 'lucide-react'
import { modalStyles } from '../../styles/js/settings'

function validate(fields) {
  const errors = {}
  if (!fields.adreca) errors.adreca = "L'adreça és obligatòria."
  if (!fields.codi_postal) errors.codi_postal = 'El codi postal és obligatori.'
  if (!fields.ciutat) errors.ciutat = 'La ciutat és obligatòria.'
  return errors
}

function getInitialFields(address) {
  if (!address) return { etiqueta: '', adreca: '', codi_postal: '', ciutat: '' }
  return {
    etiqueta: address.etiqueta || '',
    adreca: address.adreca || '',
    codi_postal: address.codi_postal || '',
    ciutat: address.ciutat || '',
  }
}

export default function AddressModal({ address, onClose, onSave }) {
  const [fields, setFields] = useState(() => getInitialFields(address))
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)

  function handleChange(e) {
    const { name, value } = e.target
    setFields(prev => ({ ...prev, [name]: value }))
    if (errors[name]) setErrors(prev => ({ ...prev, [name]: null }))
  }

  async function handleSubmit(e) {
    e.preventDefault()
    const validation = validate(fields)
    if (Object.keys(validation).length) return setErrors(validation)
    setLoading(true)
    await onSave(fields, address?.id)
    setLoading(false)
  }

  return (
    <div className={modalStyles.overlay} onClick={onClose}>
      <div className={modalStyles.card} onClick={e => e.stopPropagation()}>
        <div className={modalStyles.header}>
          <h2 className={modalStyles.title}>
            {address ? 'Editar adreça' : 'Nova adreça'}
          </h2>
          <button className={modalStyles.closeBtn} onClick={onClose}>
            <X size={18} />
          </button>
        </div>
        <form className={modalStyles.form} onSubmit={handleSubmit} noValidate>
          <div className={modalStyles.field}>
            <label className={modalStyles.label}>Etiqueta (opcional)</label>
            <input
              className={modalStyles.input}
              type="text"
              name="etiqueta"
              value={fields.etiqueta}
              onChange={handleChange}
              placeholder="Casa, Feina..."
            />
          </div>
          <div className={modalStyles.field}>
            <label className={modalStyles.label}>Adreça</label>
            <input
              className={errors.adreca ? modalStyles.inputError : modalStyles.input}
              type="text"
              name="adreca"
              value={fields.adreca}
              onChange={handleChange}
              placeholder="Carrer Major 1"
            />
            {errors.adreca && <span className={modalStyles.error}>{errors.adreca}</span>}
          </div>
          <div className={modalStyles.field}>
            <label className={modalStyles.label}>Codi postal</label>
            <input
              className={errors.codi_postal ? modalStyles.inputError : modalStyles.input}
              type="text"
              name="codi_postal"
              value={fields.codi_postal}
              onChange={handleChange}
              placeholder="08240"
            />
            {errors.codi_postal && <span className={modalStyles.error}>{errors.codi_postal}</span>}
          </div>
          <div className={modalStyles.field}>
            <label className={modalStyles.label}>Ciutat</label>
            <input
              className={errors.ciutat ? modalStyles.inputError : modalStyles.input}
              type="text"
              name="ciutat"
              value={fields.ciutat}
              onChange={handleChange}
              placeholder="Manresa"
            />
            {errors.ciutat && <span className={modalStyles.error}>{errors.ciutat}</span>}
          </div>
          <div className={modalStyles.actions}>
            <button type="button" className={modalStyles.btnCancel} onClick={onClose}>
              Cancel·lar
            </button>
            <button type="submit" className={modalStyles.btnSubmit} disabled={loading}>
              {loading ? 'Guardant...' : 'Guardar'}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}