	<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<div class="card m-b-30">
						<div class="card-body">
							<?php if($this->session->flashdata('success')){?>
								<div class="alert alert-success alert-dismissable my_alert_css">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('success'); ?> </div>
							<?php } ?>
							
							<?php if($this->session->flashdata('error')){?>
								<div class="alert alert-danger alert-dismissable my_alert_css">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('error'); ?> </div>
							<?php } ?>
							
							<div class="form-group row">
								
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_video') ?> </label>
								<div class="col-sm-9">
									<input placeholder="Enter your Product Video Link(Youtube/Vimeo URL}" name="product_media_upload_path" id="product_media_upload_path" class="form-control" value="" type="text">
									<?= __('admin.example_youtube_link') ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_video_image') ?></label><br>
								<div class="col-sm-9">
									<div class="fileUpload btn btn-sm btn-primary">
										<span><?= __('admin.choose_file') ?></span>
										<input id="video_thumbnail_image" name="video_thumbnail_image" class="upload" type="file">
									</div>
									<?php $video_thumbnail_image = 'no-image.jpg' ; ?>
									<img src="<?php echo base_url();?>assets/images/thumbs/<?php echo $video_thumbnail_image; ?>" id="multipleimage" class="thumbnail"  border="0" width="220px">
								</div>
							</div>
							<button class="btn btn-block btn-default btn-success" id="update-product" type="submit"><i class="fa fa-save"></i> <?= __('admin.submit') ?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-body">
						<h4 class="mt-0 header-title"><?= __('admin.all_videos_images') ?></h4>
						<?php foreach($videoimageslist as $images){ ?>
							<div class="popup-gallery">
								<a class="pull-left" href="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $images['product_media_upload_video_image'];?>">
									<div class="img-responsive">
										<img width="200px" height="200px" src="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $images['product_media_upload_video_image'];?>" ><br>
									</div>
								</a>
                                <span class="delete_item" onclick="delete_image(<?php echo $images['product_media_upload_id'];?>);" >&times;</span>
							</div>
						<?php } ?>
					</div>
				</div> <!-- end col -->
			</div> <!-- end row -->
		</div>


<script type="text/javascript">
	function readURL(input) {
		
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function(e) {
				$('#multipleimage').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	}
    function delete_image(id){
        $.confirm({
            title: '<?= __('admin.delete_image') ?>',
            content: '<?= __('admin.do_you_want_to_delete_this_image') ?>',
            buttons: {
                confirm: function () {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>admincontrol/delete_image",
                        data:'image_id='+id,
                        success: function(){
                            location.reload();
                        }
                    });
                },
                cancel: function () {
                    $.alert('Canceled!');
                }
            }
        });
    }
	document.getElementById("video_thumbnail_image").onchange = function () {
		readURL(this);
		document.getElementById("uploadFileFeature").value = this.value;
	};
</script>