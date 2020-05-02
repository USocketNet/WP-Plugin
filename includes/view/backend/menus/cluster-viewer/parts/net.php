<div id="usn-instance-net" class="col-lg-3 usn-instance-divs">
    <canvas id="barChart<?php echo "1"; ?>" style="width: 100%; height: 200px;"></canvas>
    <h5 style="text-align: center; color: #7b7b7b;">NETWORK TRAFFIC</h5>
    <script>
        var net = [20, 32, 41, 39, 42, 21, 25, 18, 29, 28];
        setInterval(function()
        { 
            net.splice(0, 1);
            net.push( Math.floor(Math.random() * 25) );

            // Any of the following formats may be used
            var bar<?php echo "1"; ?> = document.getElementById("barChart<?php echo "1"; ?>");
            var barChart<?php echo "1"; ?> = new Chart(bar<?php echo "1"; ?>, {
                type: 'bar',
                data: {
                    labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"],
                    datasets: [{
                        data: net,
                        label: 'Packets',
                        backgroundColor: '#3787d3',
                        borderColor: '#25649e',
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