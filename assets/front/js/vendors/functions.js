$(document).ready(function () {



    /* SMOOTH SCROLLING */
    // Select all links with hashes
    $('a[href*="#"]')
    // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .not('[data-noautscroll]')
        .click(function(event) {
            // On-page links
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                location.hostname == this.hostname
            ) {
                // Figure out element to scroll to
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                // Does a scroll target exist?
                if (target.length) {
                    // Only prevent default if animation is actually gonna happen
                    event.preventDefault();

                    console.log("autoscroll", this.hash);

                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000, function() {
                        // Callback after animation
                        // Must change focus!
                        var $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) { // Checking if the target was focused
                            return false;
                        } else {
                            $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                            $target.focus(); // Set focus again
                        };
                    });
                }
            }
        });





    /* ISOTOPE */

    // external js: isotope.pkgd.js
    $("#noResult").hide();
    // init Isotope

    /*var $grid = $('#cardsGrid').isotope({
        itemSelector: '.card',
    });*/


    // message si pas de résultats

    /*
    var iso = $grid.data('isotope');
    $grid.isotope('on', 'layoutComplete', function () {
        var numItems = iso.filteredItems.length;

        if (numItems > 0) {
            $("#noResult").fadeOut(0);
        } else {
            $("#noResult").show();
        };

    });
    */

    /*

    // store filter for each group
    var filters = [];

    // change is-checked class on buttons
    $('#filters').on('click', 'li', function (event) {
        var $target = $(event.currentTarget);
        $target.toggleClass('is-checked');
        var isChecked = $target.hasClass('is-checked');
        var filter = $target.attr('data-filter');

        if (isChecked) {
            addFilter(filter);

        } else {
            removeFilter(filter);
        }
        // filter isotope
        // group filters together, inclusive
        $grid.isotope({
            filter: filters.join(',')
        });
    });

    function addFilter(filter) {
        if (filters.indexOf(filter) == -1) {
            filters.push(filter);
        }

    }

    function removeFilter(filter) {
        var index = filters.indexOf(filter);
        if (index != -1) {
            filters.splice(index, 1);
        }

    }

    */


    /* TOOLTIP */

    /*
        $(document).tooltip({
            selector: '.tt'
        });
    */


    /* tooltip GENERAL */

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });


    /* POPOVER & TOOLTIPS Cards Ico */

    /* tooltip J'aime FB */

    $(".card .fa-thumbs-up").tooltip({
        trigger: "hover",
        placement: 'top',
        title: "J'aime",
        container: "body"
    });

    /* tooltip Pixie suivre */
    $(".pixieBlock .pixieLink").tooltip({
        trigger: "hover",
        placement: 'left',
        title: "Suivre le Pixie",
        container: "body"
    });

    /* tooltip Pixie Instagram */
    $(".pixieBlock .instagramLink").tooltip({
        trigger: "hover",
        placement: 'right',
        title: "Profil Instagram",
        container: "body"

    });


    /* tooltip ajouter aux favoris */

    $(".home .card .cardsIco, .region .card .cardsIco").tooltip({
        trigger: "hover",
        placement: 'top',
        title: "Ajouter aux favoris",
        container: "body"
    });

    /* tooltip retirer des favoris */

    $(".collections .card .cardsIco").tooltip({
        trigger: "hover",
        placement: 'top',
        title: "Retirer des favoris",
        container: "body"
    });



    /* tooltip supprimer la collection */

    $(".deleteColl").tooltip({
        trigger: "hover",
        placement: 'top',
        title: "Supprimer",
        container: "body",

    });

    /* tooltips nav >768 and < 991px */

    /*  
        if ($(document).width() > 768) {

                    $(this).toggleClass('open')
                }
        
        $('#accountMenu.dropdown').hover(function () {
                if ($(document).width() > 768) {

                    $(this).toggleClass('open')
                }

            });
        
    */

    /* popover GENERAL 
    $(function () {
        $('[data-toggle="popover"]').popover()
    })*/

    /* popover Ajouter à la collection */

    $(".loggedIn .card .cardsIco").popover({
        html: true,
        trigger: "click",
        placement: 'bottom',
        template: '<div class="popover popBlue addToColl"><div class="arrow"></div><div class="popover-content"></div></div>',
        container: 'body',
        content: function () {
            return $('#addToColl').html();
        }
    });

    /* popover Non loggé > Créer un compte */

    $(".loggedOut .card .cardsIco").popover({
        html: true,
        trigger: "click",
        placement: 'bottom',
        template: '<div class="popover cardPop"><div class="arrow"></div><div class="popover-content"></div></div>',
        content: function () {
            return $('#popCreateAccount').html();
        }
    });



    /* POPOVER Comments AGENDA */

    $("#agendaComments button").popover({
        html: true,
        trigger: "click",
        template: '<div class="popover popBlue collComPop"><div class="arrow"></div><div class="popover-content"></div></div>',
        container: "body",
        /*trigger: "focus",*/

        content: function () {
            return $('body #collCommentsPop').html();


        }
    });


    $(document).on("click", "button.fa-times", function () {
        $(this).parents(".popover").popover('hide');
    });

    /* POPOVER Social Sharing AGENDA*/
    $("#agendaShare button").popover({
        html: true,
        trigger: "click",
        template: '<div class="popover popRs"><div class="arrow"></div><div class="popover-content"></div></div>',
        content: function () {
            return $('#collSharePop').html();
        }
    });



    /* POPOVER SURVOLS AllPixiesRegion */

    var originalLeave = $.fn.popover.Constructor.prototype.leave;
    $.fn.popover.Constructor.prototype.leave = function (obj) {
        var self = obj instanceof this.constructor ?
            obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
        var container, timeout;

        originalLeave.call(this, obj);

        if (obj.currentTarget) {
            container = $(obj.currentTarget).siblings('.popover')
            timeout = self.timeout;
            container.one('mouseenter', function () {
                //We entered the actual popover – call off the dogs
                clearTimeout(timeout);
                //Let's monitor popover content instead
                container.one('mouseleave', function () {
                    $.fn.popover.Constructor.prototype.leave.call(self, self);
                });
            })
        }
    };


    /* POPOVER ALL PIXIES REGION */
    $(".allPixies.region .pixieBlock").popover({
        html: true,
        trigger: "click hover",
        template: '<div class="popover pl-popover"><div class="arrow"></div><div class="popover-content"></div></div>',
        placement: "bottom",
        contrainer: "body",
        delay: {
            show: 50,
            hide: 100
        },
        content: function () {
            return $(this).find('.pl-popover').html();
        }
    });


    /* FERMER LES POPOVER ON CLICK OUTSIDE */

    $('html').on('click', function (e) {
        if (typeof $(e.target).data('original-title') == 'undefined' &&
            !$(e.target).parents().is('.popover.in')) {
            $('[data-original-title]').popover('hide');
        }
    });


    /* CREATE ACCOUNT FORM */

    $("#regionSearch .dropdown-menu li label input").click(function () {
        $(this).parent().toggleClass("on");
    });

    $(".plCheck").click(function (e) {

        e.stopImmediatePropagation();
        e.stopPropagation();
        e.preventDefault();
        $(this).toggleClass("clicked");

        $(this).find("input[type=checkbox]").prop("checked", !($(this).find("input[type=checkbox]").is(':checked'))).change();
    });


    $(".collCheck").click(function () {
        $(this).toggleClass("clicked");
    });

    /*
    $('.tab-pane .navBtm .next').click(function () {
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
    });
    $('.tab-pane .navBtm .prev').click(function () {
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
    });
    */



    /*FLEXSLIDER*/

    $('.landingSlider').flexslider({
        animation: "fade",
        prevText: "",
        nextText: "",
        directionNav: false,
        controlNav: false,
    });

    $('.introHome').flexslider({
        animation: "fade",
        prevText: "",
        nextText: "",
        controlsContainer: ".customNav",
        directionNav: false
    });

    $('.cardSlider').flexslider({
        controlNav: false,
        animation: "slide",
        animationLoop: false,
        itemWidth: 240,
        itemMargin: 20,
        minItems: 1,
        maxItems: 7,

        prevText: "",
        nextText: "",
        move: 1
    });

    $('.topCardSlider').flexslider({
        animation: "fade",
        prevText: "",
        nextText: "",

    });

    /* Set left height creaCompte */
    $(".nav-tabs li.tab1").click(function () {
        newtabHeight = $('.tp1').outerHeight() + 50;
        $('.creaCompte .left').css('height', newtabHeight);


    });
    $(".nav-tabs li.tab2").click(function () {
        newtabHeight = $('.tp2').outerHeight() + 50;
        $('.creaCompte .left').css('height', newtabHeight);


    });
    $(".nav-tabs li.tab3").click(function () {
        newtabHeight = $('.tp3').outerHeight() + 50;
        $('.creaCompte .left').css('height', newtabHeight);


    });
    $(".nav-tabs li.tab4").click(function () {
        newtabHeight = $('.tp4').outerHeight() + 50;
        $('.creaCompte .left').css('height', newtabHeight);


    });



    /* CARROUSELS SLICK */
    var rows = 2;
    var viewportWidth = $(window).width();
    if( viewportWidth <= 992){
        rows = 1;
    }

    $('.carrouselRows').each(function(){
        $(this).slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
            rows: ($(this).find(".card").length >= 10)?rows:1,
            prevArrow: '<a class="slick-prev" href="#"></a>',
            nextArrow: '<a class="slick-next" href="#"></a>',
            responsive: [


                {
                    breakpoint: 1366,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 5,

                    }
                },


                {
                    breakpoint: 1240,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,

                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        rows: 1,
                        slidesToShow: 3,
                        slidesToScroll: 3,

                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 550,
                    settings: {
                        rows: 1,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    });






    $('.carrousel').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 5,
        prevArrow: '<a class="slick-prev" href="#"></a>',
        nextArrow: '<a class="slick-next" href="#"></a>',
        responsive: [



            {
                breakpoint: 1241,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,

                }
    },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,

                }
    },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
    },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
    });


    $('.carrouselColl').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 7,
        slidesToScroll: 7,
        prevArrow: '<a class="slick-prev" href="#"></a>',
        nextArrow: '<a class="slick-next" href="#"></a>',
        responsive: [

            {
                breakpoint: 1600,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 6,

                }
    },
            {
                breakpoint: 1366,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 5,

                }
    },


            {
                breakpoint: 1240,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,

                }
    },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,

                }
    },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
    },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
    });



    /*     DO NOT CLOSE DROPDOWN MENU ON CLICK  */

    $('.dnc').on('click', function (event) {
        event.preventDefault();
        $(".open>.dnc").not(this).parent().removeClass('open');
        $(this).parent().toggleClass('open');

    });

    $('body').on('click', function (e) {
        if (!$('.dropdown').is(e.target) &&
            $('.dropdown').has(e.target).length === 0 &&
            $('.open').has(e.target).length === 0
        ) {
            $('.dropdown').removeClass('open');
        }
    });


    /* INIT DATEPICKER 

    $(".datepicker").datepicker({

    });*/

});



