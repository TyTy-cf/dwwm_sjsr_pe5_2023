
interface AjaxResponse {
    nbCartElement: number;
}

function initButtonEmptyCart() {
    const buttonAddToCart: HTMLButtonElement =
        document.querySelector('[data-empty-cart]');
    if (buttonAddToCart) {
        buttonAddToCart.addEventListener('click', () => {
            fetch('/ajax/empty-cart', {method: 'GET'})
            .then((response: Response) => {
                return response.json();
            })
            .then((json: AjaxResponse) => {
                const nbElementCart: HTMLDivElement = document.querySelector('.cart-elements');
                if (nbElementCart) {
                    nbElementCart.innerHTML = json.nbCartElement.toString();
                }
                document.location.href = '/panier';
            });
        });
    }
}

window.addEventListener('load', () => {
    initButtonEmptyCart();
});
