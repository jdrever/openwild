let siteName = document.getElementById("siteName");
let autocompleteContainer = document.getElementById("autocomplete-container");

siteName.addEventListener('input', function (evt) {

    const userInput = encodeURIComponent(this.value);

    updateUrl = '/sites-autocomplete/' + userInput;

    if (userInput.length > 0) {
        fetch(updateUrl).then(function (response) {
            // The API call was successful!
            console.log("Searching for site "+userInput+"'");
            return response.text();
        }).then(function (html) {
            autocompleteContainer.style.display = "block";
            autocompleteContainer.innerHTML = html;
        }).catch(function (err) {
            // There was an error
            console.warn('Something went wrong.', err);
        });
    } else {
        // Hide/empty autocomplete dropdown if user clears siteName input
        autocompleteContainer.innerHTML = "";
    }
});
  
// Show the autocompletecontainer if clicked on the siteName input box 
siteName.addEventListener('focus', function() {
    autocompleteContainer.style.display = "block";
});

// If user clicks on document other than the input or the autocompletecontainer, hide the container 
document.addEventListener("click", function(e) {
    if (e.target != autocompleteContainer && e.target != siteName) {
        autocompleteContainer.style.display = "none";
    }
});

function autocomplete(string) {
    console.log(string + " clicked")
    siteName.value = string;
}

currentFocus = -1;

siteName.addEventListener("keydown", function(e) {
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
            siteName.blur();
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