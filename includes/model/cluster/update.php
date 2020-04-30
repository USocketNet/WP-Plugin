
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

    add_action('wp_ajax_UpdateThisCluster', 'UpdateThisCluster');
    add_action('wp_ajax_nopriv_UpdateThisCluster', 'UpdateThisCluster');
    function UpdateThisCluster() 
    { 
        if( !isset($_POST['cluster_id']) || !isset($_POST['cluster_name'])  || !isset($_POST['cluster_info']) || !isset($_POST['cluster_hostname']) || !isset($_POST['cluster_capacity']) )
        {
            echo json_encode( 
                array(
                    'status'=>'danger', 
                    'message'=>'All inputs is required and neccesary for cluster to be updated.'
                ) 
            );
        }

        $cluster_id = $_POST['cluster_id'];
        $cluster_name = $_POST['cluster_name'];
        $cluster_info = $_POST['cluster_info'];
        $cluster_hostname = $_POST['cluster_hostname'];
        $cluster_capacity = $_POST['cluster_capacity'];

        global $wpdb; //Reference to wp mysql conn.
        $clusterTable = USN_CLUSTER_TAB;

        $updates = $wpdb->get_results( "UPDATE $clusterTable SET cluster_name = '$cluster_name', cluster_info = '$cluster_info', cluster_hostname = '$cluster_hostname', cluster_capacity = '$cluster_capacity', cluster_capacity = '$cluster_capacity' WHERE ID = '$cluster_id'" );

        if( $updates !== FALSE ) {
            echo json_encode( array('message'=>'The cluster has been updated successfully.') );
        } else {
            echo json_encode( array('message'=>'There was a problem on updating this cluster.') );
        }

        wp_die();
    }

?>