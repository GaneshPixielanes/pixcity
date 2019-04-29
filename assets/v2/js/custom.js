window.jQuery = window.$ = require('jquery');
require('./bootstrap.min');
require('./popper.min');
require('./slick.min');
require('./bootstrap-select.min');
require('./simplebar.min');
require('./jquery.cookie');
require('./lightgallery');
// require('./jquery.validate');

$(document).ready(function() {

    //Map marker popup slider
    $('.popup-slider').slick({
        arrows: false,
        dots: true,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1,
        variableWidth: true,
        adaptiveHeight: false
    });

    //Map sidebar slider
    $('.sidebar-slider').slick({
        arrows: false,
        dots: true,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1,
        variableWidth: true,
        adaptiveHeight: false
    });
    $('.similar-places-slider').slick({
        arrows: false,
        dots: true,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1,
        variableWidth: true,
        adaptiveHeight: false
    });
		try{
			$('[data-toggle="tooltip"]').tooltip({
	        trigger : 'hover'
	    });

			$('[data-toggle="popover"]').popover();
			$('.selectpicker').selectpicker();
		}catch(e)
		{
			// DO NOTHING
		}




//Open dropdown for mobile categories
    $('#dropdownMenuLink').click(function() {
        $('#catsDropdown').slideToggle();
    });


    //Open dropdown for mobile regions
    $('#dropdownMenuRegion').click(function() {
        $('#regDropdown').slideToggle();
    });

    $('.switch-map input[type="checkbox"]').click(function(){
        if($(this).prop("checked") == true){
            $('.card-view').show();
            $('.map-view').hide();
        }
        else if($(this).prop("checked") == false){
            $('.card-view').hide();
            $('.map-view').show();
        }
    });

    //Sidebar Slide in animation
    // $('body').on('click','.icon-click', function(e)
    // {
    //     $('.map-view-sidebar').animate({"left": "0px"}, 400);
    // });

    $('a.close-sidebar').click(function(e) {
        $('.map-view-sidebar').animate({"left": "-1000px"}, 400);
    });

    /*Dropdown search*/

    $('.map-search .form-inline .input-group input').focus(function(e) {
        e.stopPropagation();
        $(this).parents('.input-group').find('.dropdown-search').fadeIn('400', function() {
            $(this).parents('.input-group').find('.dropdown-search').show();
        });
    });
    $(document).click(function(e) {
        if(e.target.id == '.dropdown-search, .map-search .form-inline .input-group input')
          return;
       //For descendants of menu_content being clicked, remove this check if you do not want to put constraint on descendants.
       if($(e.target).closest('.dropdown-search, .map-search .form-inline .input-group input').length)
          return;

        $('.dropdown-search').fadeOut('400', function() {
            $('.dropdown-search').hide();
        });
    });
});

