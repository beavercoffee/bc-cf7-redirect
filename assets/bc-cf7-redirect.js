if(typeof bc_cf7_redirect === 'undefined'){
    var bc_cf7_redirect = {

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        add_query_arg: function(key, value, url){
            var a = {}, href = '', search = [], search_object = {};
            a = document.createElement('a');
            if(url === ''){
                a.href = jQuery(location).attr('href');
            } else {
                a.href = url;
            }
            if(a.protocol){
                href += a.protocol + '//';
            }
            if(a.hostname){
                href += a.hostname;
            }
            if(a.port){
                href += ':' + a.port;
            }
            if(a.pathname){
                if(a.pathname[0] !== '/'){
                    href += '/';
                }
                href += a.pathname;
            }
            if(a.search){
                search_object = bc_cf7_redirect.parse_str(a.search);
                jQuery.each(search_object, function(k, v){
                    if(k != key){
                        search.push(k + '=' + v);
                    }
                });
                if(search.length > 0){
                    href += '?' + search.join('&') + '&';
                } else {
                    href += '?';
                }
            } else {
                href += '?';
            }
            href += key + '=' + value;
            if(a.hash){
                href += a.hash;
            }
            return href;
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        init: function(){
            jQuery('.wpcf7-form').on({
                wpcf7mailsent: bc_cf7_redirect.wpcf7mailsent,
                wpcf7reset: bc_cf7_redirect.wpcf7reset,
            });
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        parse_str: function(){
            var i = 0, search_object = {}, search_array = [];
            search_array = str.replace('?', '').split('&');
            for(i = 0; i < search_array.length; i ++){
                search_object[search_array[i].split('=')[0]] = search_array[i].split('=')[1];
            }
            return search_object;
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        redirect: function(event){
            var redirect = '';
            if('undefined' !== typeof(bc_cf7_redirects[event.detail.contactFormId])){
                redirect = bc_cf7_redirects[event.detail.contactFormId];
                if(redirect === ''){
                    redirect = jQuery(location).attr('href');
                }
            }
            return redirect;
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        wpcf7mailsent: function(event){
            var redirect = '';
            redirect = bc_cf7_redirect.redirect(event);
            if('' !== redirect){
                jQuery(this).find('form').children().not('.wpcf7-response-output').hide();
            }
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        wpcf7reset: function(event){
            var redirect = '';
            redirect = bc_cf7_redirect.redirect(event);
            if('' !== redirect){
                if(jQuery(this).find('input[name="bc_uniqid"]').length){
                    redirect = bc_cf7_redirect.add_query_arg('bc_referer', jQuery(this).find('input[name="bc_uniqid"]').val(), redirect);
                }
                jQuery(location).attr('href', redirect);
            }
        },

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    };
}