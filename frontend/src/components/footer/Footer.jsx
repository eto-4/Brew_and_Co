import { footerStyles } from '../../styles/js/footer'

export default function Footer() {
  return (
    <footer className={footerStyles.footer}>
      <div className={footerStyles.inner}>
        <div className={footerStyles.top}>
          <span className={footerStyles.brand}>
            Brew <span className={footerStyles.amp}>&</span> Co.
          </span>
          <p className={footerStyles.tagline}>
            Cafè artesà fet amb calma,<br />servit amb cura.
          </p>
        </div>
        <div className={footerStyles.divider} />
        <div className={footerStyles.bottom}>
          <span className={footerStyles.copy}>
            © {new Date().getFullYear()} Brew & Co. Tots els drets reservats.
          </span>
        </div>
      </div>
    </footer>
  )
}