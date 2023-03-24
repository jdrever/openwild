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
    // TODO - trigger speciesName changed? (so autocomplete list updates)
    // TODO - focus on speciesName? (so user can hit enter to search)
}

currentFocus = -1;

speciesName.addEventListener("keydown", function(e) {
    if (currentFocus >= autocompleteContainer.children.length) {
        currentFocus = autocompleteContainer.children.length - 1;
    }

    switch (e.code) {
        case "ArrowUp":
            if (autocompleteContainer.children.length != 0 && currentFocus > 0) {
                autocompleteFocus(currentFocus, --currentFocus);
            }
            
            break;
    
        case "ArrowDown":
            if (autocompleteContainer.children.length != 0 && currentFocus < autocompleteContainer.children.length - 1) {
                autocompleteFocus(currentFocus, ++currentFocus);
            }
            break;

        case "Escape":
            speciesName.blur();
            autocompleteContainer.style.display = "none";
            break;

        case "Enter":
            // if an autocomplete item is selected, capture input, enter that item (deselect it) so the user can enter again to search w it
            if (currentFocus != -1) {
                e.preventDefault();
                autocomplete(autocompleteContainer.children[currentFocus].innerHTML);
                autocompleteContainer.children[currentFocus].classList.remove("autocomplete-focus");
                currentFocus = -1;
            }
            break;
        default:
            break;
    }
});

function autocompleteFocus(prev, current) {
    if (prev != -1) {
        autocompleteContainer.children[prev].classList.remove("autocomplete-focus");
    }

    autocompleteContainer.children[current].classList.add("autocomplete-focus");
}
