<?php

	$db =& get_instance();

	$userdetails=$db->userdetails();



	$allows = array(

		'user'   => [1 => 1, 2 => 2, 3=>3 , 4=>4, 5=>5 ,7=>7, 8=>8, 9=>9, 10=>10, 11=>11,12=>12,13=>13],

		'admin'  => [1 => 1, 2 => 2, 3=>3 , 4=>4, 5=>5 , 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 11=>11, 13=>13],

		'client' => [2 => 2, 3=>3 , 6=>6, 7=>7, 8=>8, 9=>9],

	);

?>



	

		<div class="row">

			<div class="col-12">

			    	<div>

						<h4 class="page-title pull-left"><?= __('admin.mail_editor') ?> (<small><?php echo $templates['name'] ?></small>) </h4>

						<a href="<?= base_url('admincontrol/mails') ?>" class="btn pull-right btn-sm btn-outline-primary"><?= __('admin.back') ?></a>

						<div class="clearfix"></div>

					</div>

				<div class="card m-b-30">

					<div class="card-body">

						<form action="" method="POST" role="form" id="mail_template_form">

							<input type="hidden" name="id" value="<?php echo $templates['id'] ?>">

							

							<div role="tabpanel">

								<!-- Nav tabs -->

								<ul class="nav nav-pills" role="tablist" id="myTab">

									<?php if($allows['user'][$templates['id']]){ ?><li role="presentation" class=" nav-item">

										<a href="#for-user" class="active nav-link" aria-controls="for-user" role="tab" data-toggle="tab"><?= __('admin.user') ?></a>

									</li><?php } ?>

									<?php if($allows['admin'][$templates['id']]){ ?><li role="presentation">

										<a href="#for-admin" class="nav-link" aria-controls="for-admin" role="tab" data-toggle="tab"><?= __('admin.admin') ?></a>

									</li><?php } ?>

									<?php if($allows['client'][$templates['id']]){ ?><li role="presentation">

										<a href="#for-client" class="nav-link" aria-controls="for-admin" role="tab" data-toggle="tab"><?= __('admin.client') ?></a>

									</li><?php } ?>

								</ul>

							

								<hr>

								<div class="tab-content">

									<?php if($allows['user'][$templates['id']]){ ?>

										<div role="tabpanel" class="tab-pane active" id="for-user">

											<div class="form-group">

												<label for=""><?= __('admin.subject') ?></label>

												<input type="text" class="form-control" name="subject" value="<?php echo $templates['subject'] ?>">

											</div>

											<div class="form-group">

												<label for=""><?= __('admin.subject') ?></label>

												<textarea id="editor1" type="text" class="form-control summernote" name="text" ><?php echo $templates['text'] ?></textarea>

											</div>

										</div>

									<?php } ?>

									<?php if($allows['admin'][$templates['id']]){ ?>

										<div role="tabpanel" class="tab-pane" id="for-admin">

											<div class="form-group">

												<label for=""><?= __('admin.subject') ?></label>

												<input type="text" class="form-control" name="admin_subject" value="<?php echo $templates['admin_subject'] ?>">

											</div>

											<div class="form-group">

												<label for=""><?= __('admin.subject') ?></label>

												<textarea id="editor2" type="text" class="form-control summernote" name="admin_text" ><?php echo $templates['admin_text'] ?></textarea>

											</div>

										</div>

									<?php } ?>

									<?php if($allows['client'][$templates['id']]){ ?>

										<div role="tabpanel" class="tab-pane" id="for-client">

											<div class="form-group">

												<label for=""><?= __('admin.subject') ?></label>

												<input type="text" class="form-control" name="client_subject" value="<?php echo $templates['client_subject'] ?>">

											</div>

											<div class="form-group">

												<label for=""><?= __('admin.subject') ?></label>

												<textarea id="editor3" type="text" class="form-control summernote" name="client_text" ><?php echo $templates['client_text'] ?></textarea>

											</div>

										</div>

									<?php } ?>

								</div>

							</div>

							<div>

								<?php 

									$shortcode= explode(",",($templates['shortcode']));

									foreach ($shortcode as $key => $value) {

										echo '<span class="badge badge-secondary font-16 m-1">[['. $value .']]</span>';

									}

								?>

							</div>

							

							<br>

							<div class="row">

								<div class="col-sm-2">

									<button type="submit" class="btn btn-primary"><?= __('admin.submit') ?></button>

								</div>

								<div class="col-sm-10">

									<div class="input-group">

							            <input type="text" class="form-control" name="test_email" class="test_email">

							            <button class="btn btn-primary send-test"> <?= __('admin.send_test') ?></button>

							        </div>

								</div>

							</div>

							

						</form>

					</div>

				</div> 

			</div> 

		</div>

<script src="<?php echo base_url('assets/plugins/store/ckeditor/ckeditor.js'); ?>"></script>

<script type="text/javascript">

	$(document).on('ready',function() {

		$('#myTab li:first a').tab('show');



		CKEDITOR.config.language = 'en';

		CKEDITOR.config.extraPlugins = 'templates';

		if($("#editor1").length) CKEDITOR.replace( 'editor1' );

		if($("#editor2").length) CKEDITOR.replace( 'editor2' );

		if($("#editor3").length) CKEDITOR.replace( 'editor3' );

		

		$(".send-test").on('click',function(){

			$this = $(this);

			if($("#editor1").length) $("#editor1").val(CKEDITOR.instances.editor1.getData());

			if($("#editor2").length) $("#editor2").val(CKEDITOR.instances.editor2.getData());

			if($("#editor3").length) $("#editor3").val(CKEDITOR.instances.editor3.getData());

			$.ajax({

				type:'POST',

				dataType:'json',

				data: $("#mail_template_form").serialize() + "&send_test=true&test_for=" + $('.tab-pane.active').attr("id"),

				beforeSend:function(){ $this.button("loading"); },

				complete:function(){ $this.button("reset"); },

				success:function(json){

					$(".alert-mail").remove();

					if(json['error']){

						$this.parents(".input-group").after('<div class="alert alert-mail alert-danger">'+ json['error'] +'</div>');

					}

					if(json['success']){

						$this.parents(".input-group").after('<div class="alert alert-mail alert-success">'+ json['success'] +'</div>');

					}

				},

			})

		})

	});

</script>