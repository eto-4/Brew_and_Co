export const homeLayout = {
  wrapper: 'min-h-screen w-full flex justify-center',
  page: 'home-page w-full sm:w-[65%] max-w-[1100px] min-h-screen',
  content: 'px-4 sm:px-12',
}

export const heroStyles = {
  section: 'flex flex-col md:flex-row items-center justify-between gap-8 sm:gap-12 pt-[5rem] sm:pt-[7rem] pb-12',
  text: 'flex flex-col gap-4 flex-1 w-full',
  title: 'hero-title',
  slogan: 'hero-slogan max-w-sm',
  imageWrap: 'flex-1 flex justify-center sm:justify-end w-full',
  image: 'hero-image w-full max-w-[600px] mt-[2rem] sm:mt-0 sm:max-w-[420px] aspect-[4/3] object-cover select-none pointer-events-none',
}

export const aboutStyles = {
  section: 'flex flex-col lg:flex-row items-center gap-8 sm:gap-12 py-12',
  text: 'flex flex-col gap-4 flex-1 w-full',
  title: 'about-title',
  body: 'about-body',
  image: 'about-image w-full max-w-[300px] sm:flex-1 sm:max-w-[380px] aspect-[3/4] object-cover select-none pointer-events-none',
}

export const stepsStyles = {
  section: 'flex flex-col gap-6 py-12',
  title: 'steps-title',
  container: 'steps-container flex flex-col gap-3 p-3 sm:p-6',
  card: 'step-card flex flex-row items-center gap-4 sm:gap-8 p-4 sm:p-5',
  cardReverse: 'step-card flex flex-row-reverse text-right items-center gap-4 sm:gap-8 p-4 sm:p-5',
  image: 'step-image w-[80px] h-[80px] sm:w-[120px] sm:h-[120px] object-cover shrink-0 pointer-events-none rounded-lg',
  text: 'flex flex-col gap-1',
  number: 'step-number',
  stepTitle: 'step-title',
  desc: 'step-desc',
}

export const productGrid = {
  title_container: 'flex items-center justify-center p-5',
  title: 'grid-title',
  section: 'pg-section p-4 sm:p-12 mt-8',
  grid: 'pg-grid-inner grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 p-4 sm:p-6',
  card: 'pg-card',
  image: 'pg-image w-full aspect-square object-cover block',
  ctaWrap: 'flex justify-center mt-8',
  cta: 'pg-cta px-10 py-3 cursor-pointer',
}

export const divider = 'home-divider w-full h-px my-4'