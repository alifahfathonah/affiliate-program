<div class="alert alert-primary" role="alert">
  <p>Integrations Tools: In this page, you can add a new integration tool like banner/link/text/video and publish it to your affiliates.
  </p>
</div>

<div class="row">
			<div class="col-12">
			    <div class="card m-b-20">
                    <div class="card-body">
				<div class="row">
					<!-- <div class="col-sm-3">
						<div class="card m-b-30 text-white bg-primary">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <h3><?php echo __('admin.banners') ?></h3>
                                 	<a href="<?= base_url('integration/integration_tools_form/banner') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i> <?php echo __('admin.create_new') ?></a>
                                </blockquote>
                            </div>
                        </div>
					</div> -->
					<!--  -->
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-secondary">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <!-- <h3><?= __('admin.text_ads') ?></h3> -->
                                    <h3><?= __('Create Campaign') ?></h3>
                                 	<a href="<?= base_url('integration/integration_tools_form/text_ads') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i> <?php echo __('admin.create_new') ?></a>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<!--  -->
					<!-- <div class="col-sm-3">
						<div class="card m-b-30 text-white bg-danger">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <h3><?php echo __('admin.invisible_links') ?></h3>
                                 	<a href="<?= base_url('integration/integration_tools_form/link_ads') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i><?php echo __('admin.create_new') ?></a>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-warning">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <h3><?php echo __('admin.viral_videos') ?></h3>
                                 	<a href="<?= base_url('integration/integration_tools_form/video_ads') ?>" class="btn btn-dark waves-effect waves-light"><i class="fa fa-plus"></i> <?php echo __('admin.create_new') ?></a>
                                </blockquote>
                            </div>
                        </div>
					</div>
				</div> -->

				 <div class="row">
                                <div class="col-12">
                                    
                                    
                                        <div class="text-center">
                                <?php if ($tools ==null) {?>
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center"><?= __('admin.no_tools_found') ?></h3></div>
                                <?php }
                                else {?>
                                
                                
						<div>
							<h5 class="pull-left"><?php echo __('admin.integration_tools') ?></h5>
							<div class="ml-2 pull-left">
								<input class="table-search form-control" id="txt_name" onkeyup="myFunction()" placeholder="Search" type="search">
							</div>
						</div>
					</div>

				
						<div class="table-rep-plugin">
                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                     <table id="myTable" class="table table-striped">
								<thead>
									<tr>
										<th width="50px" class="text-center"></th>
										<th width="50px" class="text-center"><?php echo __('admin.id') ?></th>
										<th width="200px"><?php echo __('admin.name') ?></th>
										<th width="100px"><?= __('admin.tool_type') ?></th>
										<th width="200px"><?php echo __('admin.program_name') ?> / <?php echo __('admin.type') ?></th>
										
										<th  width="140px"><?= __('admin.sale_commisssion') ?></th>
										<th  width="140px"><?= __('admin.product_click') ?></th>
										<th  width="140px"><?= __('admin.general_click') ?></th>
										<th  width="140px"><?= __('admin.action_click') ?></th>

										<th width="100px"><?php echo __('admin.status') ?></th>
										<th width="180px"><?php echo __('admin.created_date') ?></th>
										<th width="100px"></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($tools as $key => $tool) { ?>
										<tr>
											<td class="text-center">
												<img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $tool['featured_image'],100,100,1) ?>" >
											</td>
											<td class="text-center"><?= $key+1 ?></td>
											<td width="100px">
												<?= $tool['name'] ?>
												<div>
													<a class="get-code" href="javascript:void(0)" data-id="<?= $tool['id'] ?>"><?php echo __('admin.get_code') ?></a>

												</div>
												<div>
													<a class="btn-show-code" href="javascript:void(0)" data-id='<?= $tool['id'] ?>'> Website Code </a>
												</div>
											</td>
											<td>
												<div><?= $tool['type'] ?></div>

												<?php 
													if($tool['recursion']){
									           			if($tool['recursion'] != 'custom_time'){
									           				echo '<b>'. __('admin.recurring') .' </b> : ' .  __('admin.'.$tool['recursion']);
									           			} else {
									           				echo '<b>'. __('admin.recurring') .' </b> : '. timetosting($tool['recursion_custom_time']);
									           			}
										           	}
												?>	
											</td>
											<td><?= $tool['program_name'] ? $tool['program_name'] .' / ' : '' ?>  <?= $tool['tool_type'] ?></td>
											
											<td>
												<div class="wallet-toggle ">
													<div class="<?= $tool['_tool_type'] == 'program' && $tool['sale_status'] ? '' : 'hide' ?>">
														<?php 
															$comm = '';
															if($tool['commission_type'] == 'percentage'){ $comm = $tool['commission_sale'].'%'; }
															else if($tool['commission_type'] == 'fixed'){ $comm = c_format($tool['commission_sale']); }

															echo "<small>You Will Get : {$comm} <br>";
															echo "Count : ". (int)$tool['total_sale_count'] ."<br>";
															echo "Amount : ". $tool['total_sale_amount'] ."</small>";
														?>
													</div>
													<a href="javascript:void(0)" class="tog"> Toggle Data </a>
												</div>
											</td>
											<td>
												<div class="wallet-toggle ">
													<div class="<?= $tool['_tool_type'] == 'program' && $tool['click_status'] ? '' : 'hide' ?>">
														<?php 
															echo "<small>You Will Get : ";
															echo c_format($tool["commission_click_commission"]). " per ". $tool['commission_number_of_click'] ." Clicks <br>";

															echo "Count : ". (int)$tool['total_click_count'] ."<br>";
															echo "Amount : ". $tool['total_click_amount'] ."</small>";
														?>
													</div>
													<a href="javascript:void(0)" class="tog"> Toggle Data </a>
												</div>
											</td>
											<td>
												<div class="wallet-toggle ">
													<div class="<?= $tool['_tool_type'] == 'general_click' ? '' : 'hide' ?>">
														<?php 
															echo "<small>You Will Get : ";
															echo c_format($tool["general_amount"]). " per ". $tool['general_click'] ." Clicks <br>";

															echo "Count : ". (int)$tool['total_general_click_count'] ."<br>";
															echo "Amount : ". $tool['total_general_click_amount'] ."</small>";
														?>
													</div>
													<a href="javascript:void(0)" class="tog"> Toggle Data </a>
												</div>
											</td>
											<td>
												<div class="wallet-toggle ">
													<div class="<?= $tool['_tool_type'] == 'action' ? '' : 'hide' ?>">
														<?php 
															echo "<small>You Will Get : ";
															echo c_format($tool["action_amount"]). " per ". $tool['action_click'] ." Actions <br>";

															echo "Count : ". (int)$tool['total_action_click_count'] ."<br>";
															echo "Amount : ". $tool['total_action_click_amount'] ."</small>";
														?>
													</div>
													<a href="javascript:void(0)" class="tog"> Toggle Data </a>
												</div>
											</td>
											<td><?= $tool['status'] ? 'Enable' : 'Disable' ?></td>
											<td><?= $tool['created_at'] ?></td>
											<td class="text-center">
												<a href="<?= base_url('integration/integration_tools_form/'. $tool['_type'] .'/' . $tool['id']) ?>" class="btn btn-sm btn-block mb-1  btn-primary"><?php echo __('admin.edit') ?></a>
												
												<a href="<?= base_url('integration/integration_tools_delete/'. $tool['id']) ?>" class="btn btn-sm btn-block mb-1  btn-danger tool-remove-link"><?php echo __('admin.delete') ?></a>

												<button class="btn-sm btn btn-primary btn-show-code" data-id='<?= $tool['id'] ?>'>Code</button>
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



<div class="modal fade" id="integration-code">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>

<div class="modal fade" id="showcode-code"></div>



<script type="text/javascript">
	$(".btn-show-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/integration_code_modal") ?>',
			type:'POST',
			dataType:'json',
			data:{
				id: $this.attr("data-id"),
			},
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(json){
				if(json['html']){
					$("#showcode-code").html(json['html']);
					$("#showcode-code").modal("show");
				}
			},
		})
	})
	function myFunction() {
	  var input, filter, table, tr, td, i, txtValue;
	  input = document.getElementById("txt_name");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("myTable");
	  tr = table.getElementsByTagName("tr");
	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[1];
	    if (td) {
	      txtValue = td.textContent || td.innerText;
	      if (txtValue.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }       
	  }
	}

	$(".wallet-toggle .tog").on('click',function(){
		$(this).parents(".wallet-toggle").find("> div").toggleClass("hide");
	})
	$(".tool-remove-link").on('click',function(){
		if(!confirm("Are you sure?")) return false;
		return true;
	})

	$(".get-code").on('click',function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url("integration/tool_get_code") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.btn("loading"); },
			complete:function(){ $this.btn("reset"); },
			success:function(json){
				if(json['html']){
					$("#integration-code .modal-content").html(json['html']);
					$("#integration-code").modal("show");
				}
			},
		})
	})
</script>