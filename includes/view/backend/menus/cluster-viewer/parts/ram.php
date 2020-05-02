
<div id="usn-instance-mem" class="col-lg-3 usn-instance-divs">
    <canvas id="lineChart<?php echo "1"; ?>" style="width: 100%; height: 200px;"></canvas>
    <h5 style="text-align: center; color: #7b7b7b;">RAM USAGE</h5>
    <script>
        let ram = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        let ramTitle = [];
        for (var i = 1; i <= 10; i++) {
            ram.push(0);
            ramTitle.push(i);
        }

        // Any of the following formats may be used
        let line<?php echo "1"; ?> = document.getElementById("lineChart<?php echo "1"; ?>");
        let lineChart<?php echo "1"; ?> = new Chart(line<?php echo "1"; ?>, {
            type: 'line',
            data: {
                labels: ramTitle,
                datasets: [{
                    data: ram,
                    label: 'Memory',
                    backgroundColor: ['#59c165'],
                    borderColor: ['#3f9149'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value, index, values) {
                                return value + ' MB';
                            }
                        }
                    }]
                },
                animation: {
                    duration: 0
                }
            }
        });

        function updateRamValue( ram ) {
            lineChart<?php echo "1"; ?>.data.datasets[0].data.push( Math.round(ram/1000000) );
            lineChart<?php echo "1"; ?>.data.datasets[0].data.shift();
            lineChart<?php echo "1"; ?>.update();
        }

        
        
    </script>
</div>