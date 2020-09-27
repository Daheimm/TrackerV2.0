let chartDiagrm;
let chartGraphic;
let dataTable;
$(document).ready(function () {


    $('#datepicker.input-daterange').datepicker({
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        format: "mm.dd.yyyy"
    });

    setTimeout(function () {
        $('.js-select2').select2({
            placeholder: 'Select an option'
        });
    }, 300)


    $('.js-copy-by-id').on('click', function () {
        var copyElemId = $(this).attr('data-copy-elem-id');
        copyValById('integrationLink');
    });


    Highcharts.setOptions({
        colors: ['#101662', '#f03172', '#292e7c', '#66b3d9', '#ffaf3a']
    });


    $('#applyStatistisc').click(function () {
        var company = $('#companyName').find(":selected").text();

        diagramTraffic(company);
        listTraffic(company);
        datatabel(company);
        ClickTargetandBot(company);
    })

    function browser(msg) {
        if ($('#browsersChart').length) {

            // Build the chart
            chartDiagrm = Highcharts.chart('browsersChart', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    backgroundColor: 'transparent',
                    margin: [0, 0, 0, 0]
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '{point.percentage:.1f} %',
                            distance: -50,
                            filter: {
                                property: 'percentage',
                                operator: '>',
                                value: 4
                            },
                            style: {
                                textOutline: 'none'
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data: [{
                        name: 'Mobile',
                        y: msg.mobile,
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Desktop',
                        y: msg.desctop
                    }]
                }]
            });
        }
    }

    function traffic(msg) {
        if ($('#trafficChart').length) {

            chartGraphic = Highcharts.chart('trafficChart', {
                chart: {
                    type: 'areaspline',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories:
                    msg.date

                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    gridLineColor: 'rgba(0,0,0,.05)'
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' units'
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                    name: 'Users',
                    data: msg.target
                }, {
                    name: 'Bots',
                    data: msg.bot
                }]
            });
        }
    }

    function datatabel(company) {

        dataTable = $('#js-statistic-t').DataTable({
            "ajax": {
                "url": "?/statistics/datatable",
                "type": "GET",
                "data": function (d) {
                    d.end = $('#end').val();
                    d.start = $('#start').val();
                    d.company = company
                },
            },
            ordering: false,
            scrollX: true,
            destroy: true,
            aaData: response.data,
            success: function (msg) {

            }
        });
    }


    function getListCompany() {
        $.ajax({
            type: "POST",
            url: "/?/statistics/listcompany",
            success: async function (msg) {

                $.each(msg, await function (key, value) {

                      $('#companyName').append('<option value="' + value.nameCompany + '">' + value.nameCompany + '</option>');
                });

                var url = new URL(document.location.href);
                var company = url.searchParams.get("company");

                if (company == null) company = $('#companyName').find(":selected").text()

                $('#companyName').find(":selected").text(company);

                diagramTraffic(company);
                listTraffic(company);
                datatabel(company);
                ClickTargetandBot(company);

            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            },
            dataType: 'JSON',
        });
    }

    $(window).on("load", function () {


    });


    function diagramTraffic(company) {
        $.ajax({
            type: "POST",
            url: "/?/statistics/diagramTraffic",
            success: function (msg) {

                browser(msg);
            }, data: {
                "end": $('#end').val(),
                "start": $('#start').val(),
                "company": company
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            dataType: 'JSON',
        });
    }

    function ClickTargetandBot(company) {
        $.ajax({
            type: "POST",
            url: "/?/statistics/targetandbot",
            success: function (msg) {

                $("#click span").text(msg[0].general);
                $("#clickUser span").text(msg[1].general);
                $("#targetClick span").text(msg[2].general);
                $("#targetClickUser span").text(msg[3].general);
                $("#botClick span").text(msg[4].general);
                $("#botClickUser span").text(msg[5].general);

            }, data: {
                "end": $('#end').val(),
                "start": $('#start').val(),
                "company": company
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            dataType: 'JSON',
        });
    }

    function listTraffic(company) {
        $.ajax({
            type: "POST",
            url: "/?/statistics/listTraffic",
            success: function (msg) {
                console.log(msg);
                var days = [
                    'Воскресенье',
                    'Понедельник',
                    'Вторник',
                    'Среда',
                    'Четверг',
                    'Пятница',
                    'Суббота'
                ];

                var date = [];

                if (typeof msg.date == "object") {

                    if (Array.isArray(msg.date)) {

                        for (var i = 0; i <= msg.date.length; i++) {


                            if (msg.date[i] == null) {
                                msg.target.splice(i, 1);
                                msg.bot.splice(i, 1);
                            } else {
                                var d = new Date(msg.date[i]);
                                var index = d.getDay();
                                date.push(days[index]);
                            }
                        }
                        traffic(msg);

                    }
                } else if (typeof msg.time === "object") {
                    msg.date = msg.time;
                    traffic(msg);
                }


            }, data: {
                "end": $('#end').val(),
                "start": $('#start').val(),
                "company": company
            }, error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            dataType: 'JSON',
        });
    }

    // function validateEmail(email) {
    //   const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    //   return re.test(email);
    // }

    // function validate() {
    //   const $result = $("#result");
    //   const email = $("#email").val();
    //   $result.text("");

    //   if (validateEmail(email)) {
    //     $result.text(email + " is valid :)");
    //     $result.css("color", "green");
    //   } else {
    //     $result.text(email + " is not valid :(");
    //     $result.css("color", "red");
    //   }
    //   return false;
    // }

    // // $("#signIn").on("click", validate);
    getListCompany();
});

function copyValById(elemId) {
    var copyText = document.getElementById(elemId);
    copyText.select();


    console.log("Copied the text: " + copyText.value);
}


(function () {
    'use strict';
    window.addEventListener('load', function () {

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();