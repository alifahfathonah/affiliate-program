<?php 
	$db =& get_instance(); 
	$userdetails=$db->userdetails(); 	
?>

<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<a href="">
					<div class="d-flex flex-row">
						<div class="col-3 align-self-center">
							<div class="round ">
								<i class="mdi mdi-wallet"></i>
							</div>
						</div>
						<div class="col-9 align-self-center text-center">
							<div class="m-l-10 ">
								<h6 class="mt-0 round-inner">
									<?php echo $totals['all_clicks'] ?> / 
									<?php echo c_format($totals['all_clicks_comm']) ?></h6>
								<p class="mb-0 text-muted"><?= __('admin.total_click_commition') ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<div class="d-flex flex-row">
					<div class="col-3 align-self-center">
						<div class="round">
							<i class="mdi mdi-wallet"></i>
						</div>
					</div>
					<div class="col-9 text-center align-self-center">
						<div class="m-l-10 ">
							<h6 class="mt-0 round-inner">
								<?php echo $totals['total_sale_count']; ?>/
								<?php echo c_format($totals['all_sale_comm']); ?>
							</h6>
							<p class="mb-0 text-muted"><?= __('admin.sale_ommition') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<div class="d-flex flex-row">
					<div class="col-3 align-self-center">
						<div class="round">
							<i class="mdi mdi-wallet"></i>
						</div>
					</div>
					<div class="col-9 text-center align-self-center">
						<div class="m-l-10 ">
							<h6 class="mt-0 round-inner">
								<?php echo c_format($totals['wallet_accept_amount']); ?>/
								<?php echo c_format($totals['wallet_request_sent_amount']); ?>/
								<?php echo c_format($totals['wallet_unpaid_amount']); ?>
							</h6>
							<p class="mb-0 text-muted"><?= __('admin.paid_request_unpaid') ?> </p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="col-lg-3 col-md-6">
		<div class="card m-b-30">
			<div class="card-body">
				<div class="d-flex flex-row">
					<div class="col-3 align-self-center">
						<div class="round">
							<i class="mdi mdi-wallet"></i>
						</div>
					</div>
					<div class="col-9 text-center align-self-center">
						<div class="m-l-10 ">
							<h6 class="mt-0 round-inner"><?= (int)$totals['integration']['action_count'] ?> / <?= c_format($totals['integration']['action_amount']) ?></h6>
							<p class="mb-0 text-muted">Total Action/ Amount</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<form method="GET">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">User</label>
								<select class="form-control" name="user_id">
									<option value="">Filter By User</option>
									<?php foreach ($users as $key => $value) { ?>
										<option <?= isset($user_id) && $user_id == $value['id'] ? 'selected' : '' ?> value="<?= $value['id'] ?>"><?= $value['name'] ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Date</label>
								<input autocomplete="off" type="text" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" class="form-control daterange-picker">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Status</label>
								<select name="status" class="form-control">
									<option value="">All</option>
									<option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>>On Hold</option>
									<option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>In Wallet</option>
									<option value="2" <?= isset($_GET['status']) && $_GET['status'] == '2' ? 'selected' : '' ?>>Request Send</option>
									<option value="3" <?= isset($_GET['status']) && $_GET['status'] == '3' ? 'selected' : '' ?>>Accept</option>
									<option value="4" <?= isset($_GET['status']) && $_GET['status'] == '4' ? 'selected' : '' ?>>Reject</option>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label"><?= __('admin.recurring_transaction') ?></label>
								<select name="recurring" class="form-control">
									<option value=""><?= __('admin.all') ?></option>
									<option value="0" <?= isset($_GET['status']) && $_GET['recurring'] == '0' ? 'selected' : '' ?>><?= __('admin.not_recurring') ?></option>
									<option value="1" <?= isset($_GET['status']) && $_GET['recurring'] == '1' ? 'selected' : '' ?>><?= __('admin.recurring') ?></option>
								</select>
							</div>
						</div>

						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Type</label>
								<select name="type" class="form-control">
									<option value="">All</option>
									<option value="actions" <?= isset($_GET['type']) && $_GET['type'] == 'actions' ? 'selected' : '' ?>>Actions</option>
									<option value="clicks" <?= isset($_GET['type']) && $_GET['type'] == 'clicks' ? 'selected' : '' ?>>Clicks</option>
									<option value="sale" <?= isset($_GET['type']) && $_GET['type'] == 'sale' ? 'selected' : '' ?>>Sale</option>
									<option value="external_integration" <?= isset($_GET['type']) && $_GET['type'] == 'external_integration' ? 'selected' : '' ?>>External Integration</option>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label d-block">&nbsp;</label>
								<button class="btn btn-primary">Filter</button>
								<button class="btn btn-danger delete-multiple" type="button">Delete Selected <span class="selected-count"></span></button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body p-0">	
			
			  <div class="text-center">
                <?php if ($transaction ==null) {?>
                	<img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                 	<h3 class="m-t-40 text-center"><?= __('admin.no_transactions') ?></h3>
                <?php }
                else { ?>
					<div class="table-responsive">
						<table class="table table-striped table-sortable wallet-table">
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" class="selectall-wallet-checkbox"></th>
									<th class="sortTr text-left <?= sort_order('users.username') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','users.username') ?>"><?= __('admin.username') ?></a></th>
									<th>Order Total</th>
									<th class="sortTr <?= sort_order('wallet.amount') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','wallet.amount') ?>"><?= __('admin.commission') ?></a></th>
									<th scope="col"><?= __('admin.payment_method') ?></th>
									<!--<th scope="col"><?= __('admin.comment') ?></th>-->
									<th class="sortTr <?= sort_order('wallet.created_at') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','wallet.created_at') ?>"><?= __('admin.date') ?></a></th>
									<th class="sortTr <?= sort_order('wallet.comm_from') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','wallet.comm_from') ?>"><?= __('admin.comm_from') ?></a></th>
									<th class="sortTr <?= sort_order('wallet.type') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','wallet.type') ?>"><?= __('admin.type') ?></a></th>
									<th class="sortTr <?= sort_order('wallet.status') ?>" scope="col"><a href="<?= sortable_link('admincontrol/mywallet','wallet.status') ?>"><?= __('admin.status') ?></a></th>
									<th scope="col"><?= __('admin.action_trans') ?></th>
								</tr>
							</thead>
							<tbody>
								<?= $table ?>
							</tbody>
						</table>
					</div>
				<?php if(isset($pagination_link)) { ?>
					<div class="pagination_div">
						<?php echo $pagination_link; ?>
					</div>
				<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-confirm">
	<div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div>
</div>
<div class="modal fade" id="modal-confirmstatus">
	<div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div>
</div>

<div class="modal fade" id="modal-recursion">
	<div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div>
</div>


<script src="<?= base_url('assets/plugins/datatable') ?>/moment.js"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/plugins/datatable') ?>/daterangepicker.css" />

<script type="text/javascript">
	$('.selectall-wallet-checkbox').on('change',function(){
		$(".wallet-checkbox").prop("checked", $(this).prop("checked")).trigger("change");
	});

	$(".wallet-checkbox").on('change',function(){
		if($(".wallet-checkbox:checked").length == 0){
			$(".delete-multiple").hide();
		} else {
			$(".delete-multiple").show();
			$(".selected-count").text($(".wallet-checkbox:checked").length);
		}
	})

	$(".delete-multiple").on('click',function(){
		var ids = $(".wallet-checkbox:checked").map(function(){ return $(this).val() }).toArray().join(",");
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/info_remove_tran_multiple") ?>',
			type:'POST',
			dataType:'json',
			data:{ids:ids},
			beforeSend:function(){ $this.button("loading"); },
			complete:function(){ $this.button("reset"); },
			success:function(json){
				$("#modal-confirm .modal-body").html(json['html']);
				$("#modal-confirm").modal("show");
			},
		})
	})

	$("#modal-confirm .modal-body").delegate("[delete-mmultiple-confirm]","click",function(){
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/confirm_remove_tran_multi") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("delete-mmultiple-confirm")},
			beforeSend:function(){ $this.button("loading"); },
			complete:function(){ $this.button("reset"); },
			success:function(json){
				window.location.reload();
			},
		})
	})

	$('.daterange-picker').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            cancelLabel: 'Clear',
            format: 'DD-M-YYYY'
        }
    });

	$('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-M-YYYY') + ' - ' + picker.endDate.format('DD-M-YYYY'));
    });

    $('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

	$(document).delegate(".status-change-rdo",'change',function(){
		$this = $(this);
		var id = $this.attr("data-id");
		var val = $this.val();
		$loading = $this.parents(".wallet-status-switch").find(".loading");
		
		$.ajax({
			url: '<?php echo base_url("admincontrol/wallet_change_status") ?>',
			type:'POST',
			dataType:'json',
			data:{id:id,val:val},
			beforeSend:function(){$loading.show();},
			complete:function(){$loading.hide();},
			success:function(json){
				if(json['ask_confirm']){
					$("#modal-confirmstatus .modal-body").html(json['html']);
					$("#modal-confirmstatus").modal({
					    backdrop: 'static',
					    keyboard: false
					});
				} 
				if(json['success']){
					window.location.reload();
				}
			},
		})
	});

	$("#modal-confirmstatus").delegate(".close-modal","click",function(){
		var id = $(this).attr("data-id");
		$(".status-change-rdo[name=status_"+ id +"]:not(:checked)").prop("checked",1)
		$("#modal-confirmstatus").modal("hide");
	})

	$(document).delegate(".remove-tran",'click',function(){
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/info_remove_tran") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.button("loading"); },
			complete:function(){ $this.button("reset"); },
			success:function(json){
				$("#modal-confirm .modal-body").html(json['html']);
				$("#modal-confirm").modal("show");
			},
		})
	});	

	$("#modal-confirm .modal-body").delegate("[delete-tran-confirm]","click",function(){
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/confirm_remove_tran") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("delete-tran-confirm")},
			beforeSend:function(){ $this.button("loading"); },
			complete:function(){ $this.button("reset"); },
			success:function(json){
				window.location.reload();
			},
		})
	});


	$(".recursion-tran").on('click',function(){
		$this = $(this);
		$.ajax({
			url: '<?php echo base_url("admincontrol/info_recursion_tran") ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.attr("data-id")},
			beforeSend:function(){ $this.button("loading"); },
			complete:function(){ $this.button("reset"); },
			success:function(json){
				$("#modal-recursion .modal-body").html(json['html']);
				$("#modal-recursion").modal("show");

				if( json['recursion_type'] == 'custom_time' ){
					$('.custom_time').show();
				}else{
					$('.custom_time').hide();
				}
			},
		})
	});

	


	$('[name="user_id"]').select2();

	$(".show-recurring-transition").on("click",function(){
		$this = $(this);
		var id = $this.attr("data-id");

		$this.find("i").toggleClass("mdi-plus mdi-minus")

		$nextAll = $this.parents("tr").nextAll("tr.recurringof-"+id);
		if($nextAll.length){
			if($nextAll.eq(0).css("display") == 'table-row'){
				$nextAll.hide();
			} else {
				$nextAll.show();
			}
			return false;
		}

		$this.parents("tr").nextAll("tr.recurringof-"+id).remove();

		$.ajax({
			url:'<?= base_url('admincontrol/getRecurringTransaction') ?>',
			type:'POST',
			dataType:'json',
			data:{
				id:id,
			},
			beforeSend:function(){
				$this.btn("loading");
			},
			complete:function(){
				$this.btn("reset");
			},
			success:function(json){
				if(json['table']){
					$this.parents("tr").after(json['table'])
				}
			},
		})
	})

</script>