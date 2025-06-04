//video demo

const video = document.getElementById('video');


const handleVideoVisibility = (entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            if (video.paused) {
                video.play().catch(error => {
                    
                    document.body.addEventListener("click", () => {
                        video.play();
                    });
                });
            }
        } else {
        if (!video.paused) {
                video.pause();
            }
        }
    });
};

const observer = new IntersectionObserver(handleVideoVisibility, {
    threshold: 0.5, 
});

const videoSection = document.getElementById('video-section');
observer.observe(videoSection);
