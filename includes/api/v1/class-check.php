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
	class BC_USN_ClusterQuery {

		public static function initialize() {

			// STEP 1: Check if WPID and SNID is passed as this is REQUIRED!
			if ( !isset($_POST["securekey"]) ) {
				return rest_ensure_response( 
					array(
						"status" => "unknown",
						"message" => "Please contact your administrator. Verification Unknown!",
					)
				);
			}
			$securekey = $_POST["securekey"];

			global $wpdb; //Reference to wp mysql conn.
            $clusterTable = USN_CLUSTER_TAB;

            $clusterCheck = $wpdb->get_results("SELECT cluster_name, cluster_capacity, wp_users.user_login FROM $clusterTable, wp_users WHERE cluster_secretkey = '$securekey'");


            if( count($clusterCheck) >= 1 ) {
                return rest_ensure_response( 
                    array( 
                        'status' => 'success',
                        'data' => $clusterCheck[0]
                    ) 
                );
            } else {
                return rest_ensure_response( 
                    array( 
                        'status'=>'error',
                        'message'=>'Cluster secret key: ' . $securekey . ' is unknown!'
                    ) 
                );
            }

		}
	}

?>