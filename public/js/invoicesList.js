const list = document.getElementById('items-table-body')
const searchBar = document.querySelector('#search-bar input')

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

function deleteInvoice(event) {
    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    // activateSpinner();
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
    })
}

function downloadInvoice(event) {
    const row = event.target.parentElement.parentElement;
    const rowChildren = row.children;
    const invoiceId = rowChildren[rowChildren.length - 1].value

    // activateSpinner();
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
