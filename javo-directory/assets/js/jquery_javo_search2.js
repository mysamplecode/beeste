/*
* jQuery javo Search Plugin; v1.3.0
* Last Modified	: 2015-01-02
* Copyright (C) 2014 javo
*/

(function($){

	window.javo_search_instance = false;

	var javo_search_func = {

		init: function( attr, el )
		{
			var _attr = $.extend( true, {}, {
				type				: 1
				, ppp				: 10
				, featured			: "image"
				, page				: 1
				, post_type			: "item"
				, meta_term			: false
				, success_callback	: null
				, before_callback	: null
				, start				: true
				, txtKeyword		: $( ".javo-listing-search-field" )
				, btnSubmit			: $( ".javo-listing-submit" )
				, type_toggler		: $( "[name='javo_btn_item_list_type']" )
				, views				: $( "li[data-javo-hmap-ppp]" )
			}, attr);

			this.attr				= _attr;
			this.el					= el;

			if( this.attr.map.val() != '' )
			{
				var em				= $( this.attr.map.val() );
				em					.height( 380 )
			}

			if( this.attr.start ){
				this.run();
			}

			; $( document )
				.on( 'change'	, this.attr.selFilter.selector		, this.trigger_filter )
				.on( 'click'	, '.page-numbers'					, this.trigger_pagination )
				.on( 'keypress'	, this.attr.txtKeyword.selector		, this.trigger_keyword )
				.on( 'click'	, this.attr.btnSubmit.selector		, this.trigger_search_button )
				.on( 'change'	, this.attr.type_toggler.selector	, this.swap_type )
				.on( 'click'	, this.attr.views.selector			, this.swap_views )
				.on( 'click'	, '[data-listing-map-move-allow]'	, this.disable_map_drag )


			; window.javo_search_instance = true;
		}

		, trigger_filter: function( e )
		{
			e.preventDefault();
			var obj = javo_search_func;
			obj.run();
		}

		, trigger_pagination: function( e )
		{
			e.preventDefault();
			var obj			= javo_search_func;
			var options		=  obj.attr.param;
			var pn			= $(this).attr("href").split("=");
			pn				= parseInt( pn[1] );

			options.page	= !isNaN( pn ) ? pn : 1;
			obj.run();
		}

		, trigger_keyword: function( e )
		{
			var obj			= javo_search_func;
			var options		=  obj.attr.param;

			if( e.keyCode == 13 )
			{
				options.page = 1;
				obj.run();
				e.preventDefault();
			}
		}

		, trigger_search_button: function( e )
		{
			e.preventDefault();

			var obj			= javo_search_func;
			var options		=  obj.attr.param;
			options.page	= 1;
			obj.run();
		}

		, swap_type: function( e )
		{
			e.preventDefault();
			var obj			= javo_search_func;
			var options		=  obj.attr.param;
			var type		= parseInt( $( this ).val() );

			if( ! isNaN( type ) )
			{
				options.type = type;
				obj.run();
			}
		}

		, swap_views: function( e )
		{
			e.preventDefault();
			var obj			= javo_search_func;
			var options		=  obj.attr.param;
			var views		= parseInt( $( this ).val() );

			if( ! isNaN( views ) )
			{
				options.ppp	= views;
				obj.run();
			}
		}

		, disable_map_drag: function( e )
		{
			e.preventDefault();
			var obj			= javo_search_func;
			var attr		= obj.attr;

			var $map = $( attr.map.val() ).gmap3('get');

			$(this).toggleClass('active');

			if( $(this).hasClass('active') )
			{
				// Allow
				$map.setOptions({draggable:true, scrollwheel: true});
				$(this).find('i').removeClass('fa fa-lock').addClass('fa fa-unlock');
			}
			else
			{
				// Not Allowed
				$map.setOptions({draggable:false, scrollwheel: false});
				$(this).find('i').removeClass('fa fa-unlock').addClass('fa fa-lock');
			}
		}

		, run: function( param )
		{
			var obj			= this;
			var data		= {};
			var attr		= obj.attr;
			var param		= param || attr.param;

			// Ajax Setup
			param.action	= 'post_list';

			if( typeof attr.before_callback == 'function' ){
				attr.before_callback();
			}

			if( typeof( attr.selFilter ) != "undefined" )
			{
				$.each( attr.selFilter, function(){
					if( this.value != "" && this.value > 0)
					{
						var n = this.name.replace("]", "").split("[")[1];
						data[n] = this.value;
					};
				});
				param.tax = data;
			};
			if( typeof( attr.txtKeyword) != "undefined" )
			{
				param.keyword = attr.txtKeyword.val();
			};

			var xhr;
			if( window.XMLHttpRequest )
			{
				xhr = new window.XMLHttpRequest();
			}else{
				xhr = new ActiveXObject( "Microsoft.XMLHTTP" );
			}

			xhr.onreadystatechange = function()
			{
				var response;
				if( xhr.readyState == 4 && xhr.status == 200 )
				{
					response = JSON.parse( xhr.responseText );

					console.log( xhr.responseText );

					alert('ok');
				}
			}

			xhr.addEventListener( "progress", function( e )
			{
				console.log( e.loaded );

			}, false);

			xhr.open( 'POST', obj.attr.url, true );
			xhr.setRequestHeader( 'Content-Type', 'application/json; charset=UTF-8');
			xhr.send( 'action=post_list' );

			console.log( JSON.stringify( param ) );

			console.log( obj.attr.url );


		}
	};

	$.fn.javo_search = function( attr )
	{
		if( !window.javo_search_instance )
		{
			var el = $(this);
			javo_search_func.init( attr, el );
		}
	}
})(jQuery);