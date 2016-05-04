// inicialización de valores
var delta = 0;
var currentSlideIndex = 0;
var scrollThreshold = 8;
var numSlides = 3;

function elementScroll (e) {
	// --- Scrolling up ---
	if (e.originalEvent.detail < 0 || e.originalEvent.wheelDelta > 0) {
		delta--;
		if ( Math.abs(delta) >= scrollThreshold) {
		    prevSlide();
		}
	}

	// --- Scrolling down ---
	else {
		delta++;
		if (delta >= scrollThreshold) {
			nextSlide();
		}
	}
	// Prevent page from scrolling
	return false;
}


function showSlide() {
	// reset
	delta = 0;
    // Mostrar el slide actual y ocultar los hermanos
    $(".slide").eq(currentSlideIndex).fadeIn(1000).siblings().fadeOut(0);
}

function prevSlide() {
	currentSlideIndex--;
	if (currentSlideIndex < 0) {
		currentSlideIndex = 0;
	}
	showSlide();
}

function nextSlide() {
	currentSlideIndex++;
	if (currentSlideIndex > numSlides) {
		currentSlideIndex = numSlides;
	}
	showSlide();
}

$(window).on({
	'DOMMouseScroll mousewheel': elementScroll
});
