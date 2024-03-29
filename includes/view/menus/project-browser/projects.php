
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

	/** 
		* @package bytescrafter-usocketnet-restapi
		* Name: USocketNet RestAPI
		* Description: Self-Host Realtime Multiplayer Server 
		*       for your Game or Chat Application.
		* Package-Website: https://usocketnet.bytescrafter.net
		* 
		* Author: Bytes Crafter
		* Author-Website:: https://www.bytescrafter.net/about-us
		* License: Copyright (C) Bytes Crafter - All rights Reserved. 
	*/
?>

<script type="text/javascript">
    jQuery(document).ready( function ( $ ) 
    {
        //THIS ARE ALL THE PUBLIC VARIABLES.
        var activeTimeout = 'undefined';

        //#region Page = APPLICATION LIST 
            //GET THE REFERENCE OF THE CURRENT PAGE DATTABLES.
            var appDatatables = $('#apps-datatables');

            //SHOW NOTIFICATION THAT WE ARE CURRENTLY LOADING APPS.

            //SET INTERVAL DRAW UPDATE.
            loadingAppList( appDatatables );
            // setInterval( function()
            // { 
            //     loadingAppList( appDatatables );
            // }, 10000);
            $('#RefreshAppList').click(function() {
                loadingAppList( appDatatables );
            });
        
            //LOAD APPLIST WITH AJAX.
            var usnapps = 'undefined';
            function loadingAppList( appDatatables )
            {
                if( appDatatables.length != 0 )
                {
                    if( $('#apps-notification').hasClass('usn-display-hide') )
                    {
                        $('#apps-notification').removeClass('usn-display-hide');
                    }
                    
                    var appListAction = { action: 'ReloadProjects' };
                    $.ajax({
                        dataType: 'json',
                        type: 'POST', 
                        data: appListAction,
                        url: 'admin-ajax.php',
                        success : function( data )
                        {
                            displayingLoadedApps( data.message );
                            if( !$('#apps-notification').hasClass('usn-display-hide') )
                            {
                                $('#apps-notification').addClass('usn-display-hide');
                            }
                        },
                        error : function(jqXHR, textStatus, errorThrown) 
                        {
                            //$('#apps-notification').text = "";
                            console.log("" + JSON.stringify(jqXHR) + " :: " + textStatus + " :: " + errorThrown);
                        }
                    });
                }
            }

            //DISPLAY DATA INTO THE TARGET DATATABLES.
            function displayingLoadedApps( data )
            {
                //Set table column header.
                var columns = [
                    // { "sTitle": "IDENTITY",   "mData": "ID" },
                    { "sTitle": "NAME",   "mData": "app_name" },
                    { "sTitle": "DESCRIPTION",   "mData": "app_info" },
                    { "sTitle": "USER / MATCH",   "mData": "match_cap" },
                    { "sTitle": "MAX USER",   "mData": "max_connect" },
                    { "sTitle": "STATUS",   "mData": "app_status" },
                    { "sTitle": "OWNER",   "mData": "user_login" },
                    {"sTitle": "Action", "mRender": function(data, type, item)
                        {
                            return '' + 

                                '<div class="btn-group" role="group" aria-label="Basic example">' +

                                    '<button type="button" class="btn btn-primary btn-sm"' +
                                        ' data-toggle="modal" data-target="#EditAppOption"' +
                                        ' title="Click this to modified or delete this project."' +
                                        ' data-aid="' + item.ID + '"' +  
                                        ' data-aname="' + item.app_name + '"' +  
                                        ' data-ainfo="' + item.app_info + '"' + 
                                        ' data-mcap="' + item.match_cap + '"' +   
                                        ' data-aurl="' + item.app_website + '"' +  
                                        ' data-asta="' + item.app_status + '"' +  
                                        ' data-acap="' + item.max_connect + '"' +
                                        ' >Options</button>' +

                                    '<button type="button" class="btn btn-secondary btn-sm appkey-' + item.ID + '"' +
                                        ' data-clipboard-text="' + item.app_secret + '"' +
                                        ' onclick="copyFromId(\'appkey-' + item.ID + '\')" ' +
                                        ' title="Click this to copy the project apikey to your clipboard."' +
                                        '>Copy Key</button>' +  

                                    '<button type="button" class="btn btn-success btn-sm"' +
                                        ' onclick="window.location.href = `<?php echo get_home_url()."/wp-admin/admin.php?page=".$_GET['page']."&id="; ?>' + item.ID + '&name=' +item.app_name+ '`;" ' +
                                        ' title="Click this to navigate to variant list of this project."' + 
                                        ' >Variants</button>' +

                                             
                                        
                                '</div>'; 
                        }
                    }
                ];

                //Displaying data on datatables.
                usnapps = $('#apps-datatables').DataTable({
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
            if(typeof usnapps !== 'undefined' && typeof usnapps.on === 'function')
            {
                usnapps.on( 'responsive-resize', function ( e, datatable, columns ) {
                    var count = columns.reduce( function (a,b) {
                        return b === false ? a+1 : a;
                    }, 0 );
                
                    console.log( count +' column(s) are hidden' );
                } );
            }

            //CREATE NEW APP ENTRY ON MODAL.
            $('#create-app-form').submit( function(event) {
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
                            confirmCreateProcess();
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

            function confirmCreateProcess()
            {
                $('#create-app-btn').addClass('disabled');

                //From native form object to json object.
                var unindexed_array = $('#create-app-form').serializeArray();
                var indexed_array = {};

                $.map(unindexed_array, function(n, i){
                    indexed_array[n['name']] = n['value'];
                });
                indexed_array.action = 'CreateNewApp';

                // This will be handled by create-app.php.
                $.ajax({
                    dataType: 'json',
                    type: 'POST', 
                    data: indexed_array,
                    url: 'admin-ajax.php',
                    success : function( data )
                    {
                        if( data.status == 'success' ) {
                            $('#appname_create').val('');
                            $('#appdesc_create').val('');
                            $('#appurl_create').val('');
                            $('#appmtcap_create').val('');
                            $('#appcap_create').val('');
                        }
                        $('#CNAMessage').addClass('alert-'+data.status);
                        $('#CNAMessage').removeClass('usn-display-hide');
                        $('#CNAMcontent').text( data.message );

                        loadingAppList( appDatatables );
                        $('#create-app-btn').removeClass('disabled');
                        activeTimeout = setTimeout( function() {
                            $('#CNAMessage').removeClass('alert-'+data.status);
                            $('#CNAMessage').addClass('usn-display-hide');
                            activeTimeout = 'undefined';
                        }, 4000);
                    },
                    error : function(jqXHR, textStatus, errorThrown) {
                        $('#CNAMessage').addClass('alert-danger');
                        $('#CNAMessage').removeClass('usn-display-hide');
                        $('#CNAMcontent').text( textStatus + ': Kindly consult to your administrator for this issue.' );

                        $('#create-app-btn').removeClass('disabled');
                        activeTimeout = setTimeout( function() {
                            $('#CNAMessage').removeClass('alert-danger');
                            $('#CNAMessage').addClass('usn-display-hide');
                            activeTimeout = 'undefined';
                        }, 7000);
                        console.log("" + JSON.stringify(jqXHR) + " :: " + textStatus + " :: " + errorThrown);
                    }
                });
            }

            // LISTEN FOR MODAL SHOW AND ATTACHED ID.
            $('#CreateNewApp').on('show.bs.modal', function(e) {
                var data = e.relatedTarget.dataset;
                $('#create-app-btn').removeClass('disabled');
                $('#appsta_create').val( 'Active' );
                $('#appmtcap_create').val();
                $('#appcap_create').val();
            });

            // MAKE SURE THAT TIMEOUT IS CANCELLED.
            $('#CreateNewApp').on('hide.bs.modal', function(e) {
                if( typeof activeTimeout !== 'undefined' )
                {
                    clearTimeout( activeTimeout );
                }

                if( !$('#CNAMessage').hasClass('usn-display-hide') )
                {
                    $('#CNAMessage').addClass('usn-display-hide');
                }
            });

            //DELETE OR UPDATE FOCUSED APP ON MODAL.
            $('#edit-app-form').submit( function(event) {
                event.preventDefault();
                var clickedBtnId = $(this).find("button[type=submit]:focus").attr('id');
                $( "#dialog-confirm-edit" ).dialog({
                    title: 'Confirmation',
                    resizable: false,
                    height: "auto",
                    width: 320,
                    modal: false,
                    open: function() {
                        $('#jquery-overlay').removeClass('usn-display-hide');
                        $('#confirm-content-edit').html(
                            '<span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>' +
                            'Please confirm to complete the process, else just press cancel.'
                        );
                    },
                    buttons: {
                    "Confirm": function() 
                    {
                        confirmEditProcess( clickedBtnId );
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

            function confirmEditProcess( clickedBtnId )
            {
                $('#delete-app-btn').addClass('disabled');
                $('#update-app-btn').addClass('disabled');

                //From native form object to json object.
                var postParam = {};

                if( clickedBtnId == 'delete-app-btn' )
                {
                    postParam.action = 'DeleteThisApp';
                    postParam.appid_edit = $('#appid_edit').val();
                }

                else
                {
                    postParam.action = 'UpdateThisApp';
                    postParam.appid_edit = $('#appid_edit').val();
                    postParam.appsta_edit = $('#appsta_edit').val();
                    postParam.appname_edit = $('#appname_edit').val();
                    postParam.appdesc_edit = $('#appdesc_edit').val();
                    postParam.appurl_edit = $('#appurl_edit').val();
                    postParam.appmtcap_edit = $('#appmtcap_edit').val();
                    postParam.appcap_edit = $('#appcap_edit').val();
                }

                // This will be handled by create-app.php.
                $.ajax({
                    dataType: 'json',
                    type: 'POST', 
                    data: postParam,
                    url: 'admin-ajax.php',
                    success : function( data )
                    {
                        if( clickedBtnId == 'delete-app-btn' ) {
                            $('#appname_edit').val('');
                            $('#appdesc_edit').val('');
                            $('#appurl_edit').val('');
                        } else {
                            $('#delete-app-btn').removeClass('disabled');
                            $('#update-app-btn').removeClass('disabled');
                        }
                        
                        $('#DFAMessage').addClass('alert-'+data.status);
                        $('#DFAMessage').removeClass('usn-display-hide');
                        $('#DFAMcontent').text( data.message );

                        loadingAppList( appDatatables );
                        activeTimeout = setTimeout( function() {
                            $('#DFAMessage').removeClass('alert-'+data.status);
                            $('#DFAMessage').addClass('usn-display-hide');
                            if( clickedBtnId == 'delete-app-btn' ) {
                                $('#EditAppOption').modal('hide');
                            }
                            activeTimeout = 'undefined';
                        }, 4000);
                    },
                    error : function(jqXHR, textStatus, errorThrown) {
                        $('#DFAMessage').addClass('alert-danger');
                        $('#DFAMessage').removeClass('usn-display-hide');
                        $('#DFAMcontent').text( textStatus + ': Kindly consult to your administrator for this issue.' );

                        $('#delete-app-btn').removeClass('disabled');
                        $('#update-app-btn').removeClass('disabled');
                        activeTimeout = setTimeout( function() {
                            $('#DFAMessage').removeClass('alert-danger');
                            $('#DFAMessage').addClass('usn-display-hide');
                            activeTimeout = 'undefined';
                        }, 7000);
                        console.log("" + jqXHR + " :: " + textStatus + " :: " + errorThrown);
                    }
                });
            }

            // LISTEN FOR MODAL SHOW AND ATTACHED ID.
            $('#EditAppOption').on('show.bs.modal', function(e) {
                var data = e.relatedTarget.dataset;
                $('#appid_edit').val( data.aid );
                $('#appname_edit').val( data.aname );
                $('#appdesc_edit').val( data.ainfo );
                $('#appurl_edit').val( data.aurl );
                $('#appsta_edit').val( data.asta );
                $('#appmtcap_edit').val( data.mcap );
                $('#appcap_edit').val( data.acap );

                $('#delete-app-btn').removeClass('disabled');
                $('#update-app-btn').removeClass('disabled');
            });

            // MAKE SURE THAT TIMEOUT IS CANCELLED.
            $('#EditAppOption').on('hide.bs.modal', function(e) {
                if( typeof activeTimeout !== 'undefined' ) {
                    clearTimeout( activeTimeout );
                }

                if( !$('#DFAMessage').hasClass('usn-display-hide') ){
                    $('#DFAMessage').addClass('usn-display-hide');
                }
            });

        //#endregion
    });
</script>