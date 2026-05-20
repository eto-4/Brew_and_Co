import { useState } from 'react'
import Navbar from '../components/navbar/Navbar'
import DashboardTab from '../components/admin/DashboardTab'
import ProductsTab from '../components/admin/ProductsTab'
import AiChatTab from '../components/admin/AiChatTab'
import { useAuth } from '../hooks/useAuth'
import { adminLayout } from '../styles/js/admin'
import { LayoutDashboard, Package, Bot } from 'lucide-react'

const TABS = [
  { key: 'dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { key: 'products', label: 'Productes', icon: Package },
  { key: 'ai', label: 'Assistent IA', icon: Bot },
]

export default function AdminPage() {
  const { user } = useAuth()
  const [activeTab, setActiveTab] = useState('dashboard')

    return (
    <div className={adminLayout.page}>
      <Navbar user={user} />
      <div className={adminLayout.content}>
        <div className={adminLayout.container}>
          <aside className={adminLayout.sidebar}>
            <span className={adminLayout.sidebarTitle}>Admin</span>
            {TABS.map(tab => {
              const Icon = tab.icon
              return (
                <button
                  key={tab.key}
                  className={activeTab === tab.key ? adminLayout.tabBtnActive : adminLayout.tabBtn}
                  onClick={() => setActiveTab(tab.key)}
                >
                  <Icon size={14} style={{ display: 'inline', marginRight: 8, verticalAlign: -2 }} />
                  {tab.label}
                </button>
              )
            })}
          </aside>
          <div className={adminLayout.content}>
            {activeTab === 'dashboard' && <DashboardTab />}
            {activeTab === 'products' && <ProductsTab />}
            {activeTab === 'ai' && <AiChatTab />}
          </div>
        </div>
      </div>
    </div>
  )
}