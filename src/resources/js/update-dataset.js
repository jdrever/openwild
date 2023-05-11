function listenForRefreshClicks(parent)
{
    parent.querySelectorAll("[data-refresh='true']").forEach(item => {
        item.addEventListener('click', event => {
            pageNumber=item.hasAttribute('data-page') ? item.dataset.page : 1;
            // Try to update dataset and if item isn't an INPUT (the search selection checkboxes) then prevent default
            if (updateDataset(pageNumber) && item.tagName != "INPUT") {
                console.log("should be preventing default");
                event.preventDefault();
            }
        })
    })
}

// At the start, add listeners for clicks for all refresh items
listenForRefreshClicks(document);

function updateDataset(pageNumber) {
    console.log("showSpinner");
    showSpinner();
    updateUrl = getUpdateUrl(pageNumber);
    console.log(updateUrl);
    return fetch(updateUrl,
        {
            credentials: 'include',
        }).then(function (response) {
        console.log("The API call was successful!");
        return response.text();
    }).then(function (html) {
        var dataTable = document.querySelector('#data-table');

        // Refresh data-table html
        dataTable.innerHTML = html;

        // Reload map if necessary
        if (document.getElementById('map-container')) {
            loadMap();
        }

        // Re-add listeners for data-refresh items that got reloaded (pagination links)
        listenForRefreshClicks(dataTable);

        return true;
    }).catch(function (err) {
        // There was an error
        console.warn('Something went wrong.', err);
        return false;
    });
}

function showSpinner() {
    var elem = document.querySelector('#data-table');
    elem.innerHTML = '<div class="text-center"><button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading... </button></div>';
}



