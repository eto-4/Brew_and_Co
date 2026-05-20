import { navbar } from '../../styles/js/navbar'
import NavBrand from './NavBrand'
import NavUserSection from './NavUserSection'

export default function Navbar({ user }) {
  return (
    <div className={navbar.outer}>
      <nav className={navbar.inner}>
        <NavBrand />
        <NavUserSection user={user} />
      </nav>
    </div>
  )
}