let lngList = [];
let countryList = [];

$(document).ready(function () {


    var url = new URL(document.location.href);
    var company = url.searchParams.get("company");


    $.each(languageList, function (key, value) {
        $('#language').append('<option value="' + value + '">' + key + ": " + value + '</option>');
    });


    $.each(country, function (value) {
        $('#geolocation').append('<option value="' + country[value] + '">' + country[value] + '</option>');
    });

    function redraw(data) {

        $(data.id).append('<div class="input-group removeGeo">' +
            '<input type="text" class="form-control disabled read" name="" value="' + data.val + '" disabled />' +
            '<div class="input-group-append">' +
            '<button id="addGeoButton"  class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>' +
            '</div>' +
            '</div>');
    }


    $('#addGeo').click(function () {

        console.log(countryList.indexOf($('#geolocation').find(":selected").text()));
        if (countryList.indexOf($('#geolocation').find(":selected").text()) == -1) {
            countryList.push($('#geolocation').find(":selected").text());
            data = {
                'id': '#selectedGeo',
                'val': $('#geolocation').find(":selected").text()
            }
            redraw(data)

        } else {
            alert("Выбранная страна уже есть в списке");
        }
    })

    $('#addLng').click(function () {

        var lng = $('#language').find(":selected").text().split(':')[0];


        if (lngList.indexOf(lng) == -1) {

            lngList.push(lng);
            data = {
                'id': '#selectedLng',
                'val': $('#language').find(":selected").text()
            }
            redraw(data);
        } else {
            alert("Выбранный язык уже есть в списке");
        }
    })

    $('html').on('click', '.removeLng', function () {

        var lngDel = $(this).children($("input")).val().split(':')[0];
        var index = lngList.indexOf(lngDel);
        lngList.splice(index, 1);
        console.log(lngList);
        $(this).remove();
    });

    $('html').on('click', '.removeGeo', function () {
        var lngDel = $(this).children($("input")).val();
        var index = countryList.indexOf(lngDel);
        countryList.splice(index, 1);
        $(this).remove();
        console.log(countryList);
    });

    function sendDataEditCompnay(data) {
        $.ajax({
            type: "POST",
            url: "?/editCompany/sendDataEditCompany",
            data: data,
            success: function (msg) {


                //location.reload();
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }

        });
    }

    $('#save').click(function () {

        var url = new URL(document.location.href);
        var company = url.searchParams.get("company");

        data = {
            'lng': lngList.join(),
            'location': countryList.join(),
            'targetPage': $('#targetPage').val(),
            'botPage': $('#botPage').val(),
            'staticKey': $('#staticKey').val(),
            'company': company
        }

        sendDataEditCompnay(data);
        document.location.href="/";


    });
    read();

    function read() {
        $('.readLocation').each(function (value) {

            if ($(this).val()) {
                countryList.push($(this).val());
            }
        })

        $('.readLng').each(function (value) {
            lngList.push($(this).val());

        })
        console.log(countryList);
    }

})