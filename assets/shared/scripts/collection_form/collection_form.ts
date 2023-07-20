
function initCollectionForm(): void {
    const buttonsAddForm: NodeListOf<HTMLButtonElement> = document.querySelectorAll('[data-btn-selector]');
    if (buttonsAddForm) {
        buttonsAddForm.forEach((btnElt) => {
            btnElt.addEventListener('click', () => {
                const dataValueSelector: string = btnElt.getAttribute('data-btn-selector');
                let list: HTMLElement = document.querySelector('[data-list-selector="'+dataValueSelector+'"]');
                let counter: number = list.children.length;
                let newWidget: string = list.getAttribute('data-prototype');
                newWidget = newWidget.replace(/__name__/g, counter.toString());
                newWidget = newWidget.replace('mb-3', 'mb-3 w-100');

                const icon = createIcon(counter);

                counter++;
                list.setAttribute('widget-counter', counter.toString());

                let newDiv = mainDivForm();
                newDiv.innerHTML = newWidget;
                newDiv.insertAdjacentElement('afterbegin', icon)

                list.appendChild(newDiv);
                icon.addEventListener('click', () => {
                    newDiv.innerHTML = '';
                    newDiv.remove();
                });
            });
        });
    }
}

function initButtonCollectionForm() {
    const collectionForm: NodeListOf<HTMLDivElement> = document.querySelectorAll('[data-list-selector]');
    if (collectionForm) {
        for (const form of collectionForm) {
            let i: number = 0;
            const savedChildren: string[] = [];
            for (const child of form.children) {
                child.classList.add('w-100');
                savedChildren.push(child.outerHTML);
            }
            form.innerHTML = '';

            for (const childSaved of savedChildren) {
                const divForm = mainDivForm();
                const icon: HTMLElement = createIcon(i);
                divForm.innerHTML = childSaved;
                divForm.insertAdjacentElement('afterbegin', icon);
                i++;
                form.appendChild(divForm);
                icon.addEventListener('click', () => {
                    divForm.innerHTML = '';
                    divForm.remove();
                });
            }
        }
    }
}

function mainDivForm() {
    let newDiv: HTMLDivElement = document.createElement('div');
    newDiv.classList.add('d-flex');
    return newDiv;
}

function createIcon(counter: number): HTMLElement {
    const icon: HTMLElement = document.createElement('i');
    icon.setAttribute('data-delete-form', counter.toString());
    icon.classList.add('fa');
    icon.classList.add('fa-trash');
    icon.classList.add('icon-click');
    icon.classList.add('me-3');
    icon.classList.add('mt-1');
    icon.classList.add('fa-2x');
    return icon;
}

window.addEventListener('load', () => {
    initCollectionForm();
    initButtonCollectionForm();
});


