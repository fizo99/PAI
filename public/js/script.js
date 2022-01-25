const isCompanyCheckbox = document.getElementById('is_company')
const buyerDetailsContainer = document.getElementById('buyer-details-container')
const form = document.getElementById('invoice-form');

const templateForPrivate = document.getElementById('for-private')
const templateForCompany = document.getElementById('for-company')
const templateTableRow = document.getElementById('table-row')

const itemAddButton = document.getElementById('item-add-button')
const tableBody = document.getElementById('table-body')

const additionalInformationsField = document.getElementById('additional-informations')
const nipInput = document.getElementById('nip-input')

const totalBruttoField = document.getElementById('total-brutto')
const totalNettoField = document.getElementById('total-netto')

itemAddButton.addEventListener('click', (event) => {
    const newRow = document.createElement('tr')
    newRow.innerHTML = templateTableRow.innerHTML;
    tableBody.appendChild(newRow);
})

function handleDeleteRow(event) {
    if (tableBody.children.length === 1) {
        return
    }
    event.target.parentElement.parentElement.remove();
    handleCalculateTotal();
}

isCompanyCheckbox.addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        buyerDetailsContainer.innerHTML = templateForCompany.innerHTML;
    } else {
        // TODO: (feature/invoice-for-person-without-businnes
        //buyerDetailsContainer.innerHTML = templateForPrivate.innerHTML;
        alert("Currently we don`t support invoices for people not running a business");
        event.currentTarget.checked = true;
    }
})


function handleSave() {
    const formData = Object.fromEntries(new FormData(form).entries());
    formData.products = collectProducts();
    formData.additional_informations = additionalInformationsField.value;

    activateSpinner();
    fetch("/new_invoice", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    }).then(response => {
        deactivateSpinner()
        if (response.ok) {
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

function findNIP() {
    const nip = nipInput.value;
    if (!validateNIP(nip)) {
        alert("Invalid NIP number")
        return;
    }
    activateSpinner();
    const date = new Date(Date.now()).toISOString().substr(0, 10);
    fetch("https://wl-api.mf.gov.pl/api/search/nip/" + nip + "?date=" + date)
        .then(response => {
            if (response.ok) {
                return response.json();
            }
        }).then(result => {
            const subject = result.result.subject;
            const address = subject.residenceAddress !== null
                ? subject.residenceAddress.split(",")
                : subject.workingAddress.split(",")
            const streetPart = address[0].split(" ")
            const cityPart = address[1].split(" ")
            document.getElementById('invoice-company-name').value = subject.name;
            document.getElementById('invoice-street-name').value = streetPart.slice(0, streetPart.length - 1).join(" ");
            document.getElementById('invoice-street-nr').value = streetPart[streetPart.length - 1];
            document.getElementById('invoice-zip').value = cityPart[1];
            document.getElementById('invoice-city').value = cityPart.slice(2).join(" ");
    }).catch(err => {
        alert(err.message);
    }).finally(() => {
        deactivateSpinner();
    })
}

function handleCalculateBrutto(event) {
    const netto = event.target.value;
    const vatPercent = parseInt(event.target.parentElement.parentElement.children[4].children[0].value);
    const bruttoField = event.target.parentElement.parentElement.children[5].children[0];

    if (netto === "") {
        bruttoField.value = ""
    } else {
        const nettoNum = parseFloat(netto)
        const value = nettoNum + nettoNum * (parseInt(vatPercent) / 100.0)
        bruttoField.value = round(value);
    }
}

function handleCalculateNetto(event) {
    const brutto = event.target.value;
    const vatPercent = event.target.parentElement.parentElement.children[4].children[0].value;
    const nettoField = event.target.parentElement.parentElement.children[3].children[0];

    if (brutto === "") {
        nettoField.value = "";
    } else {
        const bruttoNum = parseFloat(brutto)
        const value = parseFloat(bruttoNum) / ((100 + parseInt(vatPercent)) / 100.0);
        nettoField.value = round(value)
    }
}

function processSave() {
    if (validateRecursive(form)) {
        handleSave();
    }
}

function highlightRed(node) {
    node.style.outline = "1px solid red";
    node.style.outlineOffset = "-1px";
    setTimeout(() => {
        node.style.outline = "";
        node.style.outlineOffset = "";
    }, 3000)
}

function validateRecursive(node) {
    if (node.tagName.toLowerCase() === "input") {
        if ((node.required && node.value === '')
            || (node.pattern && !node.value.match(node.pattern))) {
            highlightRed(node)
            return false;
        }

    } else if (node.tagName.toLowerCase() === "select") {
        if (node.required && node.value === '') {
            highlightRed(node)
            return false;
        }
    } else {
        return Array.from(node.children)
            .map(child => validateRecursive(child))
            .filter(validationResult => validationResult === false)
            .length === 0
    }
}

function handleCalculateTotal() {
    const rows = tableBody.children
    const totalBrutto = Array.from(rows)
        .map(row => [row.children[1].children[0].value,row.children[5].children[0].value])
        .filter(element => !isNaN(parseFloat(element[0])) && !isNaN(parseFloat(element[1])))
        .map(value => [parseFloat(value[0]),parseFloat(value[1])])
        .reduce(function(previousValue, currentValue, index, array) {
            return previousValue + currentValue[0] * currentValue[1];
        }, 0);

    const totalNetto = Array.from(rows)
        .map(row => [row.children[1].children[0].value,row.children[3].children[0].value])
        .filter(element => !isNaN(parseFloat(element[0])) && !isNaN(parseFloat(element[1])))
        .map(value => [parseFloat(value[0]),parseFloat(value[1])])
        .reduce(function(previousValue, currentValue, index, array) {
            return previousValue + currentValue[0] * currentValue[1];
        }, 0);

    totalBruttoField.innerText = round(totalBrutto);
    totalNettoField.innerText = round(totalNetto);
}

function round(number) {
    const temp = Number((Math.abs(number) * 100).toPrecision(15));
    return Math.round(temp) / 100 * Math.sign(number);
}

function validateNIP(nip) {
    const nipWithoutDashes = nip.replace(/-/g, "");
    const reg = /^[0-9]{10}$/;
    if (reg.test(nipWithoutDashes) === false) {
        return false;
    } else {
        const digits = ("" + nipWithoutDashes).split("");
        const checksum = (6 * parseInt(digits[0]) + 5 * parseInt(digits[1]) + 7 * parseInt(digits[2]) + 2 * parseInt(digits[3]) + 3 * parseInt(digits[4]) + 4 * parseInt(digits[5]) + 5 * parseInt(digits[6]) + 6 * parseInt(digits[7]) + 7 * parseInt(digits[8])) % 11;

        return (parseInt(digits[9]) === checksum);
    }
}