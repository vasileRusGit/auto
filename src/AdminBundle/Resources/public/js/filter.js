// select picker enable/disable
$(document).ready(function () {
    $('#car-type').change(function () {
        var carMaker = $('button[data-id="car-type"]').text();
        console.log(carMaker);

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
            url: 'http://localhost:8000/ajax',
            data: {carMaker: carMaker},
            success: function (data) {
                $("#car-model").empty();
                
                $.each(data, function (item) {
                    console.log(data.id);
                    $("#car-model").append('<option>' + item.id + item.title + '</option>');
//                            append($("<option>").val(item.id).text(item.title));
                });
                
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
