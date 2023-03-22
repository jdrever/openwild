let speciesName=document.getElementById("speciesName");

let autocompleteContainer = document.getElementById("autocomplete-container");

speciesName.addEventListener('input', function (evt)
{
    const userInput = encodeURIComponent(this.value);

    // Get and format species name type
    let speciesNameType = document.querySelector('input[name="speciesNameType"]:checked').value + "Name";

    // Get and capitalise species group
    // TODO - bryophytes doesn't work
    let speciesGroup = document.querySelector('input[name="speciesGroup"]:checked').value;
    speciesGroup = speciesGroup[0].toUpperCase() + speciesGroup.slice(1)

    updateUrl='/species-autocomplete/'+userInput+'/'+speciesNameType+'/'+speciesGroup+'/';

    if (userInput.length > 0) {
        fetch(updateUrl).then(function (response) {
            // The API call was successful!
            return response.text();
        }).then(function (html) {
            autocompleteContainer.style.display = "block";
            autocompleteContainer.innerHTML = html;
    }).catch(function (err) {
        // There was an error
        console.warn('Something went wrong.', err);
    });
}});

// speciesNameInput gain focus - show autocomplete list
// speciesNameInput lose focus - hide autocomplete list

speciesName.addEventListener('focus', function (evt) {
    autocompleteContainer.style.display = "block";
});

speciesName.addEventListener('focusout', function (evt) {
    //autocompleteContainer.style.display = "none";
    console.log("input focusout");
});

autocompleteContainer.addEventListener('focusout', function (evt) {
    //autocompleteContainer.style.display = "none";
    console.log("ac focusout");
});

document.getElementById("search-container").addEventListener('focusout', function (evt) {
    //autocompleteContainer.style.display = "none";
    console.log("container focusout");
});

document.addEventListener("click", function (e) {
    if (e.target != autocompleteContainer && e.target != speciesName) {
        autocompleteContainer.style.display = "none";
    }
});

// autocomplete-item
// on hover - highlight w css
// on click - set text to value

function autocomplete(string) {
    console.log(string + " clicked")
    speciesName.value = string;
    //autocompleteContainer.style.display = "none";
}

// keyinput
// up - if autocomplete list shown, move active selected up
// down - if autocomplete list shown, move active selected down
// enter - if autocomplete list shown, click on active selected

function copy(string) {
    navigator.clipboard.writeText(string);
}