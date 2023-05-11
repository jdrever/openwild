backButton=document.querySelector('#backButton');
// Get the password input
if (backButton)
{
    backButton.addEventListener('click', function(event)
    {
        history.back();
    });
}
