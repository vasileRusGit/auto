// cookie
$(function () {
    var cc = $.cookie('list_grid');
    if (cc === 'g') {
        $('#products .item').addClass('grid-group-item');
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('padding_grid');
    } else {
        $('#products .item').removeClass('grid-group-item');
        $('#products .item').addClass('list-group-item');
        $('#products .item').removeClass('padding_grid');
    }
});

$(document).ready(function () {
    $('#grid').click(function (event) {
        event.preventDefault();
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('grid-group-item');
        $('#products .item').addClass('padding_grid');

        $.cookie('list_grid', 'g');
    });
    $('#list').click(function (event) {
        event.preventDefault();
        $('#products .item').removeClass('grid-group-item');
        $('#products .item').addClass('list-group-item');
        $('#products .item').removeClass('padding_grid');

        $.cookie('list_grid', null);
    });

    // add loader to the pagination
    $('#pagination a').click(function () {
        // get current page class
        var myClass = $('#test').attr('class');
        console.log(myClass);

        var loading = new $.LoadingBox({
            loadingImageSrc: "bundles/admin/images/default.gif"
        });
        loading.close();
    });
});