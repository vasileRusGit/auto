// select picker enable/disable
$(document).ready(function () {
    $('#car-type').change(function () {
        var carMaker = $('button[data-id="car-type"]').text();
//        console.log(carMaker);

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

        // get ajax request
        $.ajax({
            method: 'POST',
            url: 'http://localhost:8000/form_submit',
            data: {
                carMakerDropdown: carMakerDropdown,
                carModelDropdown: carModelDropdown,
                carStartYearDropdown: carStartYearDropdown,
                carEndYearDropdown: carEndYearDropdown,
                carPieceDropdown: carPieceDropdown
            },
            success: function (data) {
                $('.content').empty();
                $('.content').html(data);
            }
        });
    });
});



//// get the name of the select dropdown
//$(document).ready(function () {
//    $('select').change(function () {
//        var carMakerId = document.getElementsByTagName('select')[0].id;
//        var carMakerName = document.getElementById(carMakerId).value;
//        console.log(carMakerName);
//
//        $.ajax({
//            method: 'POST',
//            url: 'http://localhost:8000/',
//            data: {carMakerName: carMakerName}
//        });
//    });
//});
