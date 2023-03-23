let speciesName = document.getElementById("speciesName");
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
            console.log("Searching for "+speciesNameType+" '"+userInput+"'");
            return response.text();
        }).then(function (html) {
            autocompleteContainer.style.display = "block";
            autocompleteContainer.innerHTML = html;
        }).catch(function (err) {
            // There was an error
            console.warn('Something went wrong.', err);
        });
    } else {
        // Hide/empty autocomplete dropdown if user clears speciesName input
        autocompleteContainer.innerHTML = "";
    }

});

// Show the autocompletecontainer if clicked on the speciesName input box 
speciesName.addEventListener('focus', function() {
    autocompleteContainer.style.display = "block";
});

// If user clicks on document other than the input or the autocompletecontainer, hide the container 
document.addEventListener("click", function(e) {
    if (e.target != autocompleteContainer && e.target != speciesName) {
        autocompleteContainer.style.display = "none";
    }
});

function autocomplete(string) {
    console.log(string + " clicked")
    speciesName.value = string;
    // TODO - trigger speciesName changed
}

// keyinput
// up - if autocomplete list shown, move active selected up
// down - if autocomplete list shown, move active selected down
// enter - if autocomplete list shown, click on active selected
