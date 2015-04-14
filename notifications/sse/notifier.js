(function () {
    var jQueryScriptOutputted = false, openingTunnel = true;
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

            $(function () {

                var uid = "UID" + new Date().getTime() + Math.random().toString().replace('.', '');
                var source = new EventSource("/weberp/notifications/sse/ssserver.php?uid=" + uid);

                source.onopen = function (event) {
                    $.get('/weberp/notifications/api/status.php', function (data) {

                        $('#notifications-link i')[data.count > 0 ? 'show' : 'hide']()
                            .text(data.count)
                            .css({
                                'border-radius': '25px',
                                'padding': '4px 9px',
                                'background-color': '#F88529',
                                'margin-left': '5px',
                                'font-style': 'normal',
                                'font-size': '11px'
                            });
                    });
                };
                source.onmessage = function (event) {
                    if (openingTunnel) return openingTunnel = false;
                    $.get('/weberp/notifications/api/feed.php', function (data) {
                        
                        $('#notifications-link i')[data.count > 0 ? 'show' : 'hide']()
                            .text(data.count);
                    });
                };
            });
        }

    }
    initJQuery();


})();