<div class="alert alert-primary" role="alert">
  <p>If you are using an External store like External store like: Woocommerce, Magento, Prestashop, OpenCart, Shopify, BigCommerce, OsCommerce, ZenCart, Xcart, </p>
        <p>you will be able to create your commission program for sale and clicks and share it with your affiliates.</p>
</div>

  
<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<div>
							<h4 class="mt-0 header-title"><?= __('admin.integration_programs') ?></h4>
							<div class="pull-right">
								<a class="btn btn-primary btn-sm" href="<?= base_url('integration/programs_form') ?>"><?= __('admin.add_new') ?></a>
							</div>
						</div>
					</div>
					<div class="body">
						<div class="table-rep-plugin">
                        <div class="table-responsive b-0" data-pattern="priority-columns">
                            
                             <div class="text-center">
                                <?php if ($programs ==null) {?>
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center"><?= __('admin.not_activity_yet') ?></h3>
                                <?php }
                                else {?>
        
                            <table id="tech-companies-1" class="table  table-striped">
								<thead>
									<tr>
										<th><?= __('admin.id') ?></th>
										<th><?= __('admin.name') ?></th>
										<th><?= __('admin.sale_commission') ?></th>
										<th><?= __('admin.click_commission') ?></th>
										<th><?= __('admin.sale_status') ?></th>
										<th><?= __('admin.click_status') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($programs as $key => $program) { ?>
										<tr>
											<td><?= $program['id'] ?></td>
											<td><?= $program['name'] ?></td>
											<td>
												<?php 
													if($program['commission_type'] == 'percentage'){ echo $program['commission_sale'].'%'; }
													else if($program['commission_type'] == 'fixed'){ echo c_format($program['commission_sale']); }
												?>
											</td>
											<td>
												<?php 
													echo c_format($program["commission_click_commission"]). " per ". $program['commission_number_of_click'] ." Clicks";
												?>
											</td>
											<td><?= $program['sale_status'] ? 'Enable' : 'Disable' ?></td>
											<td><?= $program['click_status'] ? 'Enable' : 'Disable' ?></td>
											<td>
												<a class="btn btn-primary btn-sm" href="<?= base_url('integration/programs_form/'. $program['id']) ?>"><?= __('admin.edit') ?></a>
												<button class="btn btn-danger btn-sm delete-program" data-id="<?= $program['id'] ?>"><?= __('admin.delete') ?></button>
											</td>
										</tr>
									<?php } ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<div class="modal fade" id="message-model">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body text-center"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".delete-program").on('click',function(){
		$this = $(this);
		if(!confirm("Are you sure?")) return false;
		$.ajax({
			url:'<?= base_url('integration/delete_programs_form/') ?>',
			type:'POST',
			dataType:'json',
			data:{id: $this.attr("data-id")},
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				if(json['success']){
					$this.parents("tr").remove();
					location.reload();
				}

				if(json['message']){
					$("#message-model .modal-body").html(json['message']);
					$("#message-model").modal("show");
				}
			},
		})
	})
</script>