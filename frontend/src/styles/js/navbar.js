export const navbar = {
  outer: 'fixed top-4 left-0 right-0 z-50 flex justify-center px-4',
  inner: 'nb-inner flex items-center justify-between w-full max-w-4xl h-[60px] px-4 md:px-6 rounded-[20px]',
}

export const navBrand = {
  wrapper: 'flex items-center gap-[2px]',
  title: 'nb-brand-title font-playfair text-[22px] md:text-[30px] font-medium',
  ampersand: 'nb-brand-ampersand font-playfair text-[18px] md:text-[20px] font-medium',
}

export const navActions = {
  wrapper: 'flex items-center gap-2',
  iconBtn: 'nb-icon-btn w-[36px] h-[36px] md:w-[40px] md:h-[40px] flex items-center justify-center rounded-[10px] text-[20px] transition-colors',
  divider: 'nb-divider w-[2px] h-9',
}

export const navUser = {
  wrapper: 'nb-user-wrapper flex items-center gap-2 px-3 py-1.5 rounded-[10px] transition-colors cursor-pointer',
  avatar: 'nb-avatar w-[26px] h-[26px] rounded-full flex items-center justify-center text-[11px] font-medium',
  username: 'nb-username font-raleway text-[13px] font-medium hidden sm:block',
  chevron: 'nb-chevron text-[12px]',
  dropdown: 'nb-dropdown absolute right-0 top-[calc(100%+8px)] w-[160px] rounded-[12px] overflow-hidden',
  dropdownItem: 'nb-dropdown-item w-full text-left px-4 py-2.5 font-raleway text-[13px] transition-colors',
}

export const navGuest = {
  wrapper: 'flex items-center gap-2',
  login: 'nb-btn-login font-raleway text-[13px] px-3 py-1.5 rounded-[10px] transition-colors hidden sm:block',
  register: 'nb-btn-register font-raleway text-[13px] font-medium px-3.5 py-1.5 rounded-[10px] transition-colors',
}