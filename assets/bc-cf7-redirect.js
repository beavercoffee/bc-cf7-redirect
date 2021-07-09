if('undefined' === typeof(bc_cf7_redirect)){
    var bc_cf7_redirect = {

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        handle_redirect: function(event){
            if(event.detail.apiResponse.bc_redirect){
                jQuery(location).attr('href', event.detail.apiResponse.bc_redirect);
            }
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        init: function(){
            jQuery('.wpcf7-form').on('wpcf7reset', bc_cf7_redirect.handle_redirect);
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    };
}
