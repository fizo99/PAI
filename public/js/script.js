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
    formData.items = collectItems()
    formData.additional_informations = additionalInformationsField.value;

    fetch("/new_invoice", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    }).then(response =>  {
        return response.text()
    }).then(result => {
        console.log(result)
    })
}

function addInvoice(formData) {

}

function collectItems() {
    const items = []
    const tableRows = Array.from(tableBody.children);
    tableRows.forEach(row => {
        const inputs = Array.from(row.children)
            .map(td => td.firstChild)
            .filter(elem => elem.tagName && elem.tagName.toLowerCase() === "input")
            .map(input => input.value)
        items.push({
            product_name: inputs[0],
            quantity: inputs[1],
            unit: inputs[2],
            netto: inputs[3],
            percent: inputs[4],
            brutto: inputs[5]
        })
    })
    return items;
}