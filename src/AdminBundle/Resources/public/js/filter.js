// select picker enable/disable
$('#car-type').change(function () {
    var carType = $('button[data-id="car-type"]').text();
    console.log(carType);
    if (carType.includes("marca")) {
        $('#car-model').attr('disabled', true);
        $('.selectpicker').selectpicker('refresh');
    } else {
        $('#car-model').attr('disabled', false);
        $('.selectpicker').selectpicker('refresh');
    }
});


// dropdown car makers and models
$('#car-maker').change(function () {
    var carMaker = $('button[data-id="car-type"]').text();
    console.log(carMaker);
})