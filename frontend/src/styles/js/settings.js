export const drawerStyles = {
  overlay: 'drawer-overlay fixed inset-0 z-50 flex justify-end',
  drawer: 'drawer w-full sm:w-[45%] max-w-[520px] h-full flex flex-col overflow-hidden',
  header: 'flex items-center justify-between px-8 py-6 shrink-0',
  title: 'drawer-title',
  closeBtn: 'drawer-close w-8 h-8 flex items-center justify-center cursor-pointer',
  body: 'flex flex-col gap-8 px-8 py-4 overflow-y-auto flex-1',
}

export const settingsStyles = {
  sectionTitle: 'settings-section-title pb-3 mb-2',
  section: 'flex flex-col gap-4',
  field: 'flex flex-col gap-1',
  label: 'settings-label',
  input: 'settings-input w-full px-4 py-2.5',
  inputError: 'settings-input error w-full px-4 py-2.5',
  error: 'settings-error',
  btn: 'settings-btn-primary px-6 py-2.5 cursor-pointer self-start',
  success: 'settings-success px-4 py-2.5',
  serverError: 'settings-server-error px-4 py-2.5',
}

export const addressStyles = {
  list: 'flex flex-col gap-3',
  card: 'address-card flex items-start gap-3 p-4 relative',
  selector: 'address-selector mt-0.5',
  selectorSelected: 'address-selector selected mt-0.5',
  info: 'flex flex-col gap-1 flex-1',
  tag: 'address-tag',
  text: 'address-info',
  actions: 'address-actions flex items-center gap-2 mt-2',
  btnEdit: 'address-btn address-btn-edit',
  btnDefault: 'address-btn address-btn-default',
  btnDelete: 'address-btn address-btn-delete',
  addCard: 'address-add-card flex items-center justify-center py-4 w-full',
  addLabel: 'address-add-label',
}

export const modalStyles = {
  overlay: 'modal-overlay fixed inset-0 z-[60] flex items-center justify-center px-4',
  card: 'modal-card w-full max-w-md px-8 py-8 flex flex-col gap-6',
  header: 'flex items-center justify-between',
  title: 'modal-title',
  closeBtn: 'drawer-close w-8 h-8 flex items-center justify-center cursor-pointer',
  form: 'flex flex-col gap-4',
  field: 'flex flex-col gap-1',
  label: 'settings-label',
  input: 'settings-input w-full px-4 py-2.5',
  inputError: 'settings-input error w-full px-4 py-2.5',
  error: 'settings-error',
  actions: 'flex gap-3 justify-end',
  btnCancel: 'address-btn address-btn-edit px-5 py-2',
  btnSubmit: 'settings-btn-primary px-6 py-2.5 cursor-pointer',
}