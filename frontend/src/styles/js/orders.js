export const ordersLayout = {
  page: 'orders-page min-h-screen',
  content: 'orders-content flex flex-col gap-6 px-4 sm:px-8 pt-24 pb-12',
  container: 'orders-container flex flex-col',
  tabs: 'orders-tabs flex items-end gap-1 px-6 pt-4',
  tab: 'orders-tab px-5 py-2.5',
  tabActive: 'orders-tab active px-5 py-2.5',
  tabContent: 'orders-tab-content flex flex-col gap-4 p-6',
  footer: 'orders-footer flex justify-between items-center px-6 py-4',
}

export const orderCardStyles = {
  card: 'order-card flex flex-col gap-2 p-4 relative',
  header: 'flex items-center justify-between',
  id: 'order-card-id',
  products: 'order-card-products',
  footer: 'order-card-footer flex items-center justify-between',
  total: 'order-card-total',
  actions: 'order-card-actions flex items-center gap-2',
  btnEdit: 'order-btn order-btn-edit',
  btnProcess: 'order-btn order-btn-process',
}

export const ordersEmptyStyles = {
  wrapper: 'flex flex-col items-center justify-center gap-4 py-16',
  text: 'orders-empty',
  btn: 'orders-empty-btn px-6 py-3',
}

export const orderModalStyles = {
  overlay: 'order-modal-overlay fixed inset-0 z-50 flex items-center justify-center p-6',
  modal: 'order-modal w-full max-w-[900px] max-h-[85vh] flex flex-col overflow-hidden',
  header: 'order-modal-header flex items-center justify-between px-8 py-5 shrink-0',
  title: 'order-summary-title',
  body: 'flex flex-1 overflow-hidden',
  gridSection: 'flex-1 flex flex-col p-5 overflow-hidden',
  gridBg: 'order-modal-grid-bg flex-1 p-4 overflow-hidden flex flex-col gap-3',
  categoryLabel: 'category-label mb-1',
  gridInner: 'order-modal-grid-inner p-3 overflow-y-auto flex-1',
  grid: 'grid grid-cols-1 sm:grid-cols-2 gap-3',
  summarySection: 'order-modal-summary-section w-[280px] shrink-0 flex flex-col',
  summaryHeader: 'order-modal-summary-header px-6 py-5',
  summaryTitle: 'order-summary-title',
  summaryBody: 'flex flex-col gap-2 px-6 py-4 flex-1 overflow-y-auto',
  summaryDivider: 'order-summary-divider w-full h-px my-2',
  summaryItem: 'flex items-center justify-between gap-2',
  summaryItemName: 'order-summary-item-name',
  summaryItemPrice: 'order-summary-item-price',
  summaryTotalRow: 'flex items-center justify-between',
  summaryTotalLabel: 'order-summary-total-label',
  summaryTotalValue: 'order-summary-total-value',
  summaryFooter: 'order-modal-summary-footer px-6 py-4 flex flex-col gap-2 shrink-0',
  saveBtn: 'order-modal-save-btn px-5 py-2.5 w-full',
  deleteBtn: 'order-modal-delete-btn px-5 py-2.5 w-full',
}

export const productCardStyles = {
  card: 'product-card flex overflow-hidden',
  cardUnavailable: 'product-card unavailable flex overflow-hidden',
  image: 'product-card-image w-[72px] h-[72px] shrink-0',
  imageGray: 'product-card-image grayscale w-[72px] h-[72px] shrink-0',
  info: 'flex flex-col justify-between flex-1 p-3',
  name: 'product-card-name',
  bottom: 'flex items-center justify-between',
  qtyRow: 'flex items-center gap-2',
  qtyBtn: 'product-qty-btn',
  qtyValue: 'product-qty-value',
  price: 'product-card-price',
}