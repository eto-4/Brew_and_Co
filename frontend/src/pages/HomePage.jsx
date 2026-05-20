import Navbar from '../components/navbar/Navbar'
import HeroSection from '../components/home/HeroSection'
import AboutSection from '../components/home/AboutSection'
import StepsSection from '../components/home/StepsSection'
import ProductGrid from '../components/home/ProductGrid'
import Footer from '../components/footer/Footer'
import { homeLayout } from '../styles/js/home'
import { useAuth } from '../hooks/useAuth'

export default function HomePage() {
  const { user } = useAuth()

  return (
    <div className={homeLayout.wrapper}>
      <div className={homeLayout.page}>
        <Navbar user={user} />
        <div className={homeLayout.content}>
          <HeroSection />
          <AboutSection />
          <StepsSection />
        </div>
        <ProductGrid user={user} />
        <Footer />
      </div>
    </div>
  )
}