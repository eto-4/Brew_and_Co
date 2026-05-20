import { navActions } from '../../styles/js/navbar'
import UserMenu from './UserMenu'
import GuestLinks from './GuestLinks'
import { ShoppingBag } from 'lucide-react'
import { Link } from 'react-router-dom'

export default function NavUserSection({ user }) {
  return (
    <div className={navActions.wrapper}>
      {user ? (
        <>
          <Link to="/orders" className={navActions.iconBtn} aria-label="Les meves ordres">
            <ShoppingBag size={20} />
          </Link>
          <div className={navActions.divider} />
          <UserMenu user={user} />
        </>
      ) : (
        <GuestLinks />
      )}
    </div>
  )
}