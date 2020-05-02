
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * @package bytescrafter-usocketnet-backend
	*/
?>

<?php

    //Require the USocketNet class which have the core function of this plguin. 

    require plugin_dir_path(__FILE__) . '/class-auth.php';
    require plugin_dir_path(__FILE__) . '/class-verify.php';
    require plugin_dir_path(__FILE__) . '/class-cluster-verify.php';
    require plugin_dir_path(__FILE__) . '/class-cluster-list.php';
    require plugin_dir_path(__FILE__) . '/class-project-verify.php';
    require plugin_dir_path(__FILE__) . '/class-project-list.php';

    //require plugin_dir_path(__FILE__) . '/class-demoguy.php';
    include_once( plugin_dir_path( __FILE__ ) . '/demo-callback.php' );

    // Init check if USocketNet successfully request from wapi.
    function bytescrafter_usocketnet_route()
    {
        register_rest_route( 'usocketnet/v1', 'auth', array(
            'methods' => 'POST',
            'callback' => array('BC_USN_Authenticate','initialize'),
        ));

        register_rest_route( 'usocketnet/v1', 'verify', array(
            'methods' => 'POST',
            'callback' => array('BC_USN_Verification','initialize'),
        ));

        register_rest_route( 'usocketnet/v1/cluster', 'verify', array(
            'methods' => 'POST',
            'callback' => array('BC_USN_ClusterVerify','initialize'),
        ));

        register_rest_route( 'usocketnet/v1/cluster', 'list', array(
            'methods' => 'POST',
            'callback' => array('BC_USN_ClusterList','initialize'),
        ));

        register_rest_route( 'usocketnet/v1/project', 'verify', array(
            'methods' => 'POST',
            'callback' => array('BC_USN_ProjectVerify','initialize'),
        ));

        register_rest_route( 'usocketnet/v1/project', 'list', array(
            'methods' => 'POST',
            'callback' => array('BC_USN_ProjectList','initialize'),
        ));
    }
    add_action( 'rest_api_init', 'bytescrafter_usocketnet_route' );

?>