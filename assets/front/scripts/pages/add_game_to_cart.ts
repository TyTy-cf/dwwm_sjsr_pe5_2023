
function initButtonAddToCart() {
    const buttonAddToCart: HTMLButtonElement =
        document.querySelector('[data-buy-game]');
    if (buttonAddToCart) {
        buttonAddToCart.addEventListener('click', () => {
            const gameId: string = buttonAddToCart.getAttribute('data-buy-game');
            fetch('/ajax/add-game-to-cart/' + gameId, {method: 'GET'})
                .then((response: Response) => {
                    console.log(response);
                })
                .then();
        });
    }
}

window.addEventListener('load', () => {
    initButtonAddToCart();
});
