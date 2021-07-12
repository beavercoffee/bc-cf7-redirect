if('undefined' === typeof(bc_cf7_redirect)){
    var bc_cf7_redirect = {

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        do_redirect: function(){
            if(bc_cf7_redirect.redirect){
                setTimeout(function(){
                    jQuery(location).attr('href', bc_cf7_redirect.redirect);
                }, 1000);
            }
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        init: function(){
            jQuery('.wpcf7-form').on({
				'wpcf7mailsent': bc_cf7_redirect.set_redirect,
				'wpcf7reset': bc_cf7_redirect.do_redirect,
			});
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        redirect: '',

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        set_redirect: function(event){
            if(event.detail.apiResponse.bc_redirect){
                bc_cf7_redirect.redirect = event.detail.apiResponse.bc_redirect;
            }
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    };
}
