<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<div>
							<h5 class="pull-left"><?= __('admin.integration_programs') ?></h5>
							<div class="pull-right">
								<a class="btn btn-primary btn-sm" href="<?= base_url('integration/programs') ?>"><?= __('admin.back') ?></a>
							</div>
						</div>
					</div>

					<div class="card-body">
						<form action="" method="get" id="form_program">
							<input name="program_id" type="hidden" value="<?= isset($programs) ? $programs['id'] : '0' ?>">
							<div class="form-group">
								<label class="control-label"><?= __('admin.program_name') ?></label>
								<input class="form-control" name="name" type="text" value="<?= isset($programs) ? $programs['name'] : '' ?>">
							</div>

							<div class="row">
								<div class="col-sm-6">
									<div class="custom-card card">
										<div class="card-header"><p class="text-center"><?= __('admin.sale_settings') ?></p></div>

										<div class="card-body">
											<div class="form-group">
												<label class="control-label"><?= __('admin.commission_type') ?></label>
												<select name="commission_type" class="form-control">
													<option value=""><?= __('admin.select_product_commission_type') ?></option>
													<option <?= (isset($programs) && $programs['commission_type'] == 'percentage') ? 'selected' : '' ?> value="percentage"><?= __('admin.percentage') ?></option>
													<option <?= (isset($programs) && $programs['commission_type'] == 'fixed') ? 'selected' : '' ?> value="fixed"><?= __('admin.fixed') ?></option>
												</select>
											</div>

											<div class="form-group">
												<label class="control-label"><?= __('admin.commission_for_sale') ?> </label>
												<input class="form-control" name="commission_sale" type="number" value="<?= isset($programs) ? $programs['commission_sale'] : '' ?>">
											</div>

											<div class="form-group">
												<label class="control-label"><?= __('admin.sale_status') ?></label>
												<div>
													<div class="radio radio-inline"> <label> <input type="radio" checked="" name="sale_status" value="0"> <?= __('admin.disable') ?> </label> </div>
													<div class="radio radio-inline"> <label> <input <?= (isset($programs) && $programs['sale_status']) ? 'checked' : '' ?> type="radio" name="sale_status" value="1"> <?= __('admin.enable') ?> </label> </div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="custom-card card">
										<div class="card-header"><p class="text-center"><?= __('admin.click_settings') ?></p></div>

										<div class="card-body">
											<div class="form-group">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label"><?= __('admin.number_of_click') ?></label>
															<input class="form-control" name="commission_number_of_click" type="number" value="<?= isset($programs) ? $programs['commission_number_of_click'] : '' ?>">
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label"><?= __('admin.amount_per_click') ?></label>
															<input class="form-control" name="commission_click_commission" type="number" value="<?= isset($programs) ? $programs['commission_click_commission'] : '' ?>">
														</div>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label class="control-label"><?= __('admin.click_status') ?></label>
												<div>
													<div class="radio radio-inline"> <label> <input type="radio" checked="" name="click_status" value="0"> <?= __('admin.disable') ?> </label> </div>
													<div class="radio radio-inline"> <label> <input type="radio" <?= (isset($programs) && $programs['click_status']) ? 'checked' : '' ?> name="click_status" value="1"> <?= __('admin.enable') ?> </label> </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</form>	
					</div>

					<div class="card-footer text-right">
						<button class="btn btn-primary btn-save"><?= __('admin.save') ?></button>
					</div>
				</div>
			</div>
		</div>


<script type="text/javascript">
	$(".btn-save").on('click',function(){
	 	$this = $("#form_program");
	 	
		$.ajax({
            url:'<?= base_url('integration/editProgram') ?>',
            type:'POST',
            dataType:'json',
            data:$this.serialize(),
            success:function(result){
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){ window.location = result['location']; }

                if(result['errors']){
                    $.each(result['errors'], function(i,j){
                        $ele = $this.find('[name="'+ i +'"]');
                        if($ele){
                            $ele.parents(".form-group").addClass("has-error");
                            $ele.after("<span class='text-danger'>"+ j +"</span>");
                        }
                    });
                }
            },
        })
	})
</script>