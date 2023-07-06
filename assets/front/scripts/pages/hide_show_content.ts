
function initHideShowContent() {
    const elements: NodeListOf<HTMLElement> = document.querySelectorAll('[data-hidden]');
    if (elements) {
        for (const element of elements) {
            // Pour chaque élément data-hidden : on ajoute l'event au clic dessus
            element.addEventListener('click', () => {
                // On récupère la valeur de son data-attribute : data-hidden
                const dataAttrValue: string = element.getAttribute('data-hidden');
                // On récupère les autres éléments avec le "data-hidden" ayant la même valeur que l'élément courant
                const siblingElements: NodeListOf<HTMLElement> = document.querySelectorAll('[data-hidden="'+dataAttrValue+'"]');
                // Pour chaque élément ayant la même data-hidden
                for (const siblingElement of siblingElements) {
                    // On toggle la classe "d-none" sur l'autre élément
                    siblingElement.classList.toggle('d-none');
                }
            });
        }
    }
}

window.addEventListener('load', () => {
    initHideShowContent();
});
