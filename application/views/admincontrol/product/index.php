<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
?>


<div id="overlay"></div>
<div class="popupbox" style="display: none;">
			<div class="backdrop box">
				<div class="modalpopup" style="display:block;">
					<a href="javascript:void(0)" class="close js-menu-close" onclick="closePopup();"><i class="fa fa-times"></i></a>
					<div class="modalpopup-dialog">
						<div class="modalpopup-content">
						<div class="modalpopup-body">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12">
		<?php if($this->session->flashdata('success')){?>
			<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('success'); ?> </div>
		<?php } ?>
		<?php if($this->session->flashdata('error')){?>
			<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('error'); ?> </div>
		<?php } ?>
	</div>
</div>
	
	
<!--STORE TABLES START-->
<!--<div class="row">
    <div class="col-<?= $store_setting['status'] ? '12' : '12' ?>">
      <?php if($store_setting['status']){ ?>
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-header m-b-10">
              <h4 class="mt-0 header-title pull-left m-0"><?= __('admin.local_store_overview') ?></h4>
              <div class="pull-right">
                <?php if($totals['store']['hold_orders']){?>
                  <div   data-toggle="tooltip" title="Hold Orders">
                    <a href="<?= base_url('admincontrol/mywallet') ?>" class="order-hold-noti">
                      <i class="fa fa-bell"></i>
                      <span><?= $totals['store']['hold_orders'] ?></span>
                    </a>
                  </div>
                <?php } ?>
              </div>
            </div>
            <div role="tabpanel">
            <div role="tabpanel" class="tab-pane active" id="all-store">


				<ul class="list-group">
					<li class="list-group-item"><?php echo __( 'admin.local_store_aff_pro' ) ?></li>

					<li class="list-group-item"><?php echo __( 'admin.total_balance' ) ?>
					<span class="badge badge-light font-14 pull-right">
					<?php echo c_format($totals['store']['balance']) ?></span></li>

					<li class="list-group-item"><?php echo __( 'admin.total_sales' ) ?>
					<span class="badge badge-light font-14 pull-right">
					<?php echo c_format($totals['store']['balance']) ?> / <?php echo c_format($totals['all_sale_comm']) ?></span></li>

					<li class="list-group-item"><?php echo __( 'admin.total_clicks' ) ?>
					<span class="badge badge-light font-14 pull-right">
					<?php echo (int)$totals['store']['click_count'] ?> /  <?php echo c_format($totals['store']['click_amount']) ?></span></li>

					<li class="list-group-item"><?php echo __( 'admin.total_commission' ) ?>
					<span class="badge badge-light font-14 pull-right">
					<?php echo c_format($totals['store']['total_commission']) ?></span></li>

					<li class="list-group-item"><?php echo __( 'admin.total_clients' ) ?>
					<span class="badge badge-light font-14 pull-right">
					<?php echo (int)$client_count; ?></span></li>

					<li class="list-group-item"><?php echo __( 'admin.total_orders' ) ?>
					<span class="badge badge-light font-14 pull-right">
					<?php echo $ordercount; ?></span></li>

					<li class="list-group-item">
					<span class="badge badge-light"><?php $store_url = base_url('store'); ?></span>
					<a class="btn btn-lg btn-default btn-success" href="<?php echo $store_url ?>"
					target="_blank"><?= __('admin.priview_store') ?></a></li>
				</ul>

				<div class="clearfix"></div>
				</div>
         </div>
        </div>
    </div>
    <?php } ?>
 </div>
</div>-->
<!--STORE TABLES END-->