$(window).on('load resize', function () {

    /* menu "ACCOUNT" on hover si >1024px */
    if ($(document).width() > 1024) {
        $('#accountMenu.dropdown').hover(function () {
            $('#accountMenu').addClass('open');
        }, function () {
            $('#accountMenu').removeClass('open');
        });
    }


    /* MATCH HEIGHT BOXES */
    $('.box').matchHeight();

    /* CUSTOM F° creaCompte */

    function setleftHeight() {
        tabHeight = $('.creaCompte .right').height();
        $('.creaCompte .left').css('height', tabHeight);

    }

    setleftHeight();


    function setlandingHeight() {
        landingHeight = $(window).height();
        $('.LandingPage-template, .LandingPage-template .col-md-6, .LandingPage-template .flexslider li').css('min-height', landingHeight);

    }

    setlandingHeight();


});

/* HREF # FIX */

$('a').click(function (e) {
    // Special stuff to do when this link is clicked...

    // Cancel the default action
    e.preventDefault();
});


/* HOVER CELLULES DE l AGENDA */
$(document).ready(function () {


    var $tooltip = $("<div>").addClass("calInnerLink");
    $("#calendar").on("mouseenter", ".fc-event", function () {
        $('body').append($tooltip);
        $tooltip.position({
            my: "left top",
            at: "middle middle",
            of: $(this)
        });
        console.log("in");
    }).on("mouseleave", ".fc-event", function () {
        $tooltip = $tooltip.detach();
        console.log("out");
    });


});
