<script type="text/javascript">
    jQuery(document).ready( function ( $ ) 
    {
        var usnprojects = 'undefined';

        //GET THE REFERENCE OF THE CURRENT PAGE DATTABLES.
        var clusterDatatables = $('#project-datatables');

        //SHOW NOTIFICATION THAT WE ARE CURRENTLY LOADING APPS.

        //SET INTERVAL DRAW UPDATE.
        loadingAppList( clusterDatatables );
        // setInterval( function()
        // { 
        //     loadingAppList( clusterDatatables );
        // }, 10000);
        $('#RefreshProjectList').click(function() {
            loadingAppList( clusterDatatables );
        });

        function loadingAppList( clusterDatatables )
        {
            if( clusterDatatables.length != 0 )
            {
                if( $('#project-notification').hasClass('usn-display-hide') )
                {
                    $('#project-notification').removeClass('usn-display-hide');
                }
                
                var appListAction = { action: 'ReloadProjects' };
                $.ajax({
                    dataType: 'json',
                    type: 'POST', 
                    data: appListAction,
                    url: 'admin-ajax.php',
                    success : function( data )
                    {
                        displayingLoadedProjects( data.message );
                        if( !$('#project-notification').hasClass('usn-display-hide') )
                        {
                            $('#project-notification').addClass('usn-display-hide');
                        }
                    },
                    error : function(jqXHR, textStatus, errorThrown) 
                    {
                        //$('#project-notification').text = "";
                        console.log("" + JSON.stringify(jqXHR) + " :: " + textStatus + " :: " + errorThrown);
                    }
                });
            }
        }

        //DISPLAY DATA INTO THE TARGET DATATABLES.
        function displayingLoadedProjects( data )
        {
            //Set table column header.
            let columns = [
                // { "sTitle": "IDENTITY",   "mData": "ID" },
                { "sTitle": "NAME",   "mData": "cluster_name" },
                { "sTitle": "INFO",   "mData": "cluster_info" },
                { "sTitle": "HOSTNAME",   "mData": "cluster_hostname" },
                { "sTitle": "CAPACITY",   "mData": "cluster_capacity" },
                { "sTitle": "OWNER",   "mData": "cluster_owner" },
                {"sTitle": "Action", "mRender": function(data, type, item)
                    {
                        return '' + 

                            '<div class="btn-group" role="group" aria-label="Basic example">' +

                                '<button type="button" class="btn btn-primary btn-sm"' +
                                    ' data-toggle="modal" data-target="#EditAppOption"' +
                                    ' title="Clicking this will show options for the game that can be modified."' +
                                    ' data-aid="' + item.ID + '"' +  
                                    ' data-aname="' + item.cluster_name + '"' +  
                                    ' data-ainfo="' + item.cluster_info + '"' +  
                                    ' data-aurl="' + item.cluster_owner + '"' +  
                                    ' data-asta="' + item.cluster_hostname + '"' +  
                                    ' data-acap="' + item.cluster_capacity + '"' +
                                    ' >Options</button>' +

                                '<button type="button" class="btn btn-info btn-sm"' +
                                    ' title="Clicking this will show realtime statistics and current state of the game."' + 
                                    ' disabled>More</button>' +

                                '<button type="button" class="btn btn-info btn-sm appkey-' + item.ID + '"' +
                                    '>Display</button>' +            
                                    
                            '</div>'; 
                    }
                }
            ];

            //Displaying data on datatables.
            usnprojects = $('#project-datatables').DataTable({
                destroy: true,
                searching: true,
                buttons: ['copy', 'excel', 'print'],
                responsive: true,
                "aaData": data,
                "aoColumns": columns,
                "columnDefs": [
                    {"className": "dt-center", "targets": "_all"}
                ],
            });
        }

        //IMPLEMENT DATATABLES RESPONSIVENESS.
        if( usnprojects != undefined)
        {
            usnprojects.on( 'responsive-resize', function ( e, datatable, columns ) {
                var count = columns.reduce( function (a,b) {
                    return b === false ? a+1 : a;
                }, 0 );
            
                console.log( count +' column(s) are hidden' );
            } );
        }
    });
</script>