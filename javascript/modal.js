// openModalButtons pentru posibilitatea de a avem mai multe cai de a deschide un modal
// querySelectorAll selecteaza toate elementele care au modal-target
const openModalButtons = document.querySelectorAll('[data-modal-target]') 
const closeModalButtons = document.querySelectorAll('[data-close-button]')
//overlay => to show and hide the modal
const overlay = document.getElementById('overlay')

openModalButtons.forEach(button => {
  // evenimentul se va intimpla de fiecare data cum dam click pe buton
  button.addEventListener('click', () => {
    //dataset allows to access all of the data attributes
    //(button.dataset.modalTarget) = ia id-ul modal-ului
    const modal = document.querySelector(button.dataset.modalTarget)
    openModal(modal) //functia de mai jos, care deschide modal
  })
})

overlay.addEventListener('click', () => {
  const modals = document.querySelectorAll('.modal.active')
  modals.forEach(modal => {
    closeModal(modal)
  })
})

closeModalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const modal = button.closest('.modal')
    closeModal(modal)
  })
})

function openModal(modal) {
  if (modal == null) return
  modal.classList.add('active')
  overlay.classList.add('active')//atita timp cat este deschis un modal , overlay-ul la fel trebuie se fie activ
}

function closeModal(modal) {
  if (modal == null) return
  modal.classList.remove('active')
  overlay.classList.remove('active')
}