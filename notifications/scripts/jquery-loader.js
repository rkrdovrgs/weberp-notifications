var $ = $ || function (onReady) {
    var jQueryScriptOutputted = false;
    function initJQuery() {

        //if the jQuery object isn't available
        if (typeof (jQuery) == 'undefined') {

            if (!jQueryScriptOutputted) {
                //only output the script once..
                jQueryScriptOutputted = true;
                document.write("<scr" + "ipt type='text/javascript' src='/weberp/notifications/bower_components/jquery/dist/jquery.js'></scr" + "ipt>");
            }
            setTimeout(function () { initJQuery(); }, 500);
        } else {

            $(onReady());
        }

    }
    initJQuery();
};