import { useState } from 'react'
import { updateProfile } from '../../api/user'
import { useAuth } from '../../hooks/useAuth'
import { settingsStyles } from '../../styles/js/settings'
import { useToast } from '../../hooks/useToast'

function validate(fields) {
  const errors = {}
  if (!fields.nom) errors.nom = 'El nom és obligatori.'
  if (!fields.email) errors.email = 'El correu és obligatori.'
  else if (!/\S+@\S+\.\S+/.test(fields.email)) errors.email = 'El correu no és vàlid.'
  return errors
}

export default function ProfileForm() {
  const { user, token, saveSession } = useAuth()
  const { addToast } = useToast()
  const [fields, setFields] = useState({ nom: user.nom, email: user.email })
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
      const updated = await updateProfile(token, fields)
      saveSession(updated, token)
      setSuccess(true)
      addToast('Perfil actualitzat correctament.')
    } catch (err) {
      setServerError(err.message || 'Error en actualitzar el perfil.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className={settingsStyles.section}>
      <h3 className={settingsStyles.sectionTitle}>Perfil</h3>
      {serverError && <p className={settingsStyles.serverError}>{serverError}</p>}
      <form className="flex flex-col gap-4" onSubmit={handleSubmit} noValidate>
        <div className={settingsStyles.field}>
          <label className={settingsStyles.label}>Nom</label>
          <input
            className={errors.nom ? settingsStyles.inputError : settingsStyles.input}
            type="text"
            name="nom"
            value={fields.nom}
            onChange={handleChange}
          />
          {errors.nom && <span className={settingsStyles.error}>{errors.nom}</span>}
        </div>
        <div className={settingsStyles.field}>
          <label className={settingsStyles.label}>Correu electrònic</label>
          <input
            className={errors.email ? settingsStyles.inputError : settingsStyles.input}
            type="email"
            name="email"
            value={fields.email}
            onChange={handleChange}
          />
          {errors.email && <span className={settingsStyles.error}>{errors.email}</span>}
        </div>
        <button className={settingsStyles.btn} type="submit" disabled={loading}>
          {loading ? 'Guardant...' : 'Guardar canvis'}
        </button>
      </form>
    </div>
  )
}