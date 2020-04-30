
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package bytescsrafter-usocketnet
	*/
?>

<?php

    add_action('wp_ajax_DeleteThisApp', 'DeleteThisApp');
    add_action('wp_ajax_nopriv_DeleteThisApp', 'DeleteThisApp');
    function DeleteThisApp() 
    { 
        if( !isset($_POST['appid_edit']) )
        {
            echo json_encode( 
                array(
                    'status'=>'danger', 
                    'message'=>'Project id is required during project deletion, contact your administrator.'
                ) 
            );
        }

        global $wpdb; //Reference to wp mysql conn.
        $appsTable = USN_PROJECT_TAB;

        $rows = $wpdb->get_results( "DELETE FROM $appsTable WHERE ID = ".$_POST['appid_edit']);

        if( $rows !== FALSE ) {
            echo json_encode( array('message'=>'The project has been removed successfully.') );
        } else {
            echo json_encode( array('message'=>'There was a problem on deleting this project.') );
        }

        wp_die();
    }

?>