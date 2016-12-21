$(document).ready(function () {
    $('#products .item').addClass('list-group-item');
    $('#grid').click(function (event) {
        event.preventDefault();
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('grid-group-item');
        $('#products .item').addClass('padding_grid');
    });
    $('#list').click(function (event) {
        event.preventDefault();
        $('#products .item').removeClass('grid-group-item');
        $('#products .item').addClass('list-group-item');
        $('#products .item').removeClass('padding_grid');
    });

    //get current page class
    var myClass = $('#test').attr('class');
    console.log(myClass);
    // add loader to the pagination
    $('#pagination a').click(function () {
        var loading = new $.LoadingBox({loadingImageSrc: "bundles/admin/images/default.gif"});
        // output the wanted grid system
        if (myClass.indexOf('list-group-item') > -1) {
            $('#products .item').removeClass('grid-group-item');
            $('#products .item').addClass('list-group-item');
            $('#products .item').removeClass('padding_grid');
        } else if (!myClass.indexOf('list-group-item') > -1) {
            $('#products .item').removeClass('list-group-item');
            $('#products .item').addClass('grid-group-item');
            $('#products .item').addClass('padding_grid');
        }
        loading.close();
    });
});