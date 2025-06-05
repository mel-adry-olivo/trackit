const track = document.querySelector(".carousel-track");
const slides = Array.from(track.children);

const firstClone = slides[0].cloneNode(true);
const lastClone = slides[slides.length - 1].cloneNode(true);

firstClone.classList.add("clone");
lastClone.classList.add("clone");

track.appendChild(firstClone);
track.insertBefore(lastClone, slides[0]);

let allSlides = Array.from(track.children);
let currentIndex = 1;
let slideWidth; // Declare, but don't initialize here

let isTransitioning = false;

// Function to initialize or re-calculate carousel dimensions
function initializeCarouselDimensions() {
  // Ensure the first slide is rendered and has a width
  slideWidth = allSlides[0].getBoundingClientRect().width;
  // If slideWidth is still 0, there might be a deeper CSS issue or image loading problem
  if (slideWidth === 0) {
    console.warn(
      "Carousel slide width is 0. Images or container might not be rendered yet."
    );
    // You might want to retry after a short delay, or check CSS.
  }
  track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
  setActiveSlide();
  updateCarousel(false); // Ensure correct initial positioning without animation
}

// Load all images in the carousel before initializing
const carouselImages = track.querySelectorAll("img"); // If your images are <img> tags
const backgroundImages = slides
  .map((slide) => {
    // For background-image styles
    const style = getComputedStyle(slide);
    const backgroundImage = style.backgroundImage;
    if (backgroundImage && backgroundImage !== "none") {
      const match = backgroundImage.match(/url\(['"]?(.*?)['"]?\)/);
      return match ? match[1] : null;
    }
    return null;
  })
  .filter(Boolean); // Filter out nulls

const allImageUrls = [
  ...Array.from(carouselImages).map((img) => img.src),
  ...backgroundImages,
];
let imagesLoadedCount = 0;

if (allImageUrls.length > 0) {
  allImageUrls.forEach((url) => {
    const img = new Image();
    img.src = url;
    img.onload = () => {
      imagesLoadedCount++;
      if (imagesLoadedCount === allImageUrls.length) {
        // All carousel images are loaded
        initializeCarouselDimensions();
      }
    };
    img.onerror = () => {
      console.error(`Failed to load image: ${url}`);
      imagesLoadedCount++; // Still increment to avoid blocking if one image fails
      if (imagesLoadedCount === allImageUrls.length) {
        initializeCarouselDimensions();
      }
    };
  });
} else {
  // No images found, initialize directly (e.g., if content is just text)
  initializeCarouselDimensions();
}

function setActiveSlide() {
  allSlides.forEach((slide) => slide.classList.remove("active"));
  if (allSlides[currentIndex]) {
    allSlides[currentIndex].classList.add("active");
  }
}

function updateCarousel(animate = true) {
  isTransitioning = true;
  if (animate) {
    track.style.transition = "transform 0.5s ease-in-out";
  } else {
    track.style.transition = "none";
  }
  track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
  setActiveSlide();
}

function checkLooping() {
  if (
    allSlides[currentIndex] &&
    allSlides[currentIndex].classList.contains("clone")
  ) {
    if (currentIndex === allSlides.length - 1) {
      currentIndex = 1;
    } else if (currentIndex === 0) {
      currentIndex = allSlides.length - 2;
    }
    updateCarousel(false);
  }
  isTransitioning = false;
}

track.addEventListener("transitionend", checkLooping);

document.querySelector(".carousel-btn.next").addEventListener("click", () => {
  if (isTransitioning) return;
  currentIndex++;
  updateCarousel();
});

document.querySelector(".carousel-btn.prev").addEventListener("click", () => {
  if (isTransitioning) return;
  currentIndex--;
  updateCarousel();
});

let autoScroll = setInterval(() => {
  currentIndex++;
  updateCarousel();
}, 3000);

track.addEventListener("mouseenter", () => clearInterval(autoScroll));
track.addEventListener("mouseleave", () => {
  autoScroll = setInterval(() => {
    currentIndex++;
    updateCarousel();
  }, 3000);
});

window.addEventListener("resize", () => {
  slideWidth = allSlides[0].getBoundingClientRect().width;
  updateCarousel(false);
});

const scrollElements = document.querySelectorAll(".animate-on-scroll");

function elementInView(el, offset = 100) {
  const elementTop = el.getBoundingClientRect().top;
  return (
    elementTop <=
    (window.innerHeight || document.documentElement.clientHeight) - offset
  );
}

function displayScrollElement(el) {
  el.classList.add("visible");
}

function hideScrollElement(el) {
  el.classList.remove("visible");
}

function handleScrollAnimation() {
  scrollElements.forEach((el) => {
    if (elementInView(el, 100)) {
      displayScrollElement(el);
    } else {
      hideScrollElement(el);
    }
  });
}

window.addEventListener("scroll", () => {
  handleScrollAnimation();
});

window.addEventListener("load", () => {
  handleScrollAnimation();
});

function updateTime() {
  const now = new Date();
  const hours = now.getHours().toString().padStart(2, "0");
  const minutes = now.getMinutes().toString().padStart(2, "0");
  const timeString = `${hours}:${minutes}`;
  document.getElementById("currentTime").textContent = timeString;
}

updateTime();
setInterval(updateTime, 60000);

const contactBtn = document.querySelector(".contact-btn");
const contactModal = document.getElementById("contactModal");

const supportBtn = document.querySelector(".support-btn");
const supportModal = document.getElementById("supportModal");
const submitSupport = document.getElementById("submitSupport");
const supportMessage = document.getElementById("supportMessage");

const closeButtons = document.querySelectorAll(".modal-close");

contactBtn.addEventListener("click", () => {
  contactModal.style.display = "flex";
});

supportBtn.addEventListener("click", () => {
  supportModal.style.display = "flex";
});

closeButtons.forEach((button) => {
  button.addEventListener("click", () => {
    contactModal.style.display = "none";
    supportModal.style.display = "none";
  });
});

[contactModal, supportModal].forEach((modal) => {
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});

submitSupport.addEventListener("click", () => {
  const message = supportMessage.value.trim();
  if (message) {
    alert("Your message has been submitted. Thank you!");
    supportMessage.value = "";
    supportModal.style.display = "none";
  } else {
    alert("Please enter your issue before submitting.");
  }
});

document.addEventListener("onload", function () {
  const coords = [10.7202, 122.5621];

  const map = L.map("map", {
    zoomControl: false,
    attributionControl: false,
    dragging: false,
    scrollWheelZoom: false,
    doubleClickZoom: false,
    boxZoom: false,
    keyboard: false,
    tap: false,
    touchZoom: false,
  }).setView(coords, 13);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

  L.circle(coords, {
    color: "#2f80ed",
    fillColor: "#2f80ed",
    fillOpacity: 0.2,
    radius: 1000,
  }).addTo(map);

  L.marker(coords).addTo(map);
});

function createDigitElement(digit) {
  const digitWrapper = document.createElement("div");
  digitWrapper.classList.add("digit");

  const front = document.createElement("div");
  front.classList.add("card", "front");
  front.textContent = digit;

  const back = document.createElement("div");
  back.classList.add("card", "back");
  back.textContent = digit;

  digitWrapper.appendChild(front);
  digitWrapper.appendChild(back);

  return digitWrapper;
}

function updateClock() {
  const now = new Date();
  let hours = now.getHours();
  let minutes = now.getMinutes();

  const timeStr = `${hours.toString().padStart(2, "0")}:${minutes
    .toString()
    .padStart(2, "0")}`;

  const clockContainer = document.getElementById("currentTime");

  if (clockContainer.children.length === 0) {
    for (const char of timeStr) {
      if (char === ":") {
        const colon = document.createElement("div");
        colon.textContent = ":";
        colon.classList.add("colon");
        clockContainer.appendChild(colon);
      } else {
        clockContainer.appendChild(createDigitElement(char));
      }
    }
  } else {
    let digitIndex = 0;
    for (let i = 0; i < timeStr.length; i++) {
      const char = timeStr[i];
      if (char === ":") continue;

      const digitDiv = clockContainer.children[i];
      if (!digitDiv.classList.contains("digit")) continue;

      const front = digitDiv.querySelector(".front");
      const back = digitDiv.querySelector(".back");

      if (front.textContent !== char) {
        back.textContent = char;
        digitDiv.classList.add("flipping");
        setTimeout(() => {
          front.textContent = char;
          digitDiv.classList.remove("flipping");
        }, 600);
      }
    }
  }
}

updateClock();
setInterval(updateClock, 60000);
