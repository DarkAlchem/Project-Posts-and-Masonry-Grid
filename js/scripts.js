var msnry;

document.addEventListener('DOMContentLoaded', function(e) {
    createSearchFunction();
    animateMasonry();
});

window.onresize = () => {
     msnry.masonry('reloadItems')
}; 

document.addEventListener("DOMContentLoaded", function() {
  var lazyloadImages;    

  if ("IntersectionObserver" in window) {
    lazyloadImages = document.querySelectorAll(".lazy");
    var imageObserver = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var image = entry.target;
          image.src = image.dataset.src;
          image.classList.remove("lazy");
          imageObserver.unobserve(image);
          setTimeout(() => {
              image.classList.remove("_lazy");
          }, 1000);
        }
      });
    });

    lazyloadImages.forEach(function(image) {
      imageObserver.observe(image);
    });
  } else {  
    var lazyloadThrottleTimeout;
    lazyloadImages = document.querySelectorAll(".lazy");
    
    function lazyload () {
      if(lazyloadThrottleTimeout) {
        clearTimeout(lazyloadThrottleTimeout);
      }    

      lazyloadThrottleTimeout = setTimeout(function() {
        var scrollTop = window.pageYOffset;
        lazyloadImages.forEach(function(img) {
            if(img.offsetTop < (window.innerHeight + scrollTop)) {
              img.src = img.dataset.src;
              img.classList.remove('lazy');
            }
        });
        if(lazyloadImages.length == 0) { 
          document.removeEventListener("scroll", lazyload);
          window.removeEventListener("resize", lazyload);
          window.removeEventListener("orientationChange", lazyload);
        }
      }, 20);
    }

    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
  }
})

function createSearchFunction(){
    let search_name = document.querySelector('[data-type="project-name"]'),
        search_type = document.querySelector('[data-type="project-type"]'),
        search_location = document.querySelector('[data-type="project-location"]');

    search_name.addEventListener('change',() => {
        checkProjects('name',search_name.value);
    })

    search_type.addEventListener('change',() => {
        checkProjects('type',search_type.value);
    })

    search_location.addEventListener('change',() => {
        checkProjects('location',search_location.value);
    })
}

function animateMasonry(){
    let elem = document.querySelector('.project_masonry');
    elem.classList.remove('_extend');

    msnry = new Masonry(elem,{
      itemSelector: '.ip_project-cont:not(.disable)',
      gutter:10,
    });
}

function checkProjects(typeval,inputval){
    let div =  document.querySelector('.project_masonry'), 
        elem = document.querySelectorAll('.ip_project-cont');
    Object.values(elem).forEach((element) => {
        let link = element.childNodes[1];
        if (!element.classList.contains('disable')) element.classList.add('disable');
        if ('name' == typeval && (inputval == 'all' || link.getAttribute('data-id') == inputval)) element.classList.remove('disable');
        if ('type' == typeval && (inputval == 'all' || link.getAttribute('data-type').includes(inputval))) element.classList.remove('disable');
        if ('location' == typeval && (inputval == 'all' || link.getAttribute('data-location') == inputval)) element.classList.remove('disable');
    });
	msnry.destroy();
	msnry = new Masonry(div,{
      itemSelector: '.ip_project-cont:not(.disable)',
      gutter:10,
    });
}
