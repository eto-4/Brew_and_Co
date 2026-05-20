import { heroStyles } from '../../styles/js/home'

export default function HeroSection() {
  return (
    <section className={heroStyles.section}>
      <div className={heroStyles.imageWrap}>
        <img
          src="/hero.webp"
          alt=""
          className={heroStyles.image}
          draggable={false}
        />
      </div>
      <div className={heroStyles.text}>
        <h1 className={heroStyles.title}>Brew & Co.</h1>
        <p className={heroStyles.slogan}>
          El teu racó de cafè artesà al cor de Manresa.<br />
          Encomana, espera i gaudeix — sense complicacions.
        </p>
      </div>
    </section>
  )
}