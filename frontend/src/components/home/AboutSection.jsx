import { aboutStyles, divider } from '../../styles/js/home'

export default function AboutSection() {
  return (
    <>
      <div className={divider} />
      <section className={aboutStyles.section}>
        <div className={aboutStyles.text}>
          <h2 className={aboutStyles.title}>Fet amb calma, servit amb cura</h2>
          <p className={aboutStyles.body}>
            A Brew & Co. creiem que un bon cafè és molt més que una beguda.
            És el ritual del matí, la pausa del migdia, la conversa de la tarda.
            Seleccionem grans d'origen, els torrem amb cura i els portem fins a casa teva
            amb la mateixa dedicació de sempre.
          </p>
        </div>
        <img
          src={`${import.meta.env.BASE_URL}about.webp`}
          alt=""
          className={aboutStyles.image}
          draggable={false}
        />
      </section>
    </>
  )
}