   // Intervalo de tiempo para que se mueva carrusel
   $("#recipeCarousel").carousel({
    interval: 4000000000000,
});


$(".carousel .carousel-item").each(function () {
    var minPerSlide = 3;
    //next() Devuelve el siguiente elemento hermano
    var next = $(this).next();
    if (!next.length) {
        //siblings() devuleve todos los elementos hermanos 
        next = $(this).siblings(":first");
    }
    next.children(":first-child").clone().appendTo($(this));

    for (var i = 0; i < minPerSlide; i++) {
        next = next.next();
        if (!next.length) {
            next = $(this).siblings(":first");
        }

        next.children(":first-child").clone().appendTo($(this));
    }
});
const carrusel = document.querySelector(".carrusel"),
    firstImg = carrusel.querySelectorAll(".cardCarrusel")[0],
    arrowIcons = document.querySelectorAll(".contCasrrusel i");

const showHideIcons = () => {
    let scrollWidth = carrusel.scrollWidth - carrusel.clientWidth;
    arrowIcons[0].style.display = carrusel.scrollLeft == 0 ? "none" : "block";
    arrowIcons[1].style.display = carrusel.scrollLeft == scrollWidth ? "none" : "block";
}

arrowIcons.forEach(icon => {
    icon.addEventListener("click", () => {
        let firstImgWidth = firstImg.clientWidth + 14;
        carrusel.scrollLeft += icon.id == "left" ? -firstImgWidth : firstImgWidth;
        setTimeout(() => showHideIcons(), 60);
    });
});