
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
            wp_die();
        }

        global $wpdb; //Reference to wp mysql conn.
        $appsTable = USN_PROJECT_TAB;

        $app_parent = $_POST['appid_edit'];
        $app_variants = $wpdb->get_results("SELECT app_owner, wp_users.user_login FROM $appsTable, wp_users WHERE app_parent = $app_parent");

        if( count($app_variants) >= 1 )
        {
            echo json_encode( 
                array( 
                    'status'=>'danger',
                    'message'=>'This project contains total variant of ' . count($app_variants) . ' owned by '.$app_variants[0]->user_login.', etc.'
                ) 
            );
            wp_die();
        }

        $rows = $wpdb->get_results( "DELETE FROM $appsTable WHERE ID = ".$_POST['appid_edit']);

        if( $rows !== FALSE ) {
            echo json_encode( array('message'=>'The project has been removed successfully.') );
        } else {
            echo json_encode( array('message'=>'There was a problem on deleting this project.') );
        }
        wp_die();
    }

?>