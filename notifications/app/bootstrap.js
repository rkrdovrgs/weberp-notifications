$(function () {

    $.get('../index.php?Application=notifications', function (htmlStr) {


        var $html = $('<div></div>').append(htmlStr);

        if ($html.find('title').text() === 'webERP Login screen')
            window.location = '../';

        var $canvasDiv = $html.find('#CanvasDiv');
        $canvasDiv.find('#SubMenuDiv').html('').attr('ng-view', '');
        $('head').prepend($html.find('script,link'));
        $('body').html($canvasDiv.html());

        $('#QuickMenuDiv a[href*="notifications"]').addClass('menu_selected');

        angular.bootstrap(document, ['app']);

    });

});
