$(document).ready(function () {
    $('#addBlackIP').click(function () {

        $.ajax({
            type: "POST",
            url: "/?/writeBlackIp",
            data: {"blackIP": $('#blackIP').val()},
            success: function (msg) {
               alert(msg);
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }

        });

    });
});





