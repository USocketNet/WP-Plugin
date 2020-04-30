
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

	/**
	 * @package bytescrafter-usocketnet
	*/
?>

<?php

    function usocketnet_activate() {
        global $wpdb;

        // #region CREATING TABLE FOR Clusters
            $usn_cluster_tab = USN_CLUSTER_TAB;
            if($wpdb->get_var( "SHOW TABLES LIKE '$usn_cluster_tab'" ) != $usn_cluster_tab) {
                $sql = "CREATE TABLE `".$usn_cluster_tab."` (";
                    $sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
                    $sql .= "`cluster_name` varchar(49) NOT NULL, ";
                    $sql .= "`cluster_info` bigint(20) NOT NULL, ";
                    $sql .= "`cluster_owner` enum('Active','Inactive') NOT NULL DEFAULT 'Active', ";
                    $sql .= "`cluster_hostname` varchar(120) NOT NULL, ";
                    $sql .= "`cluster_secretkey` varchar(255) NOT NULL, ";
                    $sql .= "`cluster_capacity` int(10) NOT NULL DEFAULT '1000', ";
                    $sql .= "`date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
                    $sql .= "PRIMARY KEY (`ID`), ";
                    $sql .= "UNIQUE  (`cluster_name`) ";
                    $sql .= ") ENGINE = InnoDB; ";
                $result = $wpdb->get_results($sql);
                
            }
        // #endregion

        // #region CREATING TABLE FOR Projects
        $usn_project_tab = USN_PROJECT_TAB;
        if($wpdb->get_var( "SHOW TABLES LIKE '$usn_project_tab'" ) != $usn_project_tab) {
            $sql = "CREATE TABLE `".$usn_project_tab."` (";
                $sql .= "`ID` bigint(20) NOT NULL AUTO_INCREMENT, ";
                $sql .= "`app_secret` varchar(49) NOT NULL, ";
                $sql .= "`app_owner` bigint(20) NOT NULL, ";
                $sql .= "`app_status` enum('Active','Inactive') NOT NULL DEFAULT 'Active', ";
                $sql .= "`app_name` varchar(120) NOT NULL, ";
                $sql .= "`app_info` varchar(255) NOT NULL, ";
                $sql .= "`app_website` varchar(255) NOT NULL, ";
                $sql .= "`max_connect` int(7) NOT NULL DEFAULT '1000', ";
                $sql .= "`date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
                $sql .= "PRIMARY KEY (`ID`), ";
                $sql .= "UNIQUE  (`app_name`) ";
                $sql .= ") ENGINE = InnoDB; ";
            $result = $wpdb->get_results($sql);
            
        }
        // #endregion
    } 
    add_action( 'activated_plugin', 'usocketnet_activate' );

    /**
     * Deactivation hook.
     */
    function usocketnet_deactivate() {
        //echo "DEACTIVATED";
    }
    register_deactivation_hook( __FILE__, 'usocketnet_deactivate' );

?>