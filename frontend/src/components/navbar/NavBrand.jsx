import { Link } from 'react-router-dom'
import { navBrand } from '../../styles/js/navbar'

export default function NavBrand() {
  return (
    <Link to="/" className={navBrand.wrapper}>
      <span className={navBrand.title}>Brew</span>
      <span className={navBrand.ampersand}>&</span>
      <span className={navBrand.title}>Co.</span>
    </Link>
  )
}