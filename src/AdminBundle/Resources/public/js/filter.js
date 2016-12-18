// select picker enable/disable
$(document).ready(function () {
    $('#car-type').change(function () {
        var carMaker = $('button[data-id="car-type"]').text();

        var loading = new $.LoadingBox({loadingImageSrc: "bundles/admin/images/default.gif"});

        // activate the car model dropdown when car maker dropdown is selected
        if (carMaker.includes("marca")) {
            $('#car-model').attr('disabled', true);
            $('.selectpicker').selectpicker('refresh');
        } else {
            $('#car-model').attr('disabled', false);
            $('.selectpicker').selectpicker('refresh');
        }

        // get ajax request
        $.ajax({
            method: 'POST',
            url: 'http://localhost:8000/ajax_call',
            data: {carMaker: carMaker},
            complete: function () {
                loading.close();
            },
            success: function (data) {
                $('#car-model').empty();

                $.each(data, function () {
                    $("#car-model").append($("<option />").val(this.id).text(this.title));
                    $('.selectpicker').selectpicker('refresh');
                });
            }
        });
    });


    $('#search-form').on('click', function () {
        var carMakerDropdown = $('button[data-id="car-type"]').text();
        var carModelDropdown = $('button[data-id="car-model"]').text();
        var carStartYearDropdown = $('button[data-id="car-year-start"]').text();
        var carEndYearDropdown = $('button[data-id="car-year-end"]').text();
        var carPieceDropdown = $('button[data-id="car-piece"]').text();
        var stockCheckbox = $('input[name="stock"]').is(':checked');

        var loading = new $.LoadingBox({loadingImageSrc: "bundles/admin/images/default.gif"});

        // get ajax request
        $.ajax({
            method: 'POST',
            url: 'http://localhost:8000/form_submit',

            data: {
                carMakerDropdown: carMakerDropdown,
                carModelDropdown: carModelDropdown,
                carStartYearDropdown: carStartYearDropdown,
                carEndYearDropdown: carEndYearDropdown,
                carPieceDropdown: carPieceDropdown,
                stockCheckbox: stockCheckbox
            },
            complete: function () {
                loading.close();
                // // pagination
                // if ($('#filter-pagination').is(':visible')) {
                //     $('#pagination').hide();
                // }
            },
            success: function (data) {

                $('.content').empty();
                $('.content').html(data);
            }
        });
    });
});


