(() => {
  const qs = (s, ctx = document) => ctx.querySelector(s);
  const qsa = (s, ctx = document) => Array.from(ctx.querySelectorAll(s));

  // --- Carousel Module ---
  class Carousel {
    constructor(selector, interval = 3000) {
      this.track = qs(selector);
      if (!this.track) return;
      this.slides = qsa(".slide", this.track);
      this.current = 1;
      this.isTransitioning = false;
      this.interval = interval;
      this.timer = null;
      this.setup();
    }

    setup() {
      if (!this.slides.length) return;
      const first = this.slides[0].cloneNode(true);
      const last = this.slides.at(-1).cloneNode(true);
      [last, first].forEach((cl) => cl.classList.add("clone"));
      this.track.append(first);
      this.track.prepend(last);
      this.all = qsa(".slide", this.track);
      this.width = this.slides[0].getBoundingClientRect().width;
      this.move(0, false);
      this.bind();
      this.startAuto();
    }

    move(delta = 1, animate = true) {
      if (this.isTransitioning) return;
      this.isTransitioning = animate;
      this.current += delta;
      this.track.style.transition = animate ? "transform .5s ease" : "none";
      this.track.style.transform = `translateX(-${
        this.width * this.current
      }px)`;
    }

    loopCheck() {
      const slide = this.all[this.current];
      if (slide.classList.contains("clone")) {
        this.track.style.transition = "none";
        this.current = this.current === 0 ? this.all.length - 2 : 1;
        this.track.style.transform = `translateX(-${
          this.width * this.current
        }px)`;
      }
      this.isTransitioning = false;
      this.updateActive();
    }

    updateActive() {
      this.all.forEach((s) => s.classList.remove("active"));
      this.all[this.current]?.classList.add("active");
    }

    startAuto() {
      this.stopAuto();
      this.timer = setInterval(() => this.move(1), this.interval);
    }
    stopAuto() {
      clearInterval(this.timer);
    }

    bind() {
      qs(".carousel-btn.next")?.addEventListener("click", () => this.move(1));
      qs(".carousel-btn.prev")?.addEventListener("click", () => this.move(-1));
      this.track.addEventListener("transitionend", () => this.loopCheck());
      this.track.addEventListener("mouseenter", () => this.stopAuto());
      this.track.addEventListener("mouseleave", () => this.startAuto());
      window.addEventListener("resize", () => {
        this.width = this.all[0].getBoundingClientRect().width;
        this.move(0, false);
      });
    }
  }

  const scrollAnim = () => {
    const els = qsa(".animate-on-scroll");
    const inView = (el) =>
      el.getBoundingClientRect().top <= window.innerHeight - 100;
    els.forEach((el) => el.classList.toggle("visible", inView(el)));
  };

  class FlipClock {
    constructor(containerId) {
      this.container = qs(`#${containerId}`);
      this.init();
    }

    init() {
      this.update();
      setInterval(() => this.update(), 60000);
    }

    createDigit(d) {
      const wrap = document.createElement("div");
      wrap.classList.add("digit");
      ["front", "back"].forEach((cls) => {
        const div = document.createElement("div");
        div.classList.add("card", cls);
        div.textContent = d;
        wrap.append(div);
      });
      return wrap;
    }

    update() {
      const now = new Date();
      const time = now.toLocaleTimeString("en-GB", {
        hour: "2-digit",
        minute: "2-digit",
      });
      if (!this.container.children.length) {
        [...time].forEach((c) => {
          this.container.append(
            c === ":"
              ? Object.assign(document.createElement("div"), {
                  textContent: ":",
                  className: "colon",
                })
              : this.createDigit(c)
          );
        });
      } else {
        [...time].forEach((c, i) => {
          if (c === ":") return;
          const d = this.container.children[i];
          const [front, back] = [
            d.querySelector(".front"),
            d.querySelector(".back"),
          ];
          if (front.textContent !== c) {
            back.textContent = c;
            d.classList.add("flipping");
            setTimeout(() => {
              front.textContent = c;
              d.classList.remove("flipping");
            }, 600);
          }
        });
      }
    }
  }

  const modals = () => {
    const openBtn = (cls) =>
      qs(`.${cls}`)?.addEventListener(
        "click",
        () => (qs(`#${cls}Modal`).style.display = "flex")
      );
    ["contact-btn", "support-btn"].forEach(openBtn);
    qsa(".modal-close").forEach((b) =>
      b.addEventListener("click", () =>
        qsa(".modal").forEach((m) => (m.style.display = "none"))
      )
    );
    qsa(".modal").forEach((m) =>
      m.addEventListener("click", (e) => {
        if (e.target === m) m.style.display = "none";
      })
    );
    qs("#submitSupport").addEventListener("click", () => {
      const msg = qs("#supportMessage").value.trim();
      alert(
        msg
          ? "Your message has been submitted. Thank you!"
          : "Please enter your issue before submitting."
      );
      if (msg)
        (qs("#supportMessage").value = ""),
          (qs("#supportModal").style.display = "none");
    });
  };

  const initMap = () => {
    const coords = [10.7202, 122.5621];
    const map = L.map("map", {
      zoomControl: false,
      attributionControl: false,
      dragging: false,
      scrollWheelZoom: false,
    }).setView(coords, 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(
      map
    );
    L.circle(coords, {
      color: "#2f80ed",
      fillColor: "#2f80ed",
      fillOpacity: 0.2,
      radius: 1000,
    }).addTo(map);
    L.marker(coords).addTo(map);
  };

  document.addEventListener("DOMContentLoaded", () => {
    new Carousel(".carousel-track");
    window.addEventListener("scroll", scrollAnim);
    scrollAnim();
    new FlipClock("currentTime");
    modals();
    window.addEventListener("load", initMap);
  });
})();
