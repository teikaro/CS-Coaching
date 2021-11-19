// Animation "Fluide" de retour en haut de page
    const btn = document.querySelector('.scroll_to_top');

    btn.addEventListener('click', () => {

        window.scrollTo({
            top: 0,
            left: 0,
            behavior: "smooth"
        })

    });

