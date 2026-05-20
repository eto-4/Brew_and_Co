import { useState } from 'react'
import { updatePassword } from '../../api/user'
import { useAuth } from '../../hooks/useAuth'
import { settingsStyles } from '../../styles/js/settings'
import { useToast } from '../../hooks/useToast'

function validate(fields) {
  const errors = {}
  if (!fields.password_actual) errors.password_actual = 'La contrasenya actual és obligatòria.'
  if (!fields.password) errors.password = 'La nova contrasenya és obligatòria.'
  else if (fields.password.length < 8) errors.password = 'Mínim 8 caràcters.'
  if (!fields.password_confirmation) errors.password_confirmation = 'Confirma la nova contrasenya.'
  else if (fields.password !== fields.password_confirmation) errors.password_confirmation = 'Les contrasenyes no coincideixen.'
  return errors
}

export default function PasswordForm() {
  const { token } = useAuth()
  const { addToast } = useToast()
  const [fields, setFields] = useState({
    password_actual: '',
    password: '',
    password_confirmation: '',
  })
  const [errors, setErrors] = useState({})
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState(false)
  const [serverError, setServerError] = useState(null)

  function handleChange(e) {
    const { name, value } = e.target
    setFields(prev => ({ ...prev, [name]: value }))
    if (errors[name]) setErrors(prev => ({ ...prev, [name]: null }))
    setSuccess(false)
    setServerError(null)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    const validation = validate(fields)
    if (Object.keys(validation).length) return setErrors(validation)
    setLoading(true)
    try {
      await updatePassword(token, fields)
      setSuccess(true)
      addToast('Contrasenya actualitzada correctament.')
      setFields({ password_actual: '', password: '', password_confirmation: '' })
    } catch (err) {
      if (err.errors) {
        const mapped = {}
        if (err.errors.password_actual) mapped.password_actual = err.errors.password_actual[0]
        if (err.errors.password) mapped.password = err.errors.password[0]
        setErrors(mapped)
      } else {
        setServerError(err.message || 'Error en canviar la contrasenya.')
      }
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className={settingsStyles.section}>
      <h3 className={settingsStyles.sectionTitle}>Canviar contrasenya</h3>
      {serverError && <p className={settingsStyles.serverError}>{serverError}</p>}
      <form className="flex flex-col gap-4" onSubmit={handleSubmit} noValidate>
        <div className={settingsStyles.field}>
          <label className={settingsStyles.label}>Contrasenya actual</label>
          <input
            className={errors.password_actual ? settingsStyles.inputError : settingsStyles.input}
            type="password"
            name="password_actual"
            value={fields.password_actual}
            onChange={handleChange}
            placeholder="••••••••"
            autoComplete="current-password"
          />
          {errors.password_actual && <span className={settingsStyles.error}>{errors.password_actual}</span>}
        </div>
        <div className={settingsStyles.field}>
          <label className={settingsStyles.label}>Nova contrasenya</label>
          <input
            className={errors.password ? settingsStyles.inputError : settingsStyles.input}
            type="password"
            name="password"
            value={fields.password}
            onChange={handleChange}
            placeholder="Mínim 8 caràcters"
            autoComplete="new-password"
          />
          {errors.password && <span className={settingsStyles.error}>{errors.password}</span>}
        </div>
        <div className={settingsStyles.field}>
          <label className={settingsStyles.label}>Confirma la nova contrasenya</label>
          <input
            className={errors.password_confirmation ? settingsStyles.inputError : settingsStyles.input}
            type="password"
            name="password_confirmation"
            value={fields.password_confirmation}
            onChange={handleChange}
            placeholder="••••••••"
            autoComplete="new-password"
          />
          {errors.password_confirmation && <span className={settingsStyles.error}>{errors.password_confirmation}</span>}
        </div>
        <button className={settingsStyles.btn} type="submit" disabled={loading}>
          {loading ? 'Guardant...' : 'Canviar contrasenya'}
        </button>
      </form>
    </div>
  )
}