
function initSearchBar() {
    const searchBar: HTMLInputElement = document.querySelector('[data-search-bar]');

    if (searchBar instanceof HTMLInputElement) {
        searchBar.addEventListener('keyup', () => {
            if (searchBar.value.length >= 3) {
                console.log(searchBar.value);
                fetch('url de route')
                .then((data) => {
                    return data.json();
                })
                .then((jsonData) => {
                });
            }
        });
    }
}

window.addEventListener('load', () => {
    initSearchBar();
});
