import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { register } from '../api/auth'
import { useAuth } from '../hooks/useAuth'
import { authStyles } from '../styles/js/auth'

function validate(fields) {
  const errors = {}
  if (!fields.nom) errors.nom = 'El nom és obligatori.'
  if (!fields.email) errors.email = 'El correu és obligatori.'
  else if (!/\S+@\S+\.\S+/.test(fields.email)) errors.email = 'El correu no és vàlid.'
  if (!fields.password) errors.password = 'La contrasenya és obligatòria.'
  else if (fields.password.length < 8) errors.password = 'Mínim 8 caràcters.'
  if (!fields.password_confirmation) errors.password_confirmation = 'Confirma la contrasenya.'
  else if (fields.password !== fields.password_confirmation) errors.password_confirmation = 'Les contrasenyes no coincideixen.'
  return errors
}

export default function RegisterPage() {
  const navigate = useNavigate()
  const { saveSession } = useAuth()
  const [fields, setFields] = useState({
    nom: '',
    email: '',
    password: '',
    password_confirmation: '',
  })
  const [errors, setErrors] = useState({})
  const [serverError, setServerError] = useState(null)
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
    setServerError(null)
    try {
      const data = await register(fields)
      saveSession(data.user, data.token)
      navigate('/')
    } catch (err) {
      setServerError(err.message || 'Error en registrar-se.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className={authStyles.page}>
      <div className={authStyles.card}>
        <span className={authStyles.brand}>
          Brew <span className={authStyles.amp}>&</span> Co.
        </span>
        <div className={authStyles.header}>
          <h1 className={authStyles.title}>Crea el teu compte</h1>
          <p className={authStyles.subtitle}>Uneix-te a Brew & Co.</p>
        </div>
        {serverError && (
          <p className={authStyles.serverError}>{serverError}</p>
        )}
        <form className={authStyles.form} onSubmit={handleSubmit} noValidate>
          <div className={authStyles.field}>
            <label className={authStyles.label}>Nom</label>
            <input
              className={errors.nom ? authStyles.inputError : authStyles.input}
              type="text"
              name="nom"
              value={fields.nom}
              onChange={handleChange}
              placeholder="El teu nom"
              autoComplete="name"
            />
            {errors.nom && <span className={authStyles.error}>{errors.nom}</span>}
          </div>
          <div className={authStyles.field}>
            <label className={authStyles.label}>Correu electrònic</label>
            <input
              className={errors.email ? authStyles.inputError : authStyles.input}
              type="email"
              name="email"
              value={fields.email}
              onChange={handleChange}
              placeholder="tu@exemple.com"
              autoComplete="email"
            />
            {errors.email && <span className={authStyles.error}>{errors.email}</span>}
          </div>
          <div className={authStyles.field}>
            <label className={authStyles.label}>Contrasenya</label>
            <input
              className={errors.password ? authStyles.inputError : authStyles.input}
              type="password"
              name="password"
              value={fields.password}
              onChange={handleChange}
              placeholder="Mínim 8 caràcters"
              autoComplete="new-password"
            />
            {errors.password && <span className={authStyles.error}>{errors.password}</span>}
          </div>
          <div className={authStyles.field}>
            <label className={authStyles.label}>Confirma la contrasenya</label>
            <input
              className={errors.password_confirmation ? authStyles.inputError : authStyles.input}
              type="password"
              name="password_confirmation"
              value={fields.password_confirmation}
              onChange={handleChange}
              placeholder="••••••••"
              autoComplete="new-password"
            />
            {errors.password_confirmation && <span className={authStyles.error}>{errors.password_confirmation}</span>}
          </div>
          <button className={authStyles.btn} type="submit" disabled={loading}>
            {loading ? 'Creant compte...' : 'Crear compte'}
          </button>
        </form>
        <p className={authStyles.link}>
          Ja tens compte? <Link to="/login">Inicia sessió</Link>
        </p>
      </div>
    </div>
  )
}