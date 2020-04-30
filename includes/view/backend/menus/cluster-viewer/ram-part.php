
<div id="usn-instance-mem" class="col-lg-3 usn-instance-divs">
    <canvas id="lineChart<?php echo "1"; ?>" style="width: 100%; height: 200px;"></canvas>
    <h5 style="text-align: center; color: #7b7b7b;">RAM USAGE</h5>
    <script>
        var ram = [12, 19, 3, 5, 2, 3, 12, 15, 18, 21];
        setInterval(function()
        { 
            ram.splice(0, 1);
            ram.push( Math.floor(Math.random() * 25) );

            // Any of the following formats may be used
            var line<?php echo "1"; ?> = document.getElementById("lineChart<?php echo "1"; ?>");
            var lineChart<?php echo "1"; ?> = new Chart(line<?php echo "1"; ?>, {
                type: 'line',
                data: {
                    labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"],
                    datasets: [{
                        data: ram,
                        label: 'Footprint',
                        backgroundColor: ['#59c165'],
                        borderColor: ['#3f9149'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    },
                    animation: {
                        duration: 0
                    }
                }
            });
        }, 1000);
        
    </script>
</div>