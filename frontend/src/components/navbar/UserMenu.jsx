import { useState } from 'react'
import { navUser } from '../../styles/js/navbar'
import { useAuth } from '../../hooks/useAuth'
import { Link, useNavigate } from 'react-router-dom'
import SettingsDrawer from '../settings/SettingsDrawer'


export default function UserMenu({ user }) {
  const [open, setOpen] = useState(false)
  const [showSettings, setShowSettings] = useState(false)
  const { clearSession } = useAuth()
  const navigate = useNavigate()

  function handleAdminEntry() {
    navigate('/admin')
  }

  function handleLogout() {
    clearSession()
    navigate('/')
  }
  return (
    <>
      <div className="relative">
        <button className={navUser.wrapper} onClick={() => setOpen(!open)}>
          <div className={navUser.avatar}>
            {user.nom.slice(0, 2).toUpperCase()}
          </div>
          <span className={navUser.username}>{user.nom}</span>
          <span className={navUser.chevron}>{open ? '▴' : '▾'}</span>
        </button>
    
        {open && (
          <div className={navUser.dropdown}>
            <button 
              className={navUser.dropdownItem}
              onClick={() => {setShowSettings(true); setOpen(false)}}
            >
              Configuració
            </button>
            {user.rol === 'admin' && (
              <button className={navUser.dropdownItem} onClick={handleAdminEntry}>
                Administració
              </button>
            )}
            <button className={navUser.dropdownItem} onClick={handleLogout}>
              Tancar sessió
            </button>
          </div>
        )}
      </div>
      {showSettings && <SettingsDrawer onClose={() => setShowSettings(false)} />}
    </>
  )
}