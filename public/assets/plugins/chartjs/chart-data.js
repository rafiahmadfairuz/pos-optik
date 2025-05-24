$(function () {
    "use strict";
    var ctx1 = document.getElementById("chartBar1").getContext("2d");
    new Chart(ctx1, {
        type: "bar",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [
                {
                    label: "Sales",
                    data: [24, 10, 32, 24, 26, 20],
                    backgroundColor: "#664dc9",
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            legend: { display: false, labels: { display: false } },
            scales: {
                yAxes: [
                    { ticks: { beginAtZero: true, fontSize: 10, max: 80 } },
                ],
                xAxes: [
                    {
                        barPercentage: 0.6,
                        ticks: { beginAtZero: true, fontSize: 11 },
                    },
                ],
            },
        },
    });
    
    var datapie = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May"],
        datasets: [
            {
                data: [35, 20, 8, 15, 24],
                backgroundColor: [
                    "#664dc9",
                    "#44c4fa",
                    "#38cb89",
                    "#3e80eb",
                    "#ffab00",
                    "#ef4b4b",
                ],
            },
        ],
    };
    var optionpie = {
        maintainAspectRatio: false,
        responsive: true,
        legend: { display: false },
        animation: { animateScale: true, animateRotate: true },
    };
    var ctx6 = document.getElementById("chartPie");
    var myPieChart6 = new Chart(ctx6, {
        type: "doughnut",
        data: datapie,
        options: optionpie,
    });
    var ctx7 = document.getElementById("chartDonut");
    var myPieChart7 = new Chart(ctx7, {
        type: "pie",
        data: datapie,
        options: optionpie,
    });
});
