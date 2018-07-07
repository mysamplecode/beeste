;( function( $ ){

	var javo_single_house_func = function( a )
	{
		this.map_options	= {
			map:{
				options:{
					mapTypeId: google.maps.MapTypeId.ROADMAP
					, mapTypeControl	: true
					, panControl		: false
					, scrollwheel		: true
					, streetViewControl	: true
					, zoomControl		: true
					, zoomControlOptions: {
						position: google.maps.ControlPosition.RIGHT_BOTTOM
						, style: google.maps.ZoomControlStyle.SMALL
					}
				}
				, events:{
					click: function(){
						var obj = window.javo_map_box_func;
						obj.close_ib_box();
					}
				}
			}		
		};
		this.options		= $.extend( {}, {
			map_el			: null
		}, a);
		this.init();
	}

	javo_single_house_func.prototype = {
		constructor			: javo_single_house_func

		, init				: function()
		{
			this.setMap();


		}

		, setMap			: function()
		{
			var el = this.options.map_el;

			el
				.height(500)
				.gmap3( this.map_options );

		}
	}

	$.jv_single_property = function( opt ) {
		new javo_single_house_func( opt );
	}

} )( jQuery );
