/**
 * GSAP Animations & ScrollTrigger Storytelling
 * Authentic, tactical, editorial motion inspired by Bite Toothpaste Bits
 * Dapur Nasi Biryani Berkah
 */

document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
    console.warn('GSAP or ScrollTrigger is not loaded');
    return;
  }

  gsap.registerPlugin(ScrollTrigger);

  // 1. Hero Entrance Animation
  const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });

  // Fade up text
  heroTl.from('.hero-text', {
    opacity: 0,
    y: 40,
    stagger: 0.15,
    duration: 1.2,
    ease: 'expo.out'
  });

  // Reveal hero image with a mask-like effect
  heroTl.from('.hero-image-wrapper', {
    clipPath: 'inset(100% 0% 0% 0%)',
    duration: 1.5,
    ease: 'expo.inOut'
  }, '-=1.2');

  // Gentle scale down on the image inside
  heroTl.from('#hero-main-img', {
    scale: 1.2,
    duration: 2,
    ease: 'power2.out'
  }, '-=1.2');

  // Fade in the floating badge
  heroTl.from('.hero-badge', {
    opacity: 0,
    scale: 0.8,
    y: 20,
    duration: 0.8,
    ease: 'back.out(1.5)'
  }, '-=0.5');

  // 2. Parallax Floating Spices
  const spices = document.querySelectorAll('.spice-parallax');
  spices.forEach((spice) => {
    const speed = parseFloat(spice.getAttribute('data-speed')) || 0.3;
    
    gsap.to(spice, {
      yPercent: -150 * speed,
      rotation: "+=60",
      ease: 'none',
      scrollTrigger: {
        trigger: spice.parentElement,
        start: 'top bottom',
        end: 'bottom top',
        scrub: 1
      }
    });
  });

  // 3. Pinned Storytelling Section (The "Bite" Scrolling Experience)
  // Pin the entire section while scrolling through the text blocks
  if (document.getElementById('story-pin-section')) {
    const textBlocks = gsap.utils.toArray('.story-text-block');
    const images = gsap.utils.toArray('.story-img');

    // Make sure we have the same number of images and text blocks
    if (textBlocks.length === images.length && images.length > 0) {
      
      // Pin the container
      ScrollTrigger.create({
        trigger: '#story-pin-section',
        start: 'top top',
        end: 'bottom bottom',
        pin: '#story-visual-container',
        pinSpacing: false
      });

      // Crossfade images based on text block position
      textBlocks.forEach((block, i) => {
        ScrollTrigger.create({
          trigger: block,
          start: 'top center',
          end: 'bottom center',
          onEnter: () => {
            gsap.to(images, { opacity: 0, duration: 0.4 });
            gsap.to(images[i], { opacity: 1, duration: 0.4 });
          },
          onEnterBack: () => {
            gsap.to(images, { opacity: 0, duration: 0.4 });
            gsap.to(images[i], { opacity: 1, duration: 0.4 });
          }
        });
      });
    }
  }

  // 4. Menu Showcase Reveal
  const showcaseItems = document.querySelectorAll('.showcase-item');
  showcaseItems.forEach((item) => {
    gsap.from(item, {
      opacity: 0,
      y: 60,
      duration: 1,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: item,
        start: 'top 80%',
      }
    });
  });

});