<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-body">
				<b>Store URL</b>
				<div class="row top-panel">
					<span class="m-b-5">
						<div class="share-store-list">
							<a class="btn btn-lg btn-default btn-success" href="<?php echo base_url("admincontrol/addproduct") ?>"><?= __('admin.add_product') ?></a>
							<a class="btn btn-lg btn-default btn-success" href="<?php echo base_url("admincontrol/form_manage") ?>"><?= __('admin.add_form') ?></a>
						</div>
					</span>
					<span class="m-b-5" style="width: 400px;">
						<?php $store_url = base_url('store'); ?>
						<div class="input-group">
							<input type="text" id="store-link" readonly="readonly" value="<?php echo $store_url ?>" class="form-control">
							<button onclick="copy_text()" class="input-group-addon">
								<img src="<?php echo base_url('assets/images/clippy.svg') ?>" class="tooltiptext" width="25px" height="25px" alt="Copy to clipboard">
							</button>
						</div>
					</span>
					<span class="m-b-5">
						<div class="share-store-list">
							<a class="btn btn-lg btn-default btn-success" href="<?php echo $store_url ?>" target="_blank"><?= __('admin.priview_store') ?></a>
						</div>
					</span>
					<span>
						<button style="display:none;" type="button" class="btn btn-lg btn-danger" name="deletebutton" id="deletebutton" value="Save & Exit" onclick="deleteuserlistfunc('deleteAllproducts');"><?= __('admin.delete_products') ?></button>
					</span>
				</div>
				<br>

				<div class="filter">
					<form>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Category</label>
									<select name="category_id" class="form-control">
										<?php $selected = isset($_GET['category_id']) ? $_GET['category_id'] : ''; ?>
										<option value="">All Category</option>
										<?php foreach ($categories as $key => $value) { ?>
											<option <?= $selected == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>	
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label d-block">&nbsp;</label>
									<button type="submit" class="btn btn-primary">Search</button>
								</div>
							</div>	
						</div>
					</form>
				</div>
				
			    <?php if ($productlist == null) {?>
                    <div class="text-center">
                    <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                 	<h3 class="m-t-40 text-center text-muted"><?= __('admin.no_products') ?></h3></div>
                <?php } else { ?>
                	<div class="table-responsive b-0" data-pattern="priority-columns">
						<form method="post" name="deleteAllproducts" id="deleteAllproducts" action="<?php echo base_url('admincontrol/deleteAllproducts'); ?>">
							<table id="tech-companies-1" class="table  table-striped">
								<thead>
									<tr>
										<th><input name="product[]" type="checkbox" value="" onclick="checkAll(this)"></th>
										<th width="220px"><?= __('admin.product_name') ?></th>
										<th><?= __('admin.featured_image') ?></th>
										<th><?= __('admin.price') ?></th>
										<th><?= __('admin.sku') ?></th>
										<th width="220px"><?= __('admin.get_ncommission') ?></th>
										<th><?= __('admin.sales_/_commission') ?></th>
										<th><?= __('admin.clicks_/_commission') ?></th>
										<th><?= __('admin.total') ?></th>
										<th><?= __('admin.display') ?></th>
										<th><?= __('admin.action') ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$pro_setting = $this->Product_model->getSettings('productsetting');
									?>

									<?php foreach($productlist as $product){ ?>
										<?php 
											$productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] );
										?>
										<tr>
											<td><input name="checkbox[]" type="checkbox" id="check<?php echo $product['product_id'];?>" value="<?php echo $product['product_id'];?>" onclick="checkonly(this,'check<?php echo $product['product_id'];?>')"></td>
											<td>
												<div class="tooltip-copy">
													<?php if($product['product_type'] == 'downloadable'){ ?>
														<img src="<?= base_url('assets/images/download.png') ?>" width="20px">
													<?php } ?>
													<span><?php echo $product['product_name'];?></span>
													<div> <small>
														<a target="_blank" href="<?= $productLink ?>">Public Page</a>
													</small></div>
												</div>
											</td>
											<td>
												<div class="tooltip-copy">
													<img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $product['product_featured_image'] ,100,100) ?>" ><br>
												</div>
											</td>
											<td class="txt-cntr"><?php echo c_format($product['product_price']); ?></td>
											<td class="txt-cntr"><?php echo $product['product_sku'];?></td>
											<td class="txt-cntr">
												<b>Sale</b> : 
												<?php

													if($product['product_commision_type'] == 'default'){
														if($default_commition['product_commission_type'] == 'percentage'){
															echo $default_commition['product_commission']. "%";
														}
														else
														{
															echo c_format($default_commition['product_commission']);
														}
													}
													else if($product['product_commision_type'] == 'percentage'){
														echo $product['product_commision_value']. "%";
													}
													else{
														echo c_format($product['product_commision_value']);
													}
												?>
												
												<br> <b>Click</b> :
												<?php
											    	if($product['product_click_commision_type'] == 'default'){
														echo "<small>{$default_commition['product_noofpercommission']} Click for  "; 	
														echo c_format($default_commition['product_ppc']);
														echo "</small>";
													}
													else{
														echo "<small>{$product['product_click_commision_per']} Click for : ";
														echo c_format($product['product_click_commision_ppc']) ."</small>";
													}
												?>

												<?php 
													if($product['product_recursion_type']){
										           		if($product['product_recursion_type'] == 'custom'){
										           			if($product['product_recursion'] != 'custom_time'){
										           				echo '<b>'. __('admin.recurring') .' </b> : ' .  __('admin.'.$product['product_recursion']);
										           			} else {
										           				echo '<b>'. __('admin.recurring') .' </b> : '. timetosting($product['recursion_custom_time']);
										           			}
										           		} else{
															if($pro_setting['product_recursion'] == 'custom_time' ){
									           					echo '<b>'. __('admin.recurring') .' </b> : '. timetosting($pro_setting['recursion_custom_time']);
															} else {
																echo '<b>'. __('admin.recurring') .' </b> : '.  __('admin.'.$pro_setting['product_recursion']);
															}
										           		}
										           	}
												?>
											</td>
											<td class="txt-cntr">
												<?php echo $product['order_count'];?> / 
												<?php echo c_format($product['commission']) ;?>
											</td>
											<td class="txt-cntr">
												<?php echo (int)$product['commition_click_count'];?> / 
												<?php echo c_format($product['commition_click']) ;?>
											</td>
											<td class="txt-cntr">
												<?php echo
													c_format((float)$product['commition_click'] + (float)$product['commission']);
												?>
											</td>
											<td class="txt-cntr"><?= $product['on_store'] == '1' ? 'Yes' : 'No' ?></td>
											<td class="txt-cntr">
												<a class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to Edit?');" href="<?php echo base_url();?>admincontrol/updateproduct/<?php echo $product['product_id'];?>"><i class="fa fa-edit cursors" aria-hidden="true"></i></a>
												<a class="btn btn-sm btn-primary" href="<?php echo base_url('admincontrol/productupload/'. $product['product_id']);?>"><i class="fa fa-image cursors"></i></a>
                                    			<a class="btn btn-sm btn-primary" href="<?php echo base_url('admincontrol/videoupload/'. $product['product_id']);?>"><i class="fa fa-youtube cursors"></i></a>
												<button class="btn btn-danger btn-sm delete-product" type="button" data-id="<?= $product['product_id'] ?>"> <i class="fa fa-trash"></i> </button>

												<div>
													<div class="code-share-<?= $key ?>"></div>
	                                                <script type="text/javascript">
	                                                    $(document).on('ready',function(){
	                                                        $(".code-share-<?= $key ?>").jsSocials({
	                                                            url: "<?= $productLink ?>",
	                                                            showCount: false,
	                                                            showLabel: false,
	                                                            shareIn: "popup",
	                                                            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
	                                                        });
	                                                    })
	                                                </script>
												</div>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</form>
					</div>
				<?php } ?>
			</div>
		</div> <!-- end col -->
	</div> <!-- end row -->
