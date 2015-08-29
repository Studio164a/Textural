 /**
  * This file adds some LIVE to the Theme Customizer live preview.
  */
( function( $ ) {	

	// Update the site title in real time...
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '.site_title a' ).html( newval );
		} );
	} );
	
	// Update the site description in real time...
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.description' ).html( newval );
		} );
	} );

	// Update the logo in real time...
	wp.customize( 'logo_url', function( value ) {		
		value.bind( function( newval ) {			

			// Get the image dimensions
			var img = new Image();
			img.src = newval;
			img.onload = function() {
				if ( newval.length > 0 ) {
					$('.logo').css( {
						'background-image' : 'url('+newval+')', 
						'width' : img.width,
						'height' : img.height
					} );
				}			
				else {
					$('.logo').css( {
						'background-image' : 'url('+OSFA.theme_dir + '/media/images/icon_white.png)',
						'background-color' : OSFA.accent_colour,
						'border-radius' : '83px', 
						'width' : '83px', 
						'height' : '83px'
					} );
				}
			}			
		} ); 
	} );

	// Hide the site title
	wp.customize( 'hide_site_title', function( value ) {
		value.bind( function( newval ) {
			$( '.site_title' ).toggleClass('hidden', newval);
		} );
	} );

	// Hide the site description
	wp.customize( 'hide_site_tagline', function( value ) {
		value.bind( function( newval ) {
			$( '.description' ).toggleClass('hidden', newval);
		} );
	} );

	//  Update accent colour
	wp.customize( 'accent_colour', function( value ) {
		value.bind( function( newval ) {			
			OSFA.accent_colour = newval;

			$('.highlight, .button, button, input[type="submit"], input[type="reset"], input[type="button"]').css('background-color', newval);
			$('.flex-prev').css('background-color', newval);
			$('.flex-next').css('background-color', newval);

    		$('article h1, article h2, article h3').css({ 'color' : newval });

    		$('a, .button, input[type="submit"], button, input[type="reset"], .sharre .middle a')
    			.mouseenter( function() { $(this).css('background-color', newval); })
    			.mouseleave( function() { $(this).css('background-color', ''); });

    		$('#primary_navigation a')
    			.mouseenter( function() { $(this).css({ 'color' : newval, 'background-color' : 'transparent'} ); } )
    			.mouseleave( function() { $(this).css('color', ''); } );
		} );
	} );
	
	// Update background skin
	wp.customize( 'skin', function( value ) {
		value.bind( function( newval ) {
		 	$('#skin-css').attr('href', OSFA.theme_dir + '/media/css/' + newval + '.css');
		} );
	} );

	// Hide or show search
	wp.customize( 'show_search', function( value ) {
		value.bind( function( newval ) {
			$('#header > #searchform').toggleClass('hidden', !newval);
		} ); 
	} );
	
	//
	// Social networks
	//

	// Behance
	wp.customize( 'behance', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .behance');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="behance" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .behance').remove();
			}
		} );
	} );

	// Blogger
	wp.customize( 'blogger', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .blogger');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="blogger" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .blogger').remove();
			}
		} );
	} );	

	// DeviantART
	wp.customize( 'deviant', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .deviant');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="deviant" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .deviant').remove();
			}
		} );
	} );
	
	// Digg
	wp.customize( 'digg', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .digg');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="digg" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .digg').remove();
			}
		} );
	} );
	
	// Dribbble
	wp.customize( 'dribbble', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .dribbble');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="dribbble" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .dribbble').remove();
			}
		} );
	} );
	
	// Facebook
	wp.customize( 'facebook', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .facebook');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="facebook" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .facebook').remove();
			}
		} );
	} );

	// Flickr
	wp.customize( 'flickr', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .flickr');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="flickr" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .flickr').remove();
			}
		} );
	} );

	// Forrst
	wp.customize( 'forrst', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .forrst');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="forrst" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .forrst').remove();
			}
		} );
	} );

	// Google Plus
	wp.customize( 'gplus', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .gplus');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="gplus" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .gplus').remove();
			}
		} );
	} );

	// Linked IN
	wp.customize( 'linkedin', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .linkedin');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="linkedin" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .linkedin').remove();
			}
		} );
	} );

	// Myspace
	wp.customize( 'myspace', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .myspace');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="myspace" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .myspace').remove();
			}
		} );
	} );

	// Orkut
	wp.customize( 'orkut', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .orkut');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="orkut" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .orkut').remove();
			}
		} );
	} );

	// Pinterest
	wp.customize( 'pinterest', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .pinterest');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="pinterest" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .pinterest').remove();
			}
		} );
	} );

	// RSS
	wp.customize( 'rss', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .rss');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="rss" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .rss').remove();
			}
		} );
	} );		

	// Stumbleupon
	wp.customize( 'stumbleupon', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .stumbleupon');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="stumbleupon" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .stumbleupon').remove();
			}
		} );
	} );	

	// Tumblr
	wp.customize( 'tumblr', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .tumblr');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="tumblr" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .tumblr').remove();
			}
		} );
	} );

	// Twitter
	wp.customize( 'twitter', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .twitter');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="twitter" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .twitter').remove();
			}
		} );
	} );

	// Viddler
	wp.customize( 'viddler', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .viddler');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="viddler" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .viddler').remove();
			}
		} );
	} );

	// Vimeo
	wp.customize( 'vimeo', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .vimeo');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="vimeo" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .vimeo').remove();
			}
		} );
	} );

	// Youtube
	wp.customize( 'youtube', function( value ) {
		value.bind( function( newval ) {
			if ( newval.length > 0 ) {
				var has_icon = $('.social .youtube');

				if ( has_icon.length ) {
					has_icon.find('a').attr('href', newval);
				}
				else {
					$('.social').append('<li><a class="youtube" href="'+newval+'"></a></li>');
				}
			}
			else {
				$('.social .youtube').remove();
			}
		} );
	} );

} )( jQuery ); 