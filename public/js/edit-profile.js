let lngList = [];
let countryList = [];
$(document).ready(function () {
    $.each(languageList, function (key, value) {
        $('#language').append('<option value="' + value + '">' + key + ": " + value + '</option>');
    });


    $.each(country, function (value) {
        $('#geolocation').append('<option value="' + country[value] + '">' + country[value] + '</option>');
    });


    $('#addGeo').click(function () {


        if (countryList.indexOf($('#geolocation').find(":selected").text()) == -1) {
            countryList.push($('#geolocation').find(":selected").text());
            $('#selectedGeo').append('<div class="input-group removeGeo">' +
                '<input type="text" class="form-control disabled" name="" value="' + $('#geolocation').find(":selected").text() + '" disabled />' +
                '<div class="input-group-append">' +
                '<button id="addGeoButton"  class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>' +
                '</div>' +
                '</div>');
        } else {
            alert("Выбранная страна уже есть в списке");
        }
    })

    $('#addLng').click(function () {


        if (lngList.indexOf($('#language').find(":selected").text()) == -1) {

            lngList.push($('#language').find(":selected").text());
            console.log(typeof $('#language').find(":selected").text());
            $('#selectedLng').append('<div class="input-group removeLng">\n' +
                '<input type="text" id="inputLng" class="form-control disabled" name="" value="' + $('#language').find(":selected").text() + '" disabled />\n' +
                '<div class="input-group-append ">\n' +
                '<button  class="btn btn-danger del"><i class="fas fa-trash-alt"></i></button>\n' +
                '</div>' +
                '</div>');
        } else {
            alert("Выбранный язык уже есть в списке");
        }
    })

    $('html').on('click', '.removeLng', function () {
        //console.log($(this));
        var lngDel = $(this).children($("input")).val();
        var index = lngList.indexOf(lngDel);
        lngList.splice(index, 1);
        $(this).remove();
    });

    $('html').on('click', '.removeGeo', function () {
        var lngDel = $(this).children($("input")).val();
        var index = countryList.indexOf(lngDel);
        countryList.splice(index, 1);
        $(this).remove();
        console.log(countryList);
    });

})