import { useEffect, useState } from 'react'
import { getDashboard } from '../../api/admin'
import { useAuth } from '../../hooks/useAuth'
import { dashboardStyles, adminLayout } from '../../styles/js/admin'

export default function DashboardTab() {
  const { token } = useAuth()
  const [data, setData] = useState(null)

  useEffect(() => {
    getDashboard(token).then(setData).catch(console.error)
  }, [token])

  if (!data) return <p className="admin-stat-sub">Carregant...</p>

  return (
    <div>
      <h2 className={adminLayout.sectionTitle}>Resum general</h2>

      <div className={dashboardStyles.statsGrid}>
        <div className={dashboardStyles.statCard}>
          <span className={dashboardStyles.statLabel}>Ingressos aquest mes</span>
          <span className={dashboardStyles.statValue}>
            {Number(data.total_ingressos_mes).toFixed(2)}€
          </span>
        </div>
        <div className={dashboardStyles.statCard}>
          <span className={dashboardStyles.statLabel}>Ingressos totals</span>
          <span className={dashboardStyles.statValue}>
            {Number(data.total_ingressos_alltime).toFixed(2)}€
          </span>
        </div>
        <div className={dashboardStyles.statCard}>
          <span className={dashboardStyles.statLabel}>Ordres aquest mes</span>
          <span className={dashboardStyles.statValue}>{data.ordres_mes_actual}</span>
        </div>
        <div className={dashboardStyles.statCard}>
          <span className={dashboardStyles.statLabel}>Productes populars</span>
          <span className={dashboardStyles.statValue}>{data.productes_populars.length}</span>
          <span className={dashboardStyles.statSub}>en el top</span>
        </div>
      </div>

      <div className={dashboardStyles.popularSection}>
        <p className={dashboardStyles.popularTitle}>Productes més venuts</p>
        {data.productes_populars.map((p, idx) => (
          <div key={p.id} className={dashboardStyles.popularItem}>
            <span className={dashboardStyles.popularName}>
              #{idx + 1} {p.nom}
            </span>
            <span className={dashboardStyles.popularCount}>
              {p.order_lines_count} vendes
            </span>
          </div>
        ))}
      </div>
    </div>
  )
}