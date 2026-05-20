import { Link } from 'react-router-dom'
import { navGuest } from '../../styles/js/navbar'

export default function GuestLinks() {
  return (
    <div className={navGuest.wrapper}>
      <Link to="/login" className={navGuest.login}>Iniciar sessió</Link>
      <Link to="/register" className={navGuest.register}>Registrar-se</Link>
    </div>
  )
}