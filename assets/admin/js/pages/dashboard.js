require( 'chart.js' );

define(["jquery"], function( $ ) {


    $(document).ready(function () {

        var visitsPerDay = JSON.parse($("#main-graph").attr("data-values"));
        var scale = [];
        var visits = [];

        visitsPerDay.forEach(function(item, index){
            scale.push(item[1]+"/"+item[2]);
            visits.push(item[4]);
        });

        var config = {
            type: 'line',
            data: {
                labels: scale,
                datasets: [{
                    backgroundColor: '#ef528542',
                    borderColor: '#ef5285',
                    data: visits,
                    fill: true,
                }]
            },
            options: {
                responsive: true,

                tooltips: {
                    mode: 'index',
                    intersect: false,
                },

                legend: {
                    display: false
                },

                maintainAspectRatio: false,
            }
        };

        var ctx = document.getElementById('main-graph').getContext('2d');
        new Chart(ctx, config);

    });

});