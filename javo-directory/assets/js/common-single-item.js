jQuery( function($) {
	// Dot Navigation Tooltip Script


	if( typeof $().tooltip != "undefined" )
	{

		jQuery('.awesome-tooltip').tooltip({ placement: 'left' });

		// Thumbnail Section height auto browser size.
		window.javo_single_intro = {
			variable:null
			, resize:function(){
				this.variable = {};
				this.variable.winHeight			 = $(window).height();
				this.variable.adminbarHeight	 = $('#wpadminbar').outerHeight(true);
				this.variable.headerHeight		 = $('#header-line').length > 0 ? $('#header-line').outerHeight() : 0;
				this.variable.navHeight			 = $('.navbar.navbar-default.affix-top').length > 0 ? $('.navbar.navbar-default.affix-top').outerHeight(true) : 0;
				this.variable.containerMargin	 = parseInt( $('#single-intro-section').closest('.single-spy').css('marginTop') );
				this.variable.docHeight			 = ( this.variable.winHeight - this.variable.adminbarHeight );
				$('#single-intro-section').find('.single-item-slider').css('minHeight', this.variable.docHeight);
			}, init:function(){
				$(window).on('resize', this.resize).trigger('resize');
			}
		};

		javo_single_intro.init();
	}
});