

<div id="usn-instance-cpu" class="col-lg-3 usn-instance-divs" >
    <canvas id="roundChart<?php echo "1"; ?>" style="width: 100%; height: 200px; margin-bottom: 12px; display: inline-table"></canvas>
    <h5 style="text-align: center; color: #7b7b7b;">CPU USAGE</h5>
    <script>
        setInterval(function()
        { 
            var used = 50; 
            var idle = 100 - used;

            // Any of the following formats may be used
            var round<?php echo "1"; ?> = document.getElementById("roundChart<?php echo "1"; ?>");
            var roundChart<?php echo "1"; ?> = new Chart(round<?php echo "1"; ?>, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [used, idle],
                        backgroundColor: [
                        '#ea4444',
                        '#bcbcbc'
                        ],
                        borderColor: [
                            '#ce3333',
                            '#dddddd'
                        ]
                    }],

                    labels: [
                        'Used',
                        'Idle'
                    ],
                },
                options: {
                    animation: {
                        duration: 0
                    }
                }
            });
        }, 1000);
    </script>
</div>