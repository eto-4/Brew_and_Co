import { modalStyles } from '../../styles/js/settings'

export default function ConfirmModal({ message, onConfirm, onCancel }) {
  return (
    <div className={modalStyles.overlay} onClick={onCancel}>
      <div className={modalStyles.card} style={{ maxWidth: 360 }} onClick={e => e.stopPropagation()}>
        <div className={modalStyles.header}>
          <h2 className={modalStyles.title}>Confirmar acció</h2>
        </div>
        <p className="settings-label" style={{ fontWeight: 400, color: 'var(--color-text)' }}>
          {message}
        </p>
        <div className={modalStyles.actions}>
          <button className={modalStyles.btnCancel} onClick={onCancel}>
            Cancel·lar
          </button>
          <button
            className="address-btn address-btn-delete px-5 py-2"
            onClick={onConfirm}
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  )
}