function activateSpinner() {
    const spinner = document.createElement('div');
    const main = document.querySelector('main')
    spinner.id = 'loading';
    main.insertBefore(spinner, main.firstChild)
}

function deactivateSpinner() {
    document.getElementById('loading').remove();
}