</div>


		


<script type="text/javascript" async="">

	$(".show-more").on('click',function(){
		$(this).parents("tfoot").remove();
		$("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
	});

	$(".delete-button").on('click',function(){
		if(!confirm("Are you sure ?")) return false;
	});
	$(".toggle-child-tr").on('click',function(){
		$tr = $(this).parents("tr");
		$ntr = $tr.next("tr.detail-tr");

		if($ntr.css("display") == 'table-row'){
			$ntr.hide();
			$(this).find("i").attr("class","fa fa-plus");
		}else{
			$(this).find("i").attr("class","fa fa-minus");
			$ntr.show();
		}
	})
	
	function checkAll(bx) {
		var cbs = document.getElementsByTagName('input');
		if(bx.checked)
		{
			document.getElementById('deletebutton').style.display = 'block';
			} else {
			document.getElementById('deletebutton').style.display = 'none';
		}
		for(var i=0; i < cbs.length; i++) {
			if(cbs[i].type == 'checkbox') {
				cbs[i].checked = bx.checked;
			}
		}
	}
	function checkonly(bx,checkid) {
		if($(".list-checkbox:checked").length){
			$('#deletebutton').show();
		} else {
			$('#deletebutton').hide();
		}
	}
	function deleteuserlistfunc(formId){
		if(! confirm("Are you sure ?")) return false;

		$('#'+formId).submit();
	}
	
	/*function copy_text() {
		var copyText = document.getElementById("store-link");
		copyText.select();
		document.execCommand("Copy");
	}*/

	function closePopup(){
		$('.popupbox').hide();
		$('#overlay').hide();
	}
	function generateCode(affiliate_id){
		$('.popupbox').show();
		$('#overlay').show();
		$('.modalpopup-body').load('<?php echo base_url();?>admincontrol/generateproductcode/'+affiliate_id);
		$('.popupbox').ready(function () {
			$('.backdrop, .box').animate({
				'opacity': '.50'
			}, 300, 'linear');
			$('.box').animate({
				'opacity': '1.00'
			}, 300, 'linear');
			$('.backdrop, .box').css('display', 'block');
		});
	}

	$(".delete-product").on('click',function(){
		if(! confirm("Are you sure ?")) return false;
		window.location = $("#deleteAllproducts").attr("action") + "?delete_id=" + $(this).attr("data-id");
	})
</script>			