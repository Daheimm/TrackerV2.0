$(document).ready(function () {
    $('#addBlackIP').click(function () {
        console.log($('#blackIP').val());
        $.ajax({
            type: "POST",
            url: "/?/writeBlackIp",
            data: {"blackIP": $('#blackIP').val()},
            success: function (msg) {
                // location.reload();
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }

        });

    });
});





