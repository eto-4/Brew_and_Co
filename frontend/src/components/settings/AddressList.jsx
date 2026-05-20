import { addressStyles } from '../../styles/js/settings'
import { Pencil, Trash2, Star } from 'lucide-react'

export default function AddressList({ addresses, onAdd, onEdit, onDelete, onSetDefault }) {
  const sorted = addresses.slice().sort((a, b) => b.predeterminada - a.predeterminada)
  const showAddTop = addresses.length >= 5

  return (
    <div className={addressStyles.list}>
      {showAddTop && (
        <button className={addressStyles.addCard} onClick={onAdd}>
          <span className={addressStyles.addLabel}>+ Afegir adreça</span>
        </button>
      )}
      {sorted.map(address => (
        <div key={address.id} className={addressStyles.card}>
          <div className={address.predeterminada ? addressStyles.selectorSelected : addressStyles.selector} />
          <div className={addressStyles.info}>
            {address.etiqueta && (
              <span className={addressStyles.tag}>{address.etiqueta}</span>
            )}
            <span className={addressStyles.text}>
              {[address.adreca, address.codi_postal, address.ciutat].filter(Boolean).join(', ')}
            </span>
            <div className={addressStyles.actions}>
              <button className={addressStyles.btnEdit} onClick={() => onEdit(address)}>
                <Pencil size={12} style={{ display: 'inline', marginRight: 4 }} />
                Editar
              </button>
              {!address.predeterminada && (
                <button className={addressStyles.btnDefault} onClick={() => onSetDefault(address.id)}>
                  <Star size={12} style={{ display: 'inline', marginRight: 4 }} />
                  Predeterminada
                </button>
              )}
              <button className={addressStyles.btnDelete} onClick={() => onDelete(address.id)}>
                <Trash2 size={12} style={{ display: 'inline', marginRight: 4 }} />
                Eliminar
              </button>
            </div>
          </div>
        </div>
      ))}
      {!showAddTop && (
        <button className={addressStyles.addCard} onClick={onAdd}>
          <span className={addressStyles.addLabel}>+ Afegir adreça</span>
        </button>
      )}
    </div>
  )
}