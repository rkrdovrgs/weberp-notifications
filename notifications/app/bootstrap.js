$(function () {

    $.get('../index.php?Application=notifications', function (htmlStr) {


        var $html = $('<div></div>').append(htmlStr);

        if ($html.find('title').text() === 'webERP Login screen')
            window.location = '../';

        var $canvasDiv = $html.find('#CanvasDiv');
        $canvasDiv.find('#SubMenuDiv').html('')
            .attr('ng-view', '')
            .attr('ng-show', '!isViewLoading')
            .addClass('view-container shuffle-animation');
        $('head').prepend($html.find('script,link'));
        $('body').html($canvasDiv.html());

        $('#QuickMenuDiv a[href*="notifications"]').addClass('menu_selected').attr('href', '#/');

        angular.bootstrap(document, ['app']);

    });

});
