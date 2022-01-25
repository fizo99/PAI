function activateSpinner() {
    const spinner = document.createElement('div');
    const main = document.querySelector('main')
    spinner.id = 'loading';
    main.insertBefore(spinner, main.firstChild)
}

function prepareSpinnerContent(){
    const content = document.createElement("i")
    content.id = "async-result"
    return content;
}

function spinnerSucces() {
    const check = prepareSpinnerContent();
    check.className = "fas fa-check success";

    deactivateSpinnerWithContent(check);
}

function spinnerFailure() {
    const x = prepareSpinnerContent();
    x.className = "fas fa-times failure";

    deactivateSpinnerWithContent(x);
}

const delay = ms => new Promise(res => setTimeout(res, ms));

function deactivateSpinnerWithContent(spinnerContent) {
    const successBox = document.getElementById('loading');
    successBox.style.animation = 'none';
    successBox.style.border = 'none';
    successBox.offsetHeight;

    successBox.appendChild(spinnerContent);

    setTimeout(async () => {
        spinnerContent.className += " fade";
        await delay(1000);
        successBox.remove();
    },1000);
}


function deactivateSpinner() {
    document.getElementById("loading").remove();
}
