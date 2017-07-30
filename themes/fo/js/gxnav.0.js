/*
//
*/

(function() {
  var $;
  $ = jQuery;

  $.fn.gxNav = function(options) {
    var $nav, $top_nav_items, breakpoint, count, nav_percent, nav_width, resetMenu, resizer, settings, showMenu, toggle_selector, touch_selector;
    settings = $.extend({
      'animationSpeed': 250,
      'transitionOpacity': true,
      'menuSelector': '.gxnav-menu',
      'hoverIntent': false,
      'hoverIntentTimeout': 150,
      'calcItemWidths': false,
      'breakpoint': 800,
      'hover': true
    }, options);
    $nav = $(this);
    $nav.addClass('with-js');
    if (settings.transitionOpacity === true) {
      $nav.addClass('opacity');
    }
    $nav.find("li").each(function() {
      if ($(this).has("ul").length) {
        return $(this).addClass("item-with-ul").find("ul").hide();
      }
    });
    if (settings.calcItemWidths === true) {
      $top_nav_items = $nav.find('>li');
      count = $top_nav_items.length;
      nav_width = 100 / count;
      nav_percent = nav_width + "%";
    }
    if ($nav.data('breakpoint')) {
      breakpoint = $nav.data('breakpoint');
    } else {
      breakpoint = settings.breakpoint;
    }
    showMenu = function() {
      if ($nav.hasClass('lg-screen') === true && settings.hover === true) {
        if (settings.transitionOpacity === true) {
          return $(this).find('>ul').addClass('gxnav-show').stop(true, true).animate({
            height: ["show", "swing"],
            opacity: "show"
          }, settings.animationSpeed);
        } else {
          return $(this).find('>ul').addClass('gxnav-show').stop(true, true).animate({
            height: ["show", "swing"]
          }, settings.animationSpeed);
        }
      }
    };
    resetMenu = function() {
      if ($nav.hasClass('lg-screen') === true && $(this).find('>ul').hasClass('gxnav-show') === true && settings.hover === true) {
        if (settings.transitionOpacity === true) {
          return $(this).find('>ul').removeClass('gxnav-show').stop(true, true).animate({
            height: ["hide", "swing"],
            opacity: "hide"
          }, settings.animationSpeed);
        } else {
          return $(this).find('>ul').removeClass('gxnav-show').stop(true, true).animate({
            height: ["hide", "swing"]
          }, settings.animationSpeed);
        }
      }
    };
    resizer = function() {
      var selector;
      if ($(window).width() > breakpoint) {
        $nav.removeClass("sm-screen").addClass("lg-screen");
        if (settings.calcItemWidths === true) {
          $top_nav_items.css('width', nav_percent);
        }
        $nav.removeClass('gxnav-show').find('.item-with-ul').on();
        $nav.find('ul').removeClass('gxnav-show').hide();
        resetMenu();
        if (settings.hoverIntent === true) {
          return $('.item-with-ul').hoverIntent({
            over: showMenu,
            out: resetMenu,
            timeout: settings.hoverIntentTimeout
          });
        } else {
          return $('.item-with-ul').on('mouseenter', showMenu).on('mouseleave', resetMenu);
        }
      } else {
      	$nav.removeClass("lg-screen").addClass("sm-screen");
        if (settings.calcItemWidths === true) {
          $top_nav_items.css('width', '100%');
        }
        selector = settings['menuSelector'] + ', ' + settings['menuSelector'] + ' .gxnav-touch';
        $(selector).removeClass('active');
      }
    };
    $(settings['menuSelector']).data('navEl', $nav);
    touch_selector = '.item-with-ul, ' + settings['menuSelector'];
    $(touch_selector).append('<span class="gxnav-touch"></span>');
    toggle_selector = settings['menuSelector'] + ', ' + settings['menuSelector'] + ' .gxnav-touch';
    $(toggle_selector).on('click', function(e) {
      var $btnParent, $thisNav, bs;
      $(toggle_selector).toggleClass('active');
      e.preventDefault();
      e.stopPropagation();
      bs = settings['menuSelector'];
      $btnParent = $(this).is(bs) ? $(this) : $(this).parent(bs);
      $thisNav = $btnParent.data('navEl');
      return $thisNav.toggleClass('gxnav-show');
    });
    $('.gxnav-touch').on('click', function(e) {
      var $sub, $touchButton;
      $sub = $(this).parent('.item-with-ul').find('>ul');
      $touchButton = $(this).parent('.item-with-ul').find('>span.gxnav-touch');
      if ($nav.hasClass('lg-screen') === true) {
        $(this).parent('.item-with-ul').siblings().find('ul.gxnav-show').removeClass('gxnav-show').hide();
      }
      if ($sub.hasClass('gxnav-show') === true) {
        $sub.removeClass('gxnav-show').slideUp(settings.animationSpeed);
        return $touchButton.removeClass('active');
      } else {
        $sub.addClass('gxnav-show').slideDown(settings.animationSpeed);
        return $touchButton.addClass('active');
      }
    });
    $nav.find('.item-with-ul *').focus(function() {
      $(this).parent('.item-with-ul').parent().find(".open").not(this).removeClass("open").hide();
      return $(this).parent('.item-with-ul').find('>ul').addClass("open").show();
    });
    resizer();
    return $(window).on('resize', resizer);
  };

}).call(this);