// Cards filter
// ! function(t) {
//     function e(n) {
//         if (a[n]) return a[n].exports;
//         var i = a[n] = {
//             i: n,
//             l: !1,
//             exports: {}
//         };
//         return t[n].call(i.exports, i, i.exports, e), i.l = !0, i.exports
//     }
//     var a = {};
//     e.m = t, e.c = a, e.d = function(t, a, n) {
//         e.o(t, a) || Object.defineProperty(t, a, {
//             configurable: !1,
//             enumerable: !0,
//             get: n
//         })
//     }, e.n = function(t) {
//         var a = t && t.__esModule ? function() {
//             return t.default
//         } : function() {
//             return t
//         };
//         return e.d(a, "a", a), a
//     }, e.o = function(t, e) {
//         return Object.prototype.hasOwnProperty.call(t, e)
//     }, e.p = "/build/", e(e.s = "qL0K")
// }({
//     FQIL: function(t, e) {
//         jQuery(document).ready(function() {
//             var t = $(".list-of-cards"),
//                 e = t.find(".load-more"),
//                 a = t.find(".categories"),
//                 n = t.find(".regions-list"),
//                 i = t.find(".cards-grid"),
//                 r = t.find(".loader"),
//                 s = t.find(".no-results"),
//                 l = $(".search-result-title"),
//                 o = $(".search-form-container"),
//                 c = (o.find("form"), t.attr("data-api-route")),
//                 d = !1,
//                 u = null,
//                 f = [],
//                 h = [],
//                 g = [];
//             t.attr("data-selectable") && (d = t.attr("data-selectable")), t.attr("data-user-favorite") && (u = t.attr("data-user-favorite")), t.attr("data-user") && (f = [t.attr("data-user")]), t.attr("data-region") ? g = [parseInt(t.attr("data-region"))] : $("input[name='regions[]']:checked").each(function() {
//                 g.push($(this).val())
//             }), $("input[name='categories[]']:checked").each(function() {
//                 h.push($(this).val())
//             }), 0 === i.find(".category-card").length ? (e.hide(), s.show()) : i.find(".category-card").length < 9 && e.hide();
//             var p = function(a) {
//                     r.addClass("active"), $.ajax({
//                         type: "POST",
//                         url: c,
//                         data: {
//                             page: v(),
//                             limit: $(".list-pagination").attr("data-limit") ? $(".list-pagination").attr("data-limit") : 10,
//                             categories: h,
//                             regions: g,
//                             users: f,
//                             userFavorite: u,
//                             orderby: $("input[name='orderby']").val(),
//                             pixie: $("input[name='search_pixie']").val(),
//                             selectable: d
//                         }
//                     }).done(function(n) {
//                     	if(0 !== n.datas.length)
//                     	{
//                     		$('#noResult').addClass('d-none');
//                     	}
//                     	else
//                     	{
//                     		$('#noResult').removeClass('d-none');
//                     	}
//                         a ? (i.html(n.html), 1 === parseInt(v()) && $(window).trigger("card-list-reset", [n]), t.hasClass("no-autoscroll") || $("html, body").animate({
//                             scrollTop: i.offset().top - $(".navbar-fixed-top").height()
//                         }, 1e3)) : i.append(n.html), e.toggle(10 === n.datas.length), s.toggle(0 === n.datas.length && 1 === v()), $(window).trigger("card-list-updated"), l.length > 0 && l.html("Les Pixies vous proposent <span>" + n.totalItems + "</span> Cards !")
//
//                         // Update the text in breadcrumb with the selected category
//                         var categoryText = 'Résultat -';
//                         var cardCount = $('.category-card').length;
//                         $.each($('.is-checked'), function(key, val)
//                         {
//                             if(categoryText == 'Résultat -')
//                             {
//                                 categoryText = categoryText + $(val).text();
//                             }
//                             else
//                             {
//                                 categoryText = categoryText +', '+ $(val).text();
//                             }
//                         });
//
//                         $('#totalCards').text(cardCount);
//                         $('#bd-result-cat').text(categoryText);
//                     }).always(function() {
//                         r.removeClass("active")
//                     })
//                 },
//                 m = function(e) {
//                     t.attr("data-page", e)
//                 },
//                 v = function(e) {
//                     return parseInt(t.attr("data-page"))
//                 };
//             e.click(function() {
//                 m(v() + 1), p(!1)
//             }), $(window).on("pagination-updated", function(t, e) {
//                 m(e), p(!0)
//             }), a.on("click", "li", function() {
//                 var t = $(this);
//                 m(1), t.hasClass("all") ? a.find("li").removeClass("is-checked") : t.toggleClass("is-checked"), C(), a.find("li.all").toggleClass("is-checked", 0 === h.length), p(!0)
//             });
//             var C = function() {
//                 return h = [], a.find("li.is-checked:not(.all)").each(function() {
//                     h.push($(this).attr("data-id"))
//                 }), t.find(".count-filters-categories").text(h.length > 0 ? "(" + h.length + ")" : ""), h
//             };
//             n.on("click", "li", function() {
//                 var t = $(this);
//                 m(1), t.hasClass("all") ? n.find("li").removeClass("is-checked") : t.toggleClass("is-checked"), k(), n.find("li.all").toggleClass("is-checked", 0 === g.length), p(!0)
//             });
//             var k = function() {
//                 return g = [], n.find("li.is-checked:not(.all)").each(function() {
//                     g.push($(this).attr("data-id"))
//                 }), t.find(".count-filters-regions").text(g.length > 0 ? "(" + g.length + ")" : ""), g
//             }
//         })
//     },
//     qL0K: function(t, e, a) {
//         a("FQIL"), jQuery(document).ready(function() {
//             var t = 1;
//             $("#showMoreCards").click(function(e) {
//                 t += 1, e.preventDefault(), $(".loadCards:last").html('<p class="text-center">Cards en téléchargement...</p>'), $(".loadCards:last").load("/v2/load-cards?page=" + t, function(t) {
//                     // console.log(t)
//                 })
//             })
//         })
//     }
// });
//
// jQuery(document).ready(function()
// {
//     var t = $(".list-of-cards"),
//         e = t.find(".load-more"),
//         a = t.find(".categories"),
//         n = t.find(".regions-list"),
//         i = t.find(".cards-grid"),
//         r = t.find(".loader"),
//         s = t.find(".no-results"),
//         l = $(".search-result-title"),
//         o = $(".search-form-container"),
//         c = (o.find("form"), t.attr("data-api-route")),
//         d = !1,
//         u = (o.find("form"), t.attr("data-user-favorite")),
//         f = [],
//         h = [],
//         g = [];
//     $('.region-select-list').click(function()
//     {
//         $.ajax({
//             type: "POST",
//             url: c,
//             data: {
//                 limit: $(".list-pagination").attr("data-limit") ? $(".list-pagination").attr("data-limit") : 10,
//                 categories: h,
//                 regions: $(this).attr('data-id'),
//                 userFavorite: u,
//                 orderby: $("input[name='orderby']").val(),
//                 pixie: $("input[name='search_pixie']").val(),
//                 selectable: d
//             }
//         }).done(function(n) {
//             a ? (i.html(n.html), 1 === parseInt(v()) && $(window).trigger("card-list-reset", [n]), t.hasClass("no-autoscroll") || $("html, body").animate({
//                 scrollTop: i.offset().top - $(".navbar-fixed-top").height()
//             }, 1e3)) : i.append(n.html), e.toggle(10 === n.datas.length), s.toggle(0 === n.datas.length && 1 === v()), $(window).trigger("card-list-updated"), l.length > 0 && l.html("Les Pixies vous proposent <span>" + n.totalItems + "</span> Cards !")
//
//         }).always(function() {
//             r.removeClass("active")
//         })
//     })
// });



    $(document).ready(function()
    {
      $('#logout').click(function()
      {
        window.location.href = "/connexion/signout";
      });
        jQuery('#pc-city-maker-lp').click(function()
        {
            window.location.href = "/devenez-city-maker";
        });

        jQuery('#pc-login-page').click(function()
        {
            window.location.href = "/connexion";
        })
    });

    jQuery(document).ready(function() {

    var followPixieRoute = $("#api-user-routes").attr("data-follow-pixie");
    var favoriteCardRoute = $("#api-user-routes").attr("data-favorite-card");
    var likeCardRoute = $("#api-user-routes").attr("data-like-card");

    $(document).on("click", ".cta-like-card", function(e){
        // e.preventDefault();
        likeCard($(this).attr("data-id"));
    });

    $(window).on("like-card", function(event, id){

        likeCard(id);
    });


    function likeCard(id){
                var isChecked = $(this).hasClass("active");
        var $cta = $(".cta-like-card[data-id='"+id+"']");

        if($("body").hasClass("logged-in")) {
            $.ajax({
                url: "/api/users/like/card",
                                method: "POST",
                data: {
                    card: id
                },
                                success:function(e)
                                {
                                    console.log(isChecked);
                                    // if(isChecked != false){
                                    //  $cta.attr('checked').removeClass('active');
                                    // }
                                    // else
                                    // {
                                    //  $cta.prop('checked', false).addClass('active');
                                    // }

                                }
            });
        }
        else{

                    $("input[name='addLikeCard']").val(id);
                    $("#cardLikeLogoutModal").modal("show");

        }
    }

    $(document).on("click", ".cta-follow-city-maker", function(e){
        e.preventDefault();
        var $cta = $(this);
        var pixieId = $cta.attr("data-id");

        if($("body").hasClass("logged-in")) {

            if ($cta.hasClass("active")) {
                $cta.removeClass("active");
                $('.btn-solid').html('Suivre le City-maker');
            }
            else {
                $cta.addClass("active");
                // $cta.attr("title", "Ne plus suivre").tooltip('fixTitle').tooltip('show');
                $('.btn-solid').html('Ne plus suivre');
            }

            $.ajax({
                url: followPixieRoute,
                                method:"POST",
                data: {
                    pixie: pixieId
                },
                success: function(e)
                {
                    // var message = JSON.parse(e);
                    console.log(e);
                    $('.btn-solid').html(e.msg);
                }
            }).done(function (res) {

            }).always(function () {

            });
        }
        else{
            $("input[name='addFavoritePixie']").val(pixieId);
            $("#followCityMakerModal").modal("show");

        }

    })


    $(document).on("click", ".cta-favorite-card", function(e){
        e.preventDefault();
        favoriteCard($(this).attr("data-id"));
    });

    $(window).on("favorite-card", function(event, id){
        favoriteCard(id);
    });


    function favoriteCard(id){
        var $cta = $(".cta-favorite-card[data-id='"+id+"']");

        if($("body").hasClass("logged-in")) {

            if ($cta.hasClass("active")) {
                $cta.removeClass("active");
                // $cta.attr("title", "Enregistrer la card").tooltip('fixTitle').tooltip('hide');
                // if($cta.find("span").length > 0) $cta.find("span").text(parseInt($cta.find("span").text()) - 1);
            }
            else {
                $cta.addClass("active");
                // $cta.attr("title", "Retirer de mes favoris").tooltip('fixTitle').tooltip('hide');
                // if($cta.find("span").length > 0) $cta.find("span").text(parseInt($cta.find("span").text()) + 1);
            }

                $.ajax({
                url: favoriteCardRoute,
                                method: "POST",
                data: {
                    card: id
                },
                                success:function(e)
                                {

                                    $('.btn-solid').html(message.msg);
                                    if($cta.hasClass('btn-solid'))
                                    {
                                        // $cta.text(message.msg);
                                    }
                                    else
                                    {
                                        $cta.find('p').text(message.msg);
                                    }
                                    
                                }
            }).done(function (res) {
                res = JSON.parse(res);
                if(res.success == true)
                {
                    $('#cardFavoriteModal').modal('show');
                }
            }).always(function () {

            });
        }
        else{

            $("input[name='addFavoriteCard']").val(id);
            $("#cardFavoriteLogoutModal").modal("show");

        }
    }

    $('body').on('click','.dropdown-search > ul > li > a',function()
    {
      $('.is-category-active').removeClass('.is-category-active');
      $(this).addClass('is-category-active');
    });


    $('body').on('click','#close-sidebar',function()
    {
      $('#map-sidebar').html("");
    });
});

