let login;
let dataTable;
$(document).ready(function () {

    $.ajax({
        type: "POST",
        url: "/?/getAllUsers",
        success: function (msg) {
            console.log(msg);
            $.each(JSON.parse(msg), function (key, value) {
                $('#clientName').append('<option value="' + value.log + '">' + value.log + '</option>');
            });
        }, error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
        }

    });

    $("#clientName").change(function () {
        login = $(this).val();

        if ($(this).val() == 0) return false;
        else {
            $.ajax({
                type: "POST",
                url: "/?/getAllCompany",
                data: {"login": login},
                success: function (msg) {


                    $('#companyName').find('option').remove();
                    $.each(JSON.parse(msg), function (key, value) {
                        $('#companyName').append('<option value="' + value.nameCompany + '">' + value.nameCompany + '</option>');
                    });
                }, error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });
        }
    });

    $("#SetApply").click(async function () {
       // await headerForDataTable();
        var company = $('#companyName').find(":selected").text()
        datatabel(company);


    });

    function headerForDataTable() {


        $.ajax({
            type: "POST",
            url: "/?/logs/headerTable",
            data: {
                "company": $('#companyName').find(":selected").text()
            },
            success: function (msg) {
                $.each(JSON.parse(msg), function (key, value) {
                    $('#addHeader').append('<th class="th-sm" value="' + value + '">' + value + '</th>');
                });

                console.log(msg);
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }

        });
    }

    function datatabel(company) {

        dataTable = $('#js-statistic-t').DataTable({
            "ajax": {
                "url": "?/logs/getAllDataFromClient",
                "type": "GET",
                "data": function (d) {
                    d.end = $('#end').val();
                    d.start = $('#start').val();
                    d.company = company;
                    d.login = login;
                },
            },
            ordering: false,
            scrollX: true,
            destroy: true,
            aaData: function () {
                console.log(response.data);
            },
            success: function (msg) {
                console.log("dasda");
            }
        });
    }

});
