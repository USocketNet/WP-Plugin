<script type="text/javascript">
    
    class USN_Stat {
        construct () {
            
        }

        addChartView( viewName, types, datasets, prefix ) {
            this[viewName] = new Chart(document.getElementById(viewName), {
                type: types,
                data: {
                    labels: [
                        "1", "2", "3", "4", "5", "6", "7", "8", "9", "10"
                    ],
                    datasets: datasets
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                callback: function(value, index, values) {
                                    return value + prefix;
                                }
                            }
                        }]
                    },
                    animation: {
                        duration: 0
                    }
                }
            }) ;
        }

        updateChartView( elemId, data ) {
            for(var i=0;i<data.length;i++) {
                chartz[elemId].data.datasets[i].data.push(data[i]);
                chartz[elemId].data.datasets[i].data.shift();
            }
            
            chartz[elemId].update();
        }

    }
    const chartz = new USN_Stat();

    let totalProcess = 0;
    const usn_server = '<?php echo $_GET['host']; ?>';
    const authToken = { wpid: '<?php echo get_current_user_id(); ?>', snid: '<?php echo wp_get_session_token(); ?>' };
    const cluster = new USocketNet('cluster', usn_server, authToken);
        cluster.connect();

        cluster.on('connected', ( conn ) => {

            cluster.requestSummaryStat( (list) => {
                if(!list.success) {
                    return;
                }
                totalProcess = list.data.length;

                for( var i = 0; i < list.data.length; i++ ) {
                    var delta = list.data[i].uptime;

                    // calculate (and subtract) whole days
                    var days = Math.floor(delta / 86400);
                    // delta -= days * 86400;

                    // calculate (and subtract) whole hours
                    var hours = Math.floor(delta / 3600) % 24;
                    // delta -= hours * 3600;

                    // calculate (and subtract) whole minutes
                    var minutes = Math.floor(delta / 60) % 60;
                    // delta -= minutes * 60;

                    // what's left is seconds
                    var seconds = Math.floor(delta % 60); 

                    list.data[i].uptime = {
                        days: days,
                        hours: hours,
                        minutes: minutes,
                        seconds: seconds
                    };
                }

                //Get child template.
                var rawTemplate = document.getElementById("processTemplate").innerHTML;
                //Compile the raw template.
                var compiledTemplate = Handlebars.compile(rawTemplate);
                //Generate template
                var process = { process: list.data };
                var generatedTemplate = compiledTemplate(process);
                //Get Container display.
                var containerDisplay = document.getElementById("usn-cluster-viewer");
                //Push Genrated template.
                containerDisplay.innerHTML = generatedTemplate;


                for( var i = 0; i < list.data.length; i++ ) {
                    chartz.addChartView('cpu-'+list.data[i].pid, 'line', [
                        {
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, list.data[i].cpu],
                            label: 'Percent',
                            backgroundColor: ['rgba(232, 38, 38, 0.4)'],
                        }
                    ], ' %');

                    chartz.addChartView('ram-'+list.data[i].pid, 'line', [
                        {
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, list.data[i].ram],
                            label: 'Megabyte',
                            backgroundColor: ['rgba(24, 231, 13, 0.4)'],
                        }
                    ], ' MB');

                    chartz.addChartView('net-'+list.data[i].pid, 'line', [
                        {
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            label: 'Lat',
                            backgroundColor: ['rgba(231, 169, 13, 0.4)'],
                        },
                        {
                            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            label: 'Lat p95',
                            backgroundColor: ['rgba(205, 72, 239, 0.4)'],
                        }
                    ], ' ms');
                }
            });
            
            setInterval(() => {
                if( cluster.isConnected() ) {
                    
                    cluster.requestSummaryStat( (list) => {

                        if(totalProcess != list.data.length) {
                            location.reload(); //refresh page.
                        }

                        for(var i=0; i<list.data.length; i++) {
                            chartz.updateChartView('cpu-'+list.data[i].pid, [list.data[i].cpu])
                            chartz.updateChartView('ram-'+list.data[i].pid, [list.data[i].ram])
                            if(typeof list.data[i].latency !== 'undefined' || typeof list.data[i].latency_p95 !== 'undefined') {
                                chartz.updateChartView('net-'+list.data[i].pid, [list.data[i].latency, list.data[i].latency_p95])
                            } else {
                                chartz.updateChartView('net-'+list.data[i].pid, [0, 0])
                            }

                            if(list.data[i].status == 'online') {
                                if ( document.getElementById("stat-"+list.data[i].pid).classList.contains("status-red") ) {
                                    document.getElementById("stat-"+list.data[i].pid).classList.remove("status-red");
                                }

                                if ( !document.getElementById("stat-"+list.data[i].pid).classList.contains("status-green") ) {
                                    document.getElementById("stat-"+list.data[i].pid).classList.add("status-green");
                                }

                                document.getElementById("stat-"+list.data[i].pid).innerHTML ='ONLINE';
                                
                            } else {
                                if ( document.getElementById("stat-"+list.data[i].pid).classList.contains("status-green") ) {
                                    document.getElementById("stat-"+list.data[i].pid).classList.remove("status-green");
                                }

                                if ( !document.getElementById("stat-"+list.data[i].pid).classList.contains("status-red") ) {
                                    document.getElementById("stat-"+list.data[i].pid).classList.add("status-red");
                                }

                                document.getElementById("stat-"+list.data[i].pid).innerHTML = 'OFFLINE';

                            }
                        }
                    });
                }
            }, 1000);
        });

    Handlebars.registerHelper("uptimeDisplay", (uptime) => {
        let display = "";
        if(uptime.days > 0) {
            display += uptime.days + " days "; 
        }
        if(uptime.hours > 0) {
            display += uptime.hours + " hrs "; 
        }
        if(uptime.minutes > 0) {
            display += uptime.minutes + " min "; 
        }
        if(uptime.seconds > 0) {
            display += uptime.seconds + " sec "; 
        }
        return display;
    });

    Handlebars.registerHelper("processStatus", (pid, status) => {
        let display = "";
            if(status == 'online') {
                display = '<strong id="stat-'+pid+'" class="process-detail float-block-right status-green">ONLINE</strong>'; 
            } else {
                display = '<strong id="stat-'+pid+'" class="process-detail float-block-right status-red">OFFLINE</strong>'; 
            }
        return display;
    });

    Handlebars.registerHelper('ifEquals', function(arg1, arg2, options) {
        return (arg1 == arg2) ? options.fn(this) : options.inverse(this);
    });
</script>