
interface AjaxHtmlResponse {
    html: string;
}

function initSearchBar() {
    const searchBar: HTMLInputElement = document.querySelector('[data-search-bar]');

    if (searchBar instanceof HTMLInputElement) {
        searchBar.addEventListener('keyup', () => {
            if (searchBar.value.length >= 3) {
                fetch('/ajax/searched-item/' + searchBar.value, {method: 'GET'})
                .then((data) => {
                    return data.json();
                })
                .then((jsonData: AjaxHtmlResponse) => {
                    const responseElement: HTMLDivElement = document.querySelector('.search-response-container');
                    if (responseElement) {
                        responseElement.innerHTML = jsonData.html;
                    }
                });
            }
        });
    }
}

window.addEventListener('load', () => {
    initSearchBar();
});
