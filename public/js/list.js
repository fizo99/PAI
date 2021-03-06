const list = document.getElementById('items-table-body')
const searchBar = document.querySelector('#input-search-invoices')

document.addEventListener('DOMContentLoaded', (event) => {
    const listClone = document.getElementById('items-table-body').cloneNode(true)
    handleSearch = function () {
        const valueToLookFor = searchBar.value.toLowerCase()
        while (list.firstChild) {
            list.removeChild(list.lastChild);
        }
        Array.from(listClone.childNodes)
            .filter(node => node.nodeName.toLowerCase() === "tr")
            .filter(node => node.textContent.toLowerCase().includes(valueToLookFor))
            .forEach(node => list.appendChild(node.cloneNode(true)))
    }
    searchBar.addEventListener('keyup', handleSearch)
});

function handleAction(event) {
    if (event.target.value === "delete") {
        deleteInvoice(event);
    } else if (event.target.value === "download_doc") {
        downloadInvoice(event);
    }
}

function updateInvoiceState(event) {
    const newState = event.target.value;

    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    activateSpinner();
    fetch("/invoice/" + invoiceId, {
        method: "PUT",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            newState: newState
        })
    }).then(response => {
        if (response.ok) {
            return response.text()
        } else {
            throw new Error('Something went wrong' + response.text())
        }
    }).then(result => {
        event.target.parentElement.value = newState;
        spinnerSucces();
    }).catch(err => {
        console.log(err.message)
        spinnerFailure();
    })
}

function deleteInvoice(event) {
    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    activateSpinner();
    fetch("/invoice/" + invoiceId, {
        method: "DELETE",
    }).then(response => {
        if (response.ok) {
            return response.text()
        } else {
            throw new Error('Something went wrong' + response.text())
        }
    }).then(result => {
        spinnerSucces()
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }).catch(err => {
        spinnerFailure();
        console.log(err.message);
    })
}

function downloadInvoice(event) {
    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    activateSpinner();
    fetch("/invoice/" + invoiceId, {
        method: "GET",
    }).then(response => {
        if (response.ok) {
            return response.blob()
        } else {
            throw new Error('Something went wrong' + response.text())
        }
    }).then(result => {
        deactivateSpinner();
        downloadDoc(result);
    }).catch(err => {
        spinnerFailure();
        console.log(err.message);
    })
}

function downloadDoc(doc) {
    const url = URL.createObjectURL(doc);
    const tempLink = document.createElement('a');
    tempLink.href = url;
    tempLink.download = "invoice.docx";
    document.body.appendChild(tempLink);
    tempLink.click();
    tempLink.remove();
}
