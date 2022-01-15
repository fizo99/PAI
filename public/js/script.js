const isCompanyCheckbox = document.getElementById('is_company')
const buyerDetailsContainer = document.getElementById('buyer-details-container')
const form = document.getElementById('invoice-form');

const templateForPrivate = document.getElementById('for-private')
const templateForCompany = document.getElementById('for-company')
const templateTableRow = document.getElementById('table-row')

const itemAddButton = document.getElementById('item-add-button')
const tableBody = document.getElementById('table-body')

const additionalInformationsField = document.getElementById('additional-informations')

itemAddButton.addEventListener('click', (event) => {
    const newRow = document.createElement('tr')
    newRow.innerHTML = templateTableRow.innerHTML;
    tableBody.appendChild(newRow);

    const deleteButtons = document.getElementsByClassName("fas fa-trash")
    deleteButtons[deleteButtons.length - 1].addEventListener('click', (e) => {
        if(tableBody.children.length === 1) {
            return
        }
        e.target.parentElement.parentElement.remove();
    })
})

isCompanyCheckbox.addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        buyerDetailsContainer.innerHTML = templateForCompany.innerHTML;
    } else {
        buyerDetailsContainer.innerHTML = templateForPrivate.innerHTML;
    }
})
function handleSave() {
    const formData = Object.fromEntries(new FormData(form).entries());
    formData.products = collectProducts()
    formData.additional_informations = additionalInformationsField.value;

    activateSpinner();
    fetch("/new_invoice", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    }).then(response =>  {
        deactivateSpinner()
        if(response.ok){
            return response.text()
        } else {
            throw new Error('Something went wrong' + response.text())
        }
    }).then(result => {
        alert('Success!')
    }).catch(err => {
        alert(err.message)
    })
}

function activateSpinner() {
    const spinner = document.createElement('div');
    const main = document.querySelector('main')
    spinner.id = 'loading';
    main.insertBefore(spinner, main.firstChild)
}

function deactivateSpinner() {
    document.getElementById('loading').remove();
}

function collectProducts() {
    const products = []
    const tableRows = Array.from(tableBody.children);
    tableRows.forEach(row => {
        const inputs = Array.from(row.children)
            .map(td => td.firstChild)
            .filter(elem => elem.tagName && elem.tagName.toLowerCase() === "input")
            .map(input => input.value)
        products.push({
            product_name: inputs[0],
            quantity: inputs[1],
            unit: inputs[2],
            netto: inputs[3],
            percent: inputs[4],
            brutto: inputs[5]
        })
    })
    return products;
}
