
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

	/**
	 * @package bytescrafter-usocketnet-backend
	*/
?>

	<?php /* Header Section */ ?>
		<div class="usn-welcome-header">
			<h1>CLUSTER VIEWER</h1>
            <p>  
                If a USocketNet is pointed to WordPress site and has an admin credential will be automatically be added here.
			</p>
		</div>
    <?php /* Header Section */ ?>
    
	<div class="usn-panel-body">
        <div class="usn-panel-first">
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#CreateNewApp">Create New</button>
		</div>
		<table id="apps-datatables" class="stripe" style="width: 100%;"></table>
		<div id="apps-notification" class="alert alert-info usn-center-item " role="alert" style="margin-top: 20px;">
			Currently fetching updates for all available applications. Please wait...
		</div>
		<div class="usn-cluster-body container">
            <ul id="usn-cluster-viewer" class="row">
                <?php //include_once( plugin_dir_path( __FILE__ ) . "/cluster-viewer/_core.php" ); ?>

                <li class="col-sm-12">
                    <div class="row">
                        <?php include_once( plugin_dir_path( __FILE__ ) . "/cluster-viewer/cpu-part.php" ); ?>
                        <?php include_once( plugin_dir_path( __FILE__ ) . "/cluster-viewer/ram-part.php" ); ?>
                        <?php include_once( plugin_dir_path( __FILE__ ) . "/cluster-viewer/net-part.php" ); ?>
                        <?php include_once( plugin_dir_path( __FILE__ ) . "/cluster-viewer/opt-part.php" ); ?>
                    </div>
                </li>
            </ul>
        </div>
	</div>
