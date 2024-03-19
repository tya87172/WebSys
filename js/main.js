document.addEventListener("DOMContentLoaded", function ()
{
// Code to be executed when the DOM is ready (i.e. the document is
// fully loaded):
activateMenu();
registerEventListeners(); // You need to write this function...
});
function registerEventListeners(){
    document.querySelector('.img-poodle').addEventListener('click', function(){
        triggerPop('images/poodle_large.jpg');
    });
    document.querySelector('.img-chihuahua').addEventListener('click', function(){
        triggerPop('images/chihuahua_large.jpg');
    });
    document.querySelector('.img-tabby').addEventListener('click', function(){
        triggerPop('images/tabby_large.jpg');
    });
    document.querySelector('.img-calico').addEventListener('click', function(){
        triggerPop('images/calico_large.jpg');
    });
    

}
function activateMenu()
{
    //document.querySelectorAll is a method that returns a NodeList containing all elements 
    //that match the specified CSS selector, in this case, 'nav a'.
    const navLinks = document.querySelectorAll('nav a');
    console.log('Current location.href:', location.href);
    //loop through the navLinks list
    navLinks.forEach(link =>
    {
        console.log('Nav link href:', link.href);
        if (link.href === location.href) //check the absolute url
        {
            link.classList.add('active'); //adds "active" to the element's class
        }
    })
}
function triggerPop(imageSrc){
    // create new popupImg element similar to img
    const popupImg = document.createElement('img');
    popupImg.setAttribute('class', 'img-popup');
    popupImg.setAttribute('src', imageSrc);

    const popupContainer = document.getElementById('popupContainer');
    // inserting the newly created popupImg inside the span element id=popupContainer
    popupContainer.insertAdjacentElement('afterbegin', popupImg);
    // popupContainer.appendChild(popupImg);
    popupImg.style.display = 'block';
    
    //close popup when clicked
    popupImg.addEventListener('click', function(){
        popupContainer.innerHTML = '';
    });

}
c