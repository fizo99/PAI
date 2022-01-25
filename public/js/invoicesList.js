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
    if(event.target.value === "delete"){
        deleteInvoice(event);
    }else if(event.target.value === "download_doc"){
        downloadInvoice(event);
    }
}

function updateInvoiceState(event) {
    const newState = event.target.value;

    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    activateSpinner();
    fetch("/update_state/" + invoiceId, {
        method: "PUT",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            newState: newState
        })
    }).then(response =>  {
        if(response.ok){
            return response.text()
        } else {
            throw new Error('Something went wrong' + response.text())
        }
    }).then(result => {
        event.target.parentElement.value = newState;
    }).catch(err => {
        alert(err.message)
    }).finally(() => {
        deactivateSpinner()
    })
}

function deleteInvoice(event) {
    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    activateSpinner();
    fetch("/delete_invoice/" + invoiceId, {
        method: "DELETE",
    }).then(response =>  {
        //deactivateSpinner()
        if(response.ok){
            return response.text()
        } else {
            throw new Error('Something went wrong' + response.text())
        }
    }).then(result => {
        window.location.reload();
    }).catch(err => {
        alert(err.message)
    }).finally(() => {
        deactivateSpinner()
    })
}

function downloadInvoice(event) {
    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    activateSpinner();
    fetch("/download_invoice/" + invoiceId, {
        method: "GET",
    }).then(response =>  {
        //deactivateSpinner()
        if(response.ok){
            return response.blob()
        } else {
            throw new Error('Something went wrong' + response.text())
        }
    }).then(result => {
        downloadDoc(result)
    }).catch(err => {
        alert(err.message);
    }).finally(() => {
        deactivateSpinner()
    })
}

function downloadDoc(doc) {
    var url = URL.createObjectURL(doc);
    var tempLink = document.createElement('a');
    tempLink.href = url;
    tempLink.download = "invoice.docx";
    document.body.appendChild(tempLink);
    tempLink.click();
    tempLink.remove();
}
