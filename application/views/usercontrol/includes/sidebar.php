<?php	
	$db =& get_instance();
	$userdetails=$db->Product_model->userdetails('user');
	$store_setting =$db->Product_model->getSettings('store');
	$SiteSetting =$db->Product_model->getSettings('site');
	$refer_status =$db->Product_model->my_refer_status($userdetails['id']);

	$db->Product_model->ping($userdetails['id']);
?>
<?php
$csss = array(
    'sidebar_background_color'                      =>  array('type' => 'background', 'selectotr' => '.left.side-menu, .left.side-menu, #sidebar-menu, .left.side-menu, #sidebar-menu .custom-menu-link a'),
    'sidebar_menu_background_color'                 =>  array('type' => 'background', 'selectotr' => '.left.side-menu #sidebar-menu li:not(.custom-menu-link) a'),
    'sidebar_menu_text_color'                       =>  array('type' => 'color', 'selectotr' => '.left.side-menu #sidebar-menu li:not(.custom-menu-link) a'),
    'sidebar_menu_bottom_links_background_color'    =>  array('type' => 'background', 'selectotr' => '.left.side-menu #sidebar-menu .custom-menu-link a span'),
    'sidebar_menu_bottom_links_text_color'          =>  array('type' => 'color', 'selectotr' => '.left.side-menu #sidebar-menu .custom-menu-link a span'),
);
?>
<style type="text/css">
<?php 
$setting = $db->Product_model->getSettings('affiliateside');
foreach ($csss as $key => $d) {
    if(isset($setting[$key]) && $setting[$key] != ''){
        echo "\n{$d['selectotr']}{";
        echo "\t {$d['type']} : ".$setting[$key]. "!important;" ;
        echo "}";
    }
} 
?>
</style>
 <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <div id="sidebar-menu">
                        
		<?php 
            $logo = base_url($SiteSetting['logo'] ? 'assets/images/site/'.$SiteSetting['logo'] : 'assets/vertical/assets/images/no-logo-coming-soon.png');
        ?>
        <center><img style="max-width: 125px;margin-top:10px;" src="<?= $logo ?>" id="logo" class="img-fluid"></center>
        
			<ul>
				<li>
					<a href="<?php echo base_url(); ?>usercontrol/dashboard" class="waves-effect">
						<i class="mdi mdi-view-dashboard"></i>
						<span> <?= __('user.dashboard') ?></span>
					</a>
				</li>
				
				    <li><a href="<?php echo base_url();?>usercontrol/store_markettools/"><i class="mdi mdi-wallet"></i><?= __('user.all_markettools') ?></a></li>
				    <li><a href="<?php echo base_url();?>usercontrol/listproduct/"><i class="mdi mdi-store"></i><?= __('user.products_list') ?></a></li>
			     	<li><a href="<?php echo base_url();?>usercontrol/store_orders/"><i class="mdi mdi-wallet"></i><?= __('user.my_all_orders') ?></a></li>
			     	<li><a href="<?php echo base_url();?>usercontrol/store_logs/"><i class="mdi mdi-wallet"></i><?= __('user.my_all_logs') ?></a></li>
			     	<li><a href="<?php echo base_url();?>usercontrol/mywallet/"><i class="mdi mdi-wallet"></i><?= __('user.my_wallet') ?></a></li>
			     	<li><a href="<?php echo base_url('/usercontrol/my_network'); ?>" class="waves-effect"><i class="mdi mdi-view-dashboard"></i><span> <?= __('user.page_title_my_network') ?></span></a></li>
			     	<li><a href="<?php echo base_url('/ReportController/user_reports'); ?>" class="waves-effect"><i class="mdi mdi-account-settings-variant"></i><span> <?= __('user.my_page_title_user_reports') ?></span></a></li>
				
				
				
				
			     	<!-- <li><a href="<?php echo base_url();?>usercontrol/wallet/withdraw"><i class="mdi mdi-wallet"></i><?= __('user.my_payouts') ?></a></li>
			     	<li><a href="<?php echo base_url();?>usercontrol/addpayment/"><i class="mdi mdi-wallet"></i><?= __('user.my_payment_details') ?></a></li> -->
			     	
				
					<li class="has_sub">
					<!-- <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-wallet"></i> <span> <?= __('user.menu_integration') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="<?php echo base_url('integration/user_integration_tools'); ?>"><?= __('user.menu_integration_tool') ?></a></li>
						<li><a href="<?php echo base_url('integration/user_orders'); ?>"><?= __('user.menu_integration_order') ?></a></li>
						<li><a href="<?php echo base_url();?>integration/click_logs"><?= __('user.sub_menu_integration_logs') ?></a></li>
					</ul> -->
				</li>
				<?php if($store_setting['status']) { ?>
				<!-- <li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-store"></i> <span> <?= __('user.affiliate_store') ?> </span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="<?php echo base_url();?>usercontrol/listproduct/"><?= __('user.products_list') ?></a></li>
						<li><a href="<?php echo base_url();?>usercontrol/form/"><?= __('user.menu_form_list') ?></a></li>
						<li><a href="<?php echo base_url();?>usercontrol/listbuyaffiproduct/"><?= __('user.order_list') ?></a></li>
						
					</ul>
				</li> -->
				<?php } ?>
				
			<?php if($refer_status){ ?>
			

				<!-- <li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-wallet"></i> <span> <?= __('user.my_referals') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
				<li>
					<a href="<?php echo base_url();?>usercontrol/managereferenceusers/" class="waves-effect">
					<i class="mdi mdi-wallet-membership"></i><span><?= __('user.referred_users') ?></span></a>
				</li>
				<li>
					<a href="<?php echo base_url();?>usercontrol/myreferal/" class="waves-effect">
					<i class="mdi mdi-wallet-membership"></i><span><?= __('user.referred_users_tree') ?></span></a>
				</li>
				<li>
					<a href="<?php echo base_url();?>usercontrol/userslisttree/" class="waves-effect">
					<i class="mdi mdi-wallet-membership"></i><span><?= __('user.menu_referring_tree') ?></span></a>
				</li>
					</ul>
				</li> -->
			<?php } ?>

			
			
				<!-- <li class="has_sub">
					<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-account-settings-variant"></i> <span><?= __('user.reports') ?></span> <span class="float-right"><i class="mdi mdi-chevron-right"></i></span></a>
					<ul class="list-unstyled">
					    <li><a href="<?= base_url('incomereport/statistics') ?>" class="waves-effect" ><i class="mdi mdi-layers"></i> <span> <?= __('user.menu_statistics') ?> </span> </a></li>
						<li><a href="<?php echo base_url();?>ReportController/user_transaction/" class="waves-effect"><i class="mdi mdi-settings"></i><span><?= __('user.menu_report_all_transactions') ?></span></a></li>		
						<li><a href="<?php echo base_url();?>ReportController/user_statistics/" class="waves-effect"><i class="mdi mdi-settings"></i><span><?= __('user.menu_report_statistics') ?></span></a></li>						
					</ul>
				</li> -->
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
    <!-- Start right Content here -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <!-- ==================
                         PAGE CONTENT START
                         ================== -->
                    <div class="page-content-wrapper">
                        <div class="container-fluid">
                        	<?php 
                        		require APPPATH."config/breadcrumb.php";
                        		$pageKey = $db->Product_model->page_id();
                        	?>
                        	<script type="text/javascript">console.log('Page ID : <?= $pageKey ?>')</script>
                        	<?php if(isset($pageSetting[$pageKey])){ ?>
	                            <div class="row">
	                                <div class="col-sm-12">
	                                    <div class="page-title-box">
	                                        <div class="float-right">
	                                            <ol class="breadcrumb hide-phone p-0 m-0">
	                                            	<?php 
	                                            		$count = count($pageSetting[$pageKey]['breadcrumb']); 
	                                            		foreach ($pageSetting[$pageKey]['breadcrumb'] as $key => $value) { 
                                            		?>
	                                                	<li class="breadcrumb-item <?= $count == $key ? 'active' : '' ?>">
	                                                		<a href="<?= $value['link'] ?>"><?= $value['title'] ?></a>
	                                                	</li>
	                                            	<?php } ?>
	                                            </ol>
	                                        </div>
	                                        <h4 class="page-title"><?= $pageSetting[$pageKey]['title'] ?></h4>
	                                    </div>
	                                </div>
	                            </div>
                        	<?php } ?>	
                        </div>
                    </div>