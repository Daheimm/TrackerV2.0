let login;
let dataTable;

$(document).ready(function () {


    $("#save-tariff").click(async function () {
        console.log($('#tariff').find(":selected").val());
        $.ajax({
            type: "POST",
            url: "/?/tariff/pay",
            data: {"tariff": $('#tariff').find(":selected").val()},
            success: function (msg) {
alert(msg);

            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
        });

    });

});
