<script type="text/javascript">
    jQuery(document).ready( function ( $ ) 
    {
        var usnprojects = undefined;

        //GET THE REFERENCE OF THE CURRENT PAGE DATTABLES.
        var clusterDatatables = $('#project-datatables');

        //SHOW NOTIFICATION THAT WE ARE CURRENTLY LOADING APPS.

        //SET INTERVAL DRAW UPDATE.
        loadingClusterList();
        // setInterval( function()
        // { 
        //     loadingClusterList( clusterDatatables);
        // }, 10000);
        $('#ReloadClusterList').click(function() {
            loadingClusterList();
        });

        function loadingClusterList()
        {
            if( clusterDatatables.length != 0 )
            {
                if( $('#project-notification').hasClass('usn-display-hide') )
                {
                    $('#project-notification').removeClass('usn-display-hide');
                }
                
                var appListAction = { action: 'ReloadClusterList' };
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

        //CREATE NEW APP ENTRY ON MODAL.
        $('#add-cluster-form').submit( function(event) {
            event.preventDefault();

            $( "#dialog-confirm-create" ).dialog({
                title: 'Confirmation',
                resizable: false,
                height: "auto",
                width: 320,
                modal: false,
                open: function() {
                    $('#jquery-overlay').removeClass('usn-display-hide');
                    $('#confirm-content-create').html(
                        '<span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>' +
                        'Please confirm to complete the process, else just press cancel.'
                    );
                },
                buttons: {
                    "Confirm": function() 
                    {
                        confirmAddProcess();
                        $('#jquery-overlay').addClass('usn-display-hide');
                        $( this ).dialog( "close" );
                    },
                    Cancel: function() 
                    {
                        $('#jquery-overlay').addClass('usn-display-hide');
                        $( this ).dialog( "close" );
                    }
                }
            });
        });

        function confirmAddProcess()
        {
            $('#add-cluster-btn').addClass('disabled');

            //From native form object to json object.
            var unindexed_array = $('#add-cluster-form').serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });
            indexed_array.action = 'AddNewCluster';

            // This will be handled by create-app.php.
            $.ajax({
                dataType: 'json',
                type: 'POST', 
                data: indexed_array,
                url: 'admin-ajax.php',
                success : function( data )
                {
                    if( data.status == 'success' ) {
                        $('#cluster_name').val('');
                        $('#cluster_info').val('');
                        $('#cluster_hostname').val('');
                        $('#cluster_capacity').val('');
                    }
                    $('#CNAMessage').addClass('alert-'+data.status);
                    $('#CNAMessage').removeClass('usn-display-hide');
                    $('#CNAMcontent').text( data.message );

                    loadingClusterList();
                   $('#add-cluster-btn').removeClass('disabled');
                    activeTimeout = setTimeout( function() {
                        $('#CNAMessage').removeClass('alert-'+data.status);
                        $('#CNAMessage').addClass('usn-display-hide');
                        activeTimeout = undefined;
                    }, 4000);
                },
                error : function(jqXHR, textStatus, errorThrown) {
                    $('#CNAMessage').addClass('alert-danger');
                    $('#CNAMessage').removeClass('usn-display-hide');
                    $('#CNAMcontent').text( textStatus + ': Kindly consult to your administrator for this issue.' );

                    $('#add-cluster-btn').removeClass('disabled');
                    activeTimeout = setTimeout( function() {
                        $('#CNAMessage').removeClass('alert-danger');
                        $('#CNAMessage').addClass('usn-display-hide');
                        activeTimeout = undefined;
                    }, 7000);
                    console.log("" + JSON.stringify(jqXHR) + " :: " + textStatus + " :: " + errorThrown);
                }
            });
        }

        // LISTEN FOR MODAL SHOW AND ATTACHED ID.
        $('#AddNewCluster').on('show.bs.modal', function(e) {
            var data = e.relatedTarget.dataset;
            $('#add-cluster-btn').removeClass('disabled');
            $('#appsta_create').val( 'Active' );
            $('#appcap_create').val( 1000 );
        });

        // MAKE SURE THAT TIMEOUT IS CANCELLED.
        $('#AddNewCluster').on('hide.bs.modal', function(e) {
            if( activeTimeout != undefined )
            {
                clearTimeout( activeTimeout );
            }

            if( !$('#CNAMessage').hasClass('usn-display-hide') )
            {
                $('#CNAMessage').addClass('usn-display-hide');
            }
        });


        

    });
</script>