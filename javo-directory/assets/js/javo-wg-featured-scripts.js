( function( $ ) {

	var javo_wgfi = function( o ) {

		this.options = $.extend( true, {}, {
			rating:{
				container	: '.javo-rating-registed-score'
				, starOff	: '../images/star-off-s.png'
				, starOn	: '../images/star-on-s.png'
				, starHalf	: '../images/star-half-s.png'
			}
		}, o );

		if( ! window.__JAVO_WGFI__ ) {
			window.__JAVO_WGFI__ = true;
			this.init();
		}
	}

	javo_wgfi.prototype = {

		constructor		: javo_wgfi

		, init			: function()
		{
			// Set Rating
			this.setRating();
		}

		, setRating		: function()
		{
			var opt		= this.options.rating;
			$( opt.container ).each( function( k,  v)
			{
				$(this).raty({
					starOff		: opt.starOff
					, starOn	: opt.starOn
					, starHalf	: opt.starHalf
					, half		: true
					, readOnly	: true
					, score		: $(this).data('score')
				}).css('width', '');
			});
		}
	}

	window.javo_wgfi = javo_wgfi;

} )( jQuery );
