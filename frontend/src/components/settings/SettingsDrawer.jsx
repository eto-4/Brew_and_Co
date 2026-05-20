import { useState, useEffect } from 'react'
import { X } from 'lucide-react'
import { getAddresses, createAddress, updateAddress, deleteAddress, setDefaultAddress } from '../../api/user'
import { useAuth } from '../../hooks/useAuth'
import { useToast } from '../../hooks/useToast'
import { drawerStyles, settingsStyles } from '../../styles/js/settings'
import ProfileForm from './ProfileForm'
import AddressList from './AddressList'
import AddressModal from './AddressModal'
import PasswordForm from './PasswordForm'
import ConfirmModal from '../ui/ConfirmModal'

export default function SettingsDrawer({ onClose }) {
  const { token } = useAuth()
  const { addToast } = useToast()
  const [addresses, setAddresses] = useState([])
  const [modal, setModal] = useState(null)
  const [confirmDelete, setConfirmDelete] = useState(null)

  useEffect(() => {
    getAddresses(token).then(setAddresses).catch(console.error)
  }, [token])

  async function handleSaveAddress(fields, id) {
    try {
      if (id) {
        await updateAddress(token, id, fields)
        addToast('Adreça actualitzada correctament.')
      } else {
        await createAddress(token, fields)
        addToast('Adreça afegida correctament.')
      }
      const updated = await getAddresses(token)
      setAddresses(updated)
      setModal(null)
    } catch (err) {
      console.error(err)
    }
  }

  async function handleDelete(id) {
    try {
      await deleteAddress(token, id)
      setAddresses(prev => prev.filter(a => a.id !== id))
      setConfirmDelete(null)
      addToast('Adreça eliminada correctament.')
    } catch (err) {
      console.error(err)
    }
  }

  async function handleSetDefault(id) {
    try {
      await setDefaultAddress(token, id)
      const updated = await getAddresses(token)
      setAddresses(updated)
      addToast('Adreça predeterminada actualitzada.')
    } catch (err) {
      console.error(err)
    }
  }

  return (
    <>
      <div className={drawerStyles.overlay} onClick={onClose}>
        <div className={drawerStyles.drawer} onClick={e => e.stopPropagation()}>
          <div className={drawerStyles.header}>
            <h2 className={drawerStyles.title}>Configuració</h2>
            <button className={drawerStyles.closeBtn} onClick={onClose}>
              <X size={18} />
            </button>
          </div>
          <div className={drawerStyles.body}>
            <ProfileForm />
            <div className={settingsStyles.section}>
              <h3 className={settingsStyles.sectionTitle}>Adreces</h3>
              <AddressList
                addresses={addresses}
                onAdd={() => setModal({ type: 'add' })}
                onEdit={(address) => setModal({ type: 'edit', address })}
                onDelete={(id) => setConfirmDelete(id)}
                onSetDefault={handleSetDefault}
              />
            </div>
            <PasswordForm />
          </div>
        </div>
      </div>

      {modal && (
        <AddressModal
          address={modal.type === 'edit' ? modal.address : null}
          onClose={() => setModal(null)}
          onSave={handleSaveAddress}
        />
      )}

      {confirmDelete && (
        <ConfirmModal
          message="Estàs segur que vols eliminar aquesta adreça? Aquesta acció no es pot desfer."
          onConfirm={() => handleDelete(confirmDelete)}
          onCancel={() => setConfirmDelete(null)}
        />
      )}
    </>
  )
}