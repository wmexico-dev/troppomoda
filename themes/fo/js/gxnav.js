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
    if (settings.transitionOpacity === true) {
      $nav.addClass('opacity');
    }
    $nav.find(">li").each(function() {
    	$(this).find(">ul").wrap("<div></div>");
      	if ($(this).has("div").length) {
			return $(this).addClass("with-submenu").find(">div").hide();
      	}
    });
    $nav.addClass('with-js').show();
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
          return $(this).find('>div').addClass('gxnav-show').stop(true, true).animate({
            height: ["show", "swing"],
            opacity: "show"
          }, settings.animationSpeed);
        } else {
          return $(this).find('>div').addClass('gxnav-show').stop(true, true).animate({
            height: ["show", "swing"]
          }, settings.animationSpeed);
        }
      }
    };
    resetMenu = function() {
      if ($nav.hasClass('lg-screen') === true && $(this).find('>div').hasClass('gxnav-show') === true && settings.hover === true) {
        if (settings.transitionOpacity === true) {
          return $(this).find('>div').removeClass('gxnav-show').stop(true, true).animate({
            height: ["hide", "swing"],
            opacity: "hide"
          }, settings.animationSpeed);
        } else {
          return $(this).find('>div').removeClass('gxnav-show').stop(true, true).animate({
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
        $nav.removeClass('gxnav-show').find('.with-submenu').on();
        $nav.find('>div').removeClass('gxnav-show').hide();
        resetMenu();
        if (settings.hoverIntent === true) {
          return $('.with-submenu').hoverIntent({
            over: showMenu,
            out: resetMenu,
            timeout: settings.hoverIntentTimeout
          });
        } else {
          return $('.with-submenu').on('mouseenter', showMenu).on('mouseleave', resetMenu);
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
    touch_selector = '.with-submenu, ' + settings['menuSelector'];
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
      $sub = $(this).parent('.with-submenu').find('>div');
      $touchButton = $(this).parent('.with-submenu').find('>span.gxnav-touch');
      if ($nav.hasClass('lg-screen') === true) {
        $(this).parent('.with-submenu').siblings().find('div.gxnav-show').removeClass('gxnav-show').hide();
      }
      if ($sub.hasClass('gxnav-show') === true) {
        $sub.removeClass('gxnav-show').slideUp(settings.animationSpeed);
        return $touchButton.removeClass('active');
      } else {
        $sub.addClass('gxnav-show').slideDown(settings.animationSpeed);
        return $touchButton.addClass('active');
      }
    });
    $nav.find('.with-submenu *').focus(function() {
      $(this).parent('.with-submenu').parent().find(".open").not(this).removeClass("open").hide();
      return $(this).parent('.with-submenu').find('>div').addClass("open").show();
    });
    resizer();
    return $(window).on('resize', resizer);
  };

}).call(this);
