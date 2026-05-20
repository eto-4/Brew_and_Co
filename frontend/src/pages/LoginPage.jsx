import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { login } from '../api/auth'
import { useAuth } from '../hooks/useAuth'
import { authStyles } from '../styles/js/auth'

function validate(fields) {
  const errors = {}
  if (!fields.email) errors.email = 'El correu és obligatori.'
  else if (!/\S+@\S+\.\S+/.test(fields.email)) errors.email = 'El correu no és vàlid.'
  if (!fields.password) errors.password = 'La contrasenya és obligatòria.'
  return errors
}

export default function LoginPage() {
  const navigate = useNavigate()
  const { saveSession } = useAuth()
  const [fields, setFields] = useState({ email: '', password: '' })
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
      const data = await login(fields)
      saveSession(data.user, data.token)
      navigate('/')
    } catch (err) {
      setServerError(err.message || 'Error en iniciar sessió.')
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
          <h1 className={authStyles.title}>Benvingut de nou</h1>
          <p className={authStyles.subtitle}>Inicia sessió per continuar</p>
        </div>
        {serverError && (
          <p className={authStyles.serverError}>{serverError}</p>
        )}
        <form className={authStyles.form} onSubmit={handleSubmit} noValidate>
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
              placeholder="••••••••"
              autoComplete="current-password"
            />
            {errors.password && <span className={authStyles.error}>{errors.password}</span>}
          </div>
          <button className={authStyles.btn} type="submit" disabled={loading}>
            {loading ? 'Iniciant sessió...' : 'Iniciar sessió'}
          </button>
        </form>
        <p className={authStyles.link}>
          No tens compte? <Link to="/register">Registra't</Link>
        </p>
      </div>
    </div>
  )
}