$(function () {
    var openingTunnel = true;
    var uid = "UID" + new Date().getTime() + Math.random().toString().replace('.', '');
    var source = new EventSource("/weberp/notifications/sse/ssserver.php?uid=" + uid);


    function onopen(event) {
        toastr.options.closeButton = true;
        toastr.options.positionClass = "toast-bottom-left";
        toastr.options.newestOnTop = false;
        toastr.options.timeOut = 7000;
        $.get('/weberp/notifications/api/status.php', function (data) {
            $('#notifications-link i')[data.count > 0 ? 'show' : 'hide']()
                .text(data.count);
        });
    }

    function onmessage(event) {

        $.get('/weberp/notifications/api/feed.php', { current: $('#notifications-link i').text() }, function (data) {

            $('#notifications-link i')[data.count > 0 ? 'show' : 'hide']()
                .text(data.count);

            $.each(data.notifications, function (idx, ntf) {
                if (idx < 3)
                    toastr.info(ntf.message);
            });
            if (data.notifications.length > 3)
                toastr.warning('Hay mas notificaciones pendientes!');

        });

        $(document).trigger('newNotification');

    }

    source.onmessage = function (event) {
        if (openingTunnel) {
            onopen(event);
            openingTunnel = false;
        }
        else
            onmessage(event);

    };



    $(document).on('click', ':submit[notify]', function (e) {


        var sbmt = $(this);

        if (sbmt.attr('has-notified') != undefined) return;

        e.preventDefault();
        e.returnValue = false;

        $.ajax({
            url: '/weberp/notifications/sse/notify.php?type=' + sbmt.attr('notify'),
            type: 'POST',
            data: sbmt.parent('form').serialize(),
            complete: function () {
                sbmt.attr('has-notified', true);
                sbmt.click();
            }
        });
    });

});
