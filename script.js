document.addEventListener('DOMContentLoaded', function () {
    function handleButtonClick(event) {
        var outputDiv = document.getElementById('output');
        outputDiv.textContent = ''; // Clear previous output

        var formData = new FormData();
        formData.append('action', event.target.id);

        // Send an AJAX request to the PHP script
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'crawler.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                outputDiv.textContent = xhr.responseText;
            } else {
                console.error('Request failed. Status: ' + xhr.status);
            }
        };

        xhr.send(formData);
    }

    var crawlButton = document.getElementById('crawlButton');
    crawlButton.addEventListener('click', handleButtonClick);

    var searchButton = document.getElementById('searchButton');
    searchButton.addEventListener('click', handleButtonClick);
});
