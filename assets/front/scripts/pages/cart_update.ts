
function initCartSizeElement() {
    const nbElementCart: HTMLDivElement = document.querySelector('.cart-elements');
    if (nbElementCart) {
        fetch('/ajax/cart-size', {method: 'GET'})
        .then((response: Response) => {
            return response.json();
        })
        .then((json: AjaxResponse) => {
            nbElementCart.innerHTML = json.nbCartElement.toString();
        });
    }
}

window.addEventListener('load', () => {
    initCartSizeElement();
});
