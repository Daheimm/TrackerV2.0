$(document).ready(function () {
    var company;
    var table = $('#table').DataTable();

    $('#table tbody').on('click', 'tr', async function () {
        company = await table.row(this).data()[0];
        console.log(company);
        $('#deleteCompany').val(table.row(this).data()[0]);
    });

    $('#delete').click(function () {
        console.log($('#deleteCompany').val());
        $.ajax({
            type: "POST",
            url: "/?/deleteCompany",
            data: {"company": $('#deleteCompany').val()},
            success: function (msg) {
                location.reload();
            },

        });

    });
    dwn = function (msg) {
        $.ajax({
            type: "POST",
            url: "/?/download",
            data: {"company": msg},
            success: function (msg) {
                console.log(msg);
                location = msg;
                //location.reload();
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }

        });
    };



});





