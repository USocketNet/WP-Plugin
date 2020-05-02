
<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) 
	{
		exit;
	}

	/**
	 * @package bytescsrafter-usocketnet
	*/
?>

	<?php /* Header Section */ ?>
		<div class="usn-welcome-header">
			<h1>PROJECT BROWSER</h1>
			<p>
                Add, Edit, Delete projects as well as its variants. Each project will have a unique id to connect.
			</p>
		</div>
	<?php /* Header Section */ ?>

	<?php if( !isset($_GET['project']) ) { ?>

		<div class="usn-panel-body">
			<div class="usn-panel-first">
				<button id="RefreshAppList" type="button" class="btn btn-dark">Refresh List</button>
				<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#CreateNewApp">Create Project</button>
			</div>
			<table id="apps-datatables" class="stripe" style="width: 100%;"></table>
			<div id="apps-notification" class="alert alert-info usn-center-item " role="alert" style="margin-top: 20px;">
				Currently fetching updates for all available applications. Please wait...
			</div>
		</div>
		<?php include_once( plugin_dir_path( __FILE__ ) . "/project-browser/projects.php" ); ?>

	<?php } else { ?>

		<div class="usn-panel-body">
			<div class="usn-panel-first">
				<button type="button" class="btn btn-dark" onclick="window.location.href = '<?php echo get_home_url()."/wp-admin/admin.php?page=".$_GET['page']; ?>';" >Go Back</button>
				<button id="RefreshAppList" type="button" class="btn btn-dark">Refresh List</button>
				<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#CreateNewApp">Create Variant</button>
			</div>
			<table id="apps-datatables" class="stripe" style="width: 100%;"></table>
			<div id="apps-notification" class="alert alert-info usn-center-item " role="alert" style="margin-top: 20px;">
				Currently fetching updates for all available applications. Please wait...
			</div>
		</div>
		<?php include_once( plugin_dir_path( __FILE__ ) . "/project-browser/variants.php" ); ?>
	<?php } ?>

	<?php include_once( plugin_dir_path( __FILE__ ) . "/project-browser/create-modal.php" ); ?>
	<?php include_once( plugin_dir_path( __FILE__ ) . "/project-browser/edit-modal.php" ); ?>
	<div id="jquery-overlay" class="modal-backdrop fade show usn-display-hide" style="z-index: 9999;"></div>
	