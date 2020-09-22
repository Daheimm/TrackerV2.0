$(document).ready(function () {
    var table = $('#table').DataTable();

    $('#table tbody').on('click', 'tr', async function () {
        company = await table.row(this).data();


        if(table.row(this).data()[8] == 0) {
            $.ajax({
                type: "POST",
                url: "?/activation",
                data: {
                    "login": table.row(this).data()[0]
                },

                success: function (msg) {
                    console.log(msg);

                    //location.reload();
                }, error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }

            });
        }else{
            alert("Уже активирован");
        }
    });

});