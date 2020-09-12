$(document).ready(function () {

    $.ajax({
        type: "POST",
        url: "/?/statistics/listcompany",
        success: function (msg) {
            console.log(msg);
            $.each(msg, function (key, value) {
                $('#companyName').append('<option value="' + value.nameCompany + '">' + value.nameCompany + '</option>');
            });
            //location.reload();
        }, error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
        },
        dataType: 'JSON',
    });
    $(window).on("load", function () {



    });


});