jQuery(document).ready(function()
{
    $(document).on('click','#categorry-filter-button', function(e)
    {
        e.preventDefault();
    });

    $(document).on('click','.pc_category_filter', function(e)
    {
        $('#categorry-filter-button > img').attr('src','/img/MAP/'+$(this).attr('data-category-image')+'.svg');
    });

    $('body').tooltip({
    selector: '[data-toggle="tooltip"]'
        });

// var pos
// console.log();
    //---------------------------------------------
    // Share
    //---------------------------------------------

    $('body').on('click','.cta-share-popover', function(e)
    {
        window.Sharer.init();
    });

    // $('html').on('shown.bs.popover', ".cta-share-popover", function (e) {
    //     e.preventDefault();
    //     window.Sharer.init();
    // });

});



// filters
$(document).ready(function()
{



    $('body').on('hidden.bs.modal', function()
    {
        $('.cta-like-card').prop('checked',false);
    });

});


$(document).ready(function(){
    $('a.banner-floater, a.header-strip-sidebar, .opener').click(function(){
        $('.profile-sidebar-container.desktop-version, .profile-sidebar-header-container').show(400);
        $('a.header-strip-sidebar').css('margin-left', '-500px');
        $('.left-column').removeClass('col-md-3').addClass('col-md-4');
        $(window).resize();
    });
    $('.close-profile-sidebar a, .mobile-profile-close-container .close a').click(function(){
        $('.profile-sidebar-container.desktop-version, .profile-sidebar-header-container').hide(400);
        $('a.header-strip-sidebar').css('margin-left', '0px');
        $('.left-column').removeClass('col-md-4').addClass('col-md-3');
    });
    $(window).on("load resize",function(){
    	if(window.matchMedia("(max-width: 767px)").matches){
    			$('a.banner-floater, a.header-strip-sidebar, .opener, .mobile-profile-opener-container .open a').click(function(){
    			$('.profile-sidebar-container.mobile-version').show(400);
    			$('a.header-strip-sidebar').css('margin-left', '-500px');
    			$('.left-column').removeClass('col-md-3').addClass('col-md-4');
    			$('body').css('overflow-y', 'hidden');
    			$(window).resize();
    		});
    		$('.close-profile-sidebar a, .mobile-profile-close-container .close a').click(function(){
    			$('.profile-sidebar-container.mobile-version').hide(400);
    			$('a.header-strip-sidebar').css('margin-left', '0px');
    			$('.left-column').removeClass('col-md-4').addClass('col-md-3');
    			$('body').css('overflow-y', 'auto');
    		});
    	}
    });
    $(window).on("load resize",function(){
        if(window.matchMedia("(min-width: 767px)").matches){
            // Select all links with hashes
            $('a[href*="#"]')
            // Remove links that don't actually link to anything
                .not('[href="#"]')
                .not('[href="#0"]')
                .click(function(event) {
                    // On-page links
                    if (
                        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                        &&
                        location.hostname == this.hostname
                    ) {
                        // Figure out element to scroll to
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                        // Does a scroll target exist?
                        if (target.length) {
                            // Only prevent default if animation is actually gonna happen
                            event.preventDefault();
                            $('html, body').animate({
                                scrollTop: target.offset().top + (-78)
                            }, 1000, function() {
                                // Callback after animation
                                // Must change focus!
                                var $target = $(target);
                                $target.focus();
                                if ($target.is(":focus")) { // Checking if the target was focused
                                    return false;
                                } else {
                                    $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
                                    $target.focus(); // Set focus again
                                };
                            });
                        }
                    }
                });
        }
    });
    /* Banner Slider Images */
    $('.show-slideshow').on('click', function() {
        var images = new Array();
        $.each($('.slider-images'), function(index, image)
        {
            images.push({
                thumb: $(this).attr("data-thumb"),
                src: $(this).attr("data-image"),
                subHtml: '<h4>Crédit Photo: <a href="'+$('#card-gallery').attr('data-instagram-id')+'"  style="color:#fff">@'+$('#card-gallery').attr('data-instagram-id').split('/')[3]+'</a></h4>'
            });
            // console.log($(image).attr('data-image'));
        });

        $(this).lightGallery({
            download: false,
            dynamic: true,
            dynamicEl: images
        });
    });

    /*slider for profile pics*/
		$('#profileSideImageDesktop').slick({
			dots: true,
			arrows: false,
			autoplay: true,
			autoplaySpeed: 3000,
			slidesToShow:1,
			variableWidth: true
		});
		$('#profileSideImageMobile').slick({
			dots: true,
			arrows: false,
			autoplay: true,
			autoplaySpeed: 3000,
			slidesToShow:1,
			variableWidth: true
		});
    /* Speech bubble banner*/
    setTimeout(function(){
        $('.speech-bubble-container').fadeIn('slow');
    },1000);
    $('a.close-speech-bubble').click(function(){
        $('.speech-bubble-container').fadeOut('slow');
    })

});

$(document).ready(function()
{
    $('.social-link').on('click', function(e)
    {
        e.preventDefault();
        console.log('clicked');
        $.ajax('/api/users/outbound-analytics/update',{
            type: 'POST',
            data: {
                'page': window.location.href,
                'cm_id': $('#api-box').attr('data-cm-id')
                },
            success: function(result)
            {
                window.open($('.social-link > a').attr('href'));
            }
        });
    });

    $('.find-city-maker').css('cursor','pointer');
    $('.find-city-maker').click(function()
    {
        window.open('/services');
    });

    $('.landing-ad').css('cursor','pointer');
    $('.landing-ad').click(function()
    {
        window.open('/devenez-city-maker');
    });
});