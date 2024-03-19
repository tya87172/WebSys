const slider = document.querySelector(".slider"),
firstImg = slider.querySelectorAll("img")[0];
arrowIcons = document.querySelectorAll(".slider-wrapper i");

let firstImgWidth = firstImg.clientWidth;   // Get first img width

arrowIcons.forEach(icon => {
    icon.addEventListener("click", () => {
        // Scrolling function for slider
        if(icon.id == "left") {
            slider.scrollLeft -= firstImgWidth;
        } else {
            slider.scrollLeft += firstImgWidth;
        }
    });
});