import { stepsStyles, divider } from '../../styles/js/home'

const steps = [
  {
    number: '01',
    title: 'Tria els teus productes',
    desc: 'Explora el catàleg, afegeix el que vulguis i ajusta les quantitats al teu gust.',
    image: '/step1.webp',
    reverse: false,
  },
  {
    number: '02',
    title: 'Confirma la comanda',
    desc: 'Revisa el resum, aplica un codi de descompte si en tens i tria com vols pagar.',
    image: '/step2.webp',
    reverse: true,
  },
  {
    number: '03',
    title: 'Rep-ho a casa',
    desc: 'Segueix l\'estat de la teva comanda en temps real fins que arribi a la teva porta.',
    image: '/step3.webp',
    reverse: false,
  },
]

export default function StepsSection() {
  return (
    <>
      <div className={divider} />
      <section className={stepsStyles.section}>
        <h2 className={stepsStyles.title}>Com funciona</h2>
        <div className={stepsStyles.container}>
          {steps.map((step) => (
            <div
              key={step.number}
              className={step.reverse ? stepsStyles.cardReverse : stepsStyles.card}
            >
              <img
                src={step.image}
                alt=""
                className={stepsStyles.image}
                draggable={false}
              />
              <div className={stepsStyles.text}>
                <span className={stepsStyles.number}>Pas {step.number}</span>
                <span className={stepsStyles.stepTitle}>{step.title}</span>
                <span className={stepsStyles.desc}>{step.desc}</span>
              </div>
            </div>
          ))}
        </div>
      </section>
    </>
  )
}