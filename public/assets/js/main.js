(function() {
    "use strict";
    

    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
      el = el.trim()
      if (all) {
        return [...document.querySelectorAll(el)]
      } else {
        return document.querySelector(el)
      }
    }

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
      let selectEl = select(el, all)
      if (selectEl) {
        if (all) {
          selectEl.forEach(e => e.addEventListener(type, listener))
        } else {
          selectEl.addEventListener(type, listener)
        }
      }
    }

    /* Dropzone  */
    /* const dropchamp =document.querySelector('.dropzone_c');
    const headerText=document.querySelector('.dropzone_head');
    const button=document.querySelector('#dropzone_button'); */
    /* const input_te=document.querySelector(''); */

    /* button.addEventListener('click', (e)=>{
        input.click();
    }) */
    /* input.addEventListener('change', function () {

    }) */
    /* on('change', "#dropzone_input", function (e) {
        console.log(this.files[0]);
        let file = this.files[0];
        showFile(file)
    }) */

    /* function showFile(file){
        let fileType = file.type;
        let fileExtension = ["application/pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/msword"];
        const btn=document.querySelector('.valids');
        if (fileExtension.includes(fileType)) {
            btn.classList.remove('d-none')
        }else{
            btn.classList.add('d-none')
        }
    } */


    /**
     * Easy on scroll event listener
     */
    const onscroll = (el, listener) => {
      el.addEventListener('scroll', listener)
    }

    /**
     * Back to top button
     */
    let backtotop = select('.back-to-top')
    if (backtotop) {
      const toggleBacktotop = () => {
        if (window.scrollY > 100) {
          backtotop.classList.add('active')
        } else {
          backtotop.classList.remove('active')
        }
      }
      window.addEventListener('load', toggleBacktotop)
      onscroll(document, toggleBacktotop)
    }

    /**
     * Mobile nav toggle
     */
    on('click', '.mobile-nav-toggle', function(e) {
      select('#navbar').classList.toggle('navbar-mobile')
      this.classList.toggle('bi-list')
      this.classList.toggle('bi-x')
    })

    /**
     * Mobile nav dropdowns activate
     */
    on('click', '.navbar .dropdown > a', function(e) {
      if (select('#navbar').classList.contains('navbar-mobile')) {
        e.preventDefault()
        this.nextElementSibling.classList.toggle('dropdown-active')
      }
    }, true)

    /**
     * Preloader
     */
    let preloader = select('#preloader');
    if (preloader) {
      window.addEventListener('load', () => {
        preloader.remove()
      });
    }

    /**
     * Testimonials slider
     */
    new Swiper('.testimonials-slider', {
      speed: 600,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false
      },
      slidesPerView: 'auto',
      pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 20
        },

        1200: {
          slidesPerView: 2,
          spaceBetween: 20
        }
      }
    });

    /**
     * Animation on scroll
     */
    window.addEventListener('load', () => {
      AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
      })
    });

    window.addEventListener('load', () => {
        var liens = document.querySelectorAll('.liens');
        for (let i = 0; i < liens.length; i++) {
            if (liens[i].href == window.location.href) {

                liens[i].classList.add('active')
                //liens[i].parentNode.parentNode.parentNode.classList.add('active')
                //$('#menu1').metisMenu();

            }
        }
    });

    /**
     * Initiate Pure Counter
     */
    new PureCounter();

  })()
