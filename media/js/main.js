// Enclose script in an anonymous function to prevent global namespace polution
( function( $ ){

	"use strict";

	// Show flexslider controls when hovering
	var show_flex_controls = function() {
		$('.flex-prev, .flex-next').animate( {'opacity' : 1 }, 400 );
	}, 

	// Hide flexslider when not hovering anymore
	hide_flex_controls = function() {
		$('.flex-prev, .flex-next').animate( {'opacity' : 0 }, 400 );
	},

	// Toggle search field 
	toggle_search_field = function(event, $el) {
		$el.toggleClass('active');
		if ( $el.hasClass( 'active' ) ) {
			$('#s').animate( { 'opacity' : 1 }, 400 );
			event.preventDefault();	
		}
	},

	// Load more posts via AJAX
	load_more_ajax = function(event, $el) {
		// Stop the default action
		event.preventDefault();
		
		var url = $el.attr('href'), 
			$nav = $el.parent();
		
		$nav.html('<i class="icon-spinner icon-spin icon-4x"></i>');  

		$('<div>').load( url + ' #posts', function() {

			// Load up next posts
			var $posts = $('<div>').hide().html( $(this).find("#posts").wrap('<div />').html() );

			$("#posts").append( $posts );

			// Fade it in
			$posts.fadeIn(1400, 'swing', function() {

				// Load up next pagination link
				$nav.parent().load(url + ' .nav-more');

				// Attach handlers
				attach_handlers($posts);
			} );			
		} );
	},

	// Initiate jPlayer instance
	initiate_jplayer = function($player) {
		var container_id = "#" + $player.next().attr('id');
		
		$player.jPlayer( {
			ready: function () {
				$player.jPlayer("setMedia", $player.data().formats);
			},	
			// Stop other players playing at the same time		
			play: function() {
				$player.jPlayer("pauseOthers");
			},
			swfPath: $player.data().swfPath,
			supplied: $player.data().supplied,
			cssSelectorAncestor: container_id
		} );
	},

	// Attach handlers
	attach_handlers = function($container) {

		// Flexslider
		$container.find('.flexslider')
			.flexslider({ animation: "slide" })
			.mouseenter( function() { show_flex_controls(); } )
			.mouseleave( function() { hide_flex_controls(); } );

		// Responsive video sizing
		$container.find('.fit_video').fitVids();

		// Get all jPlayer divs
		var $players = $container.find(".jp-jplayer");

		// For each jPlayer div, we need to first 
		// store the div element, so that we can grab
		// its data store when setting the instance 
		// settings. 
		$players.each( function() {
			initiate_jplayer( $(this) );			
		} );
	},

	// Ensure placeholder suppport
	cross_browser_placeholder = function() {
		var $form_elements = $(':text,textarea');

		// Make sure there are text inputs
		if ( $form_elements.length ) {

			// Only proceed if placeholder isn't supported
			if ( ! ( 'placeholder' in $form_elements.first()[0] ) ) {
				var active = document.activeElement;

				$form_elements.focus( function() {
					if ( $(this).attr('placeholder') !== '' && $(this).val() === $(this).attr('placeholder') ) {
						$(this).val('').removeClass('hasPlaceholder');
					}
				}).blur( function() {
					if ( $(this).attr('placeholder') !== '' && ($(this).val() === '' || $(this).val() === $(this).attr('placeholder'))) {
						$(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
					}
				});
				$form_elements.blur();
				$(active).focus();
				$('form').submit(function () {
					$(this).find('.hasPlaceholder').each(function() { $(this).val(''); });
				});
			}
		}
	};

	// Run actions when the page is ready for it
	$(document).ready( function() {

		var $body = $('body');

		// Add "placeholder" support for IE9 and below
		cross_browser_placeholder();		

		// Load next posts
		$body.on('click', '.ajax-next', function(event) {
			load_more_ajax(event, $(this));
		})
		// Scroll top
		$body.on('click', '.scroll_top', function() { 
			$('html,body').animate({ 'scrollTop' : '0px' }, 400 );
		} );		

		// Search animation
		$('#searchform button').on('click', function(event) { toggle_search_field( event, $(this) ); });

		// Dropdown menus
		$('#primary_navigation ul').dropdownMenu({ alignment : 'left' });

		// Sharing widget
        if ( $.fn.sharrre ) {
			$('.sharrre').sharrre({
				share: {
					twitter: true,
					facebook: true,
					googlePlus: true
				},
				template: '<div class="box"><div class="left">' + Sofa_Localized.sharrre_text + '</div><div class="middle"><a href="#" class="facebook"><i class="icon-facebook"></i></a><a href="#" class="twitter"><i class="icon-twitter"></i></a><a href="#" class="googleplus"><i class="icon-google-plus"></i></a></div><div class="right">{total}</div></div>',
				enableHover: false,
				enableTracking: true,
				urlCurl: Sofa_Localized.sharrre_url,
				render: function(api) {
					$(api.element).on('click', '.twitter', function() {
						api.openPopup('twitter');
					});
					$(api.element).on('click', '.facebook', function() {
						api.openPopup('facebook');
					});
					$(api.element).on('click', '.googleplus', function() {
						api.openPopup('googlePlus');
					});
				},
				url: $(this).data().url
			});
        }		

		// Responsive menu
		$(".responsive_menu").flexNav({ breakpoint : 600 });		

		// RoundRect (for IE7&8)		
		if ('PIE' in window) {
			$('.logo, #s').each( function() {
				PIE.attach(this);
			});
		}
		
		attach_handlers( $body );
	});

	// Run actions when window is loaded
	$(window).load( function() {

		var colorbox_defaults = { 
			opacity: '0.8', 
			maxWidth: '90%', 
			maxHeight: '90%', 
			initialWidth: '100px', 
			initialHeight : '100px',
			previous : '<i class="icon-arrow-left"></i>',
			next : '<i class="icon-arrow-right"></i>',
			close : '<i class="icon-remove"></i>',
			slideshowStop : '<i class="icon-pause"></i>',
			slideshowStart : '<i class="icon-play"></i>',
			slideshow : false, 
			rel : false };

		// Individual Colorbox photos
		$('a.colorbox').colorbox( $.extend( colorbox_defaults, $(this).data() ) );
		
		// Colorbox galleries
		$('.colorbox_gallery, .gallery').each( function() {
			$(this).find('a').colorbox( $.extend( colorbox_defaults, {
				rel : $(this).data().album || 'colorbox_gallery',
				slideshow : $(this).data().slideshow
			}));
		});

		if ( window.addEventListener ) {
			window.addEventListener("orientationchange", function() {
				if($('#cboxOverlay').is(':visible')){
					$.colorbox.load(true);
				}
			}, false);
		}			

		// Image hover
		$('a.colorbox img, .colorbox_gallery img, .gallery img').imageHover();		
	});

})( jQuery );