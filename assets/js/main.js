function initAlertCloseButtons(className) {
    let close = document.getElementsByClassName(className);
    // Loop through all close buttons
    for (let i = 0; i < close.length; i++) {
        // When someone clicks on a close button
        close[i].onclick = function () {

            // Get the parent of <span class="closebtn"> (<div class="alert">)
            let div = this.parentElement;

            // Set the opacity of div to 0 (transparent)
            div.style.opacity = "0";

            // Hide the div after 600ms (the same amount of milliseconds it takes to fade out)
            setTimeout(function () {
                div.style.display = "none";
            }, 600);
        }
    }
}


function initSelectDnsTypeComponent() {
    let select = document.getElementById('dnsTypeSelect');
    let createRecordLink = document.getElementById('createRecordLink');
    if (select && createRecordLink) {
        select.addEventListener('change', function () {
            let url = createRecordLink.href;
            url = url.substring(0, url.indexOf('=')) + '=' + this.value;
            createRecordLink.href = url;
            // createRecordLink.href = 'http://localhost:8000/create_new_record.php?dns_type=' + this.value;
        });
    }
}


initAlertCloseButtons('close-btn');
initSelectDnsTypeComponent();

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded');
})