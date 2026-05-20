export const adminLayout = {
  page: 'admin-page min-h-screen',
  content: 'flex flex-col gap-6 px-4 sm:px-8 pt-24 pb-12 max-w-[1200px] mx-auto',
  container: 'admin-container flex',
  sidebar: 'admin-sidebar flex flex-col gap-2 p-6 w-[200px] shrink-0',
  sidebarTitle: 'admin-sidebar-title mb-2',
  tabBtn: 'admin-tab-btn w-full px-4 py-2.5',
  tabBtnActive: 'admin-tab-btn active w-full px-4 py-2.5',
  admin_content: 'admin-content flex-1 p-8 overflow-y-auto',
  sectionTitle: 'admin-section-title mb-6 text-center',
}

export const dashboardStyles = {
  statsGrid: 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8 w-full',
  statCard: 'admin-stat-card flex flex-col gap-1 p-5',
  statLabel: 'admin-stat-label',
  statValue: 'admin-stat-value',
  statSub: 'admin-stat-sub',
  popularSection: 'flex flex-col gap-1',
  popularTitle: 'admin-popular-title mb-3',
  popularItem: 'admin-popular-item flex items-center justify-between py-2',
  popularName: 'flex-1',
  popularCount: 'admin-popular-count',
}

export const productsAdminStyles = {
  grid: 'grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8 w-full',
  card: 'admin-product-card flex items-center gap-4 p-4',
  cardUnavailable: 'admin-product-card admin-product-unavailable flex items-center gap-4 p-4',
  image: 'w-[56px] h-[56px] object-cover rounded-8px shrink-0',
  info: 'flex flex-col gap-0.5 flex-1',
  name: 'admin-product-name',
  price: 'admin-product-price',
  switch: 'admin-switch shrink-0',
  switchSlider: 'admin-switch-slider',
}

export const chatStyles = {
  container: 'chat-container flex flex-col h-[600px]',
  header: 'chat-header flex items-center gap-3 px-5 py-4 shrink-0',
  headerDot: 'chat-header-status',
  headerName: 'chat-header-name',
  messages: 'chat-messages flex flex-col gap-4 flex-1 p-5 overflow-y-auto',
  rowAi: 'flex items-start gap-3',
  rowUser: 'flex items-start flex-row-reverse gap-3',
  avatarAi: 'chat-avatar-ai w-7 h-7 flex items-center justify-center text-[10px] shrink-0',
  avatarUser: 'chat-avatar-user w-7 h-7 flex items-center justify-center text-[10px] shrink-0',
  bubbleAi: 'chat-bubble-ai px-4 py-3 max-w-[80%]',
  bubbleUser: 'chat-bubble-user px-4 py-3 max-w-[80%]',
  typing: 'chat-typing px-4 py-2',
  footer: 'chat-footer flex items-end gap-3 p-4 shrink-0',
  input: 'chat-input flex-1 px-4 py-2.5',
  sendBtn: 'chat-send-btn px-4 py-2.5 shrink-0',
}