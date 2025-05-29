$(function () {
    "use strict";
    var ctx1 = document.getElementById("chartBar1").getContext("2d");

    new Chart(ctx1, {
        type: "bar",
        data: {
            labels: [
                "20 Agu",
                "21 Agu",
                "22 Agu",
                "23 Agu",
                "24 Agu",
                "25 Agu",
                "26 Agu",
                "27 Agu",
                "28 Agu",
                "29 Agu",
                "30 Agu",
                "31 Agu",
                "01 Sep",
                "02 Sep",
                "03 Sep",
            ],
            datasets: [
                {
                    label: "Penjualan (Rp)",
                    data: [
                        550000, 470000, 620000, 300000, 410000, 390000, 700000,
                        660000, 580000, 630000, 500000, 520000, 490000, 610000,
                        575000,
                    ],
                    backgroundColor: "#664dc9",
                    borderRadius: 4,
                    barThickness: 20,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 10 },
                        callback: function (value) {
                            return "Rp " + value.toLocaleString();
                        },
                    },
                },
                x: {
                    ticks: {
                        font: { size: 11 },
                    },
                    grid: { display: false },
                },
            },
        },
    });
    var datapie = {
        labels: [
            "Frame Titanium Wanita",
            "Kacamata Pria Elegan",
            "Lensa Anti Radiasi",
            "Softlens Natural Look",
            "Cairan Softlens 60ml",
            "Kacamata Anak Lucu",
        ],
        datasets: [
            {
                data: [85, 70, 55, 40, 30, 25], // jumlah unit terjual
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
