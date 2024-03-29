
function initButtonAddToCart() {
    const buttonAddToCart: HTMLButtonElement =
        document.querySelector('[data-buy-game]');
    if (buttonAddToCart) {
        buttonAddToCart.addEventListener('click', () => {
            const gameId: string = buttonAddToCart.getAttribute('data-buy-game');
            fetch('/ajax/add-game-to-cart/' + gameId, {method: 'GET'})
            .then((response: Response) => {
                return response.json();
            })
            .then((json: AjaxResponse) => {
                const nbElementCart: HTMLDivElement = document.querySelector('.cart-elements');
                if (nbElementCart) {
                    nbElementCart.innerHTML = json.nbCartElement.toString();
                }
            });
        });
    }
}

window.addEventListener('load', () => {
    initButtonAddToCart();
});
