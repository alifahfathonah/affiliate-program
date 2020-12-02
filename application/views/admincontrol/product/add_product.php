<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
?>

<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/plugins/ui/jquery-ui.min.css") ?>">
	
<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data" id="form_form">
	<div class="row">
		<div class="col-12">
			<div class="card m-b-30">
				<div class="card-body">
					<input type="hidden" name="product_id" value="<?php echo $product->product_id ?>">
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
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_name') ?></label>
						<div class="col-sm-9">
							<input placeholder="<?= __('admin.enter_your_product_name') ?>" name="product_name" value="<?php echo $product->product_name; ?>" class="form-control" type="text">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_price') ?></label>
						<div class="col-sm-9">
							<input placeholder="<?= __('admin.enter_your_product_price') ?>" name="product_price" class="form-control" value="<?php echo $product->product_price; ?>" type="number">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label">Categories</label>
						<div class="col-sm-9">
							<input name="category_auto" id="category_auto" class="form-control" autocomplete="off">
							<ul class="category-selected">
								<?php if(isset($categories)){ ?>
									<?php foreach ($categories as $key => $category) { ?>
										<li>
						            		<i class="fa fa-trash remove-category"></i>
						            		<span><?= $category['name'] ?></span>
						            		<input type="hidden" name="category[]" type="" value="<?= $category['id'] ?>">
						            	</li>
									<?php } ?>
								<?php } ?>
							</ul>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_sku') ?> </label>
						<div class="col-sm-9">
							<input placeholder="<?= __('admin.enter_your_product_sku') ?>" name="product_sku" id="product_sku" class="form-control" value="<?php echo $product->product_sku; ?>" type="text">
						</div>
					</div>
					<div class="form-group row" style="display: none;">
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_video_') ?></label>
						<div class="col-sm-9">
							<input placeholder="<?= __('admin.enter_your_product_video_link{youtube/vimeo}') ?>" name="product_video" id="product_video" class="form-control" value="<?php echo $product->product_video; ?>" type="text">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.short_description') ?></label>
						<div class="col-sm-9">
							<textarea rows="3" placeholder="<?= __('admin.enter_your_product_short_description') ?>" class="form-control" name="product_short_description"  type="text"><?php echo $product->product_short_description; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label">
							<?= __('admin.product_description') ?>
						</label>
						<div class="col-sm-9">
							
							<textarea rows="10" placeholder="<?= __('admin.enter_your_product_description') ?>" class="product_description form-control summernote-img" name="product_description"  type="text"><?php echo $product->product_description; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_sale_commission') ?></label>
						<div class="col-sm-4">
							<?php
								$selected_commition_type = $product->product_commision_type;
								$selected_commision_value = $product->product_commision_value;
								$commission_type= array(
									'default'    => 'Default',
									'percentage' => 'Percentage (%)',
									'fixed'      => 'Fixed',
								);
							?>
							<select name="product_commision_type" class="form-control showonchange">
								<?php foreach ($commission_type as $key => $value) { ?>
									<option <?= $key == $selected_commition_type ? 'selected' : '' ?> value="<?= $key ?>"><?= $value ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-sm-4 toggle-divs">
							<p class="text-muted hide">
								<?php
								$commnent_line = "Default Commission ";
								if($setting['product_commission_type'] == 'percentage'){
									$commnent_line .= 'Percentage : '. $setting['product_commission'] .'%';
								}
								else if($setting['product_commission_type'] == 'Fixed'){
									$commnent_line .= 'Fixed : '. $setting['product_commission'];
								}
								echo $commnent_line;
								?>
							</p>
							<input placeholder="Enter Product Sale Commission Value " name="product_commision_value" id="product_commision_value" class="form-control" value="<?php echo $selected_commision_value; ?>" type="text">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_click_commission') ?></label>
						<div class="col-sm-4">
							<?php
								$selected_commition_type = $product->product_click_commision_type;
								$product_click_commision_ppc = $product->product_click_commision_ppc;
								$product_click_commision_per = $product->product_click_commision_per;
							?>
							<select name="product_click_commision_type" class="form-control showonchange">
								<option <?= 'default' == $selected_commition_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
								<option <?= 'custom' == $selected_commition_type ? 'selected' : '' ?> value="custom"><?= __('admin.custom') ?></option>
							</select>
						</div>
						<div class="col-sm-4 toggle-divs">
							<p class="text-muted hide">
								<?php
									 echo " PPC : " . $setting['product_noofpercommission'] . " Clicks for: " . c_format($setting['product_ppc']);
								?>
								
							</p>
							<div class="row">
								<div class="col-sm-6">
									<div data-tip="<?= __('admin.commission_amount') ?>">
										<input placeholder="<?= __('admin.commission_amount') ?>" name="product_click_commision_ppc" id="product_click_commision_ppc" class="form-control" value="<?php echo $product_click_commision_ppc; ?>" type="text">
									</div>
								</div>
								<div class="col-sm-6">
									<div data-tip="<?= __('admin.number_of_clicks_per_commission') ?>">
										<input placeholder="<?= __('admin.number_of_clicks_per_commission') ?>" name="product_click_commision_per" id="product_click_commision_value" class="form-control" value="<?php echo $product_click_commision_per; ?>" type="text">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label">Product Recursion</label>
						<div class="col-sm-4">
							<?php
								$product_recursion_type = $product->product_recursion_type;
								$product_recursion = $product->product_recursion;
							?>
							<select name="product_recursion_type" class="form-control showonchange">
								<option <?= '' == $product_recursion_type ? 'selected' : '' ?> value=""><?=  __('admin.none') ?></option>
								<option <?= 'default' == $product_recursion_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
								<option <?= 'custom' == $product_recursion_type ? 'selected' : '' ?> value="custom">Custom</option>								
							</select>							
						</div>
						<div class="col-sm-4 toggle-divs">
							<p class="text-muted hide">
								<?php
									if($setting['product_recursion'] == 'custom_time'){
										echo __('admin.default_recursion'). " : " . timetosting($setting['recursion_custom_time']). " | EndTime: " . dateFormat($setting['recursion_endtime']);
									}else{
										echo __('admin.default_recursion'). " : " . $setting['product_recursion']. " | EndTime: " . dateFormat($setting['recursion_endtime']);
									}
								?>								
							</p>
							<div class="custom_recursion <?php echo (!$product_recursion_type || $product_recursion_type != 'default') ? 'hide' : '';  ?>">
								<div class="form-group">
									<select name="product_recursion" class="form-control" id="recursion_type">
										<option value="">Select recursion</option>
										<option <?php if($product_recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
										<option <?php if($product_recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
										<option <?php if($product_recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
										<option <?php if($product_recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
										<option <?php if($product_recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
									</select>
								</div>
								<div class="form-group custom_time <?php echo ($product_recursion != 'custom_time') ? 'hide' : '';  ?>">
									<?php
										$minutes = $product->recursion_custom_time;
										$day = floor ($minutes / 1440);
										$hour = floor (($minutes - $day * 1440) / 60);
										$minute = $minutes - ($day * 1440) - ($hour * 60);
									?>
									<input type="hidden" name="recursion_custom_time" value="<?php echo $minutes; ?>">
									<div class="row">
										<div class="col-sm-4">
											<label class="control-label">Days : </label>
											<input placeholder="Days" type="number" class="form-control" value="<?= $day ? $day : '' ?>" id="recur_day" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
										</div>						
										<div class="col-sm-4">
											<label class="control-label">Hours : </label>
											<select class="form-control" id="recur_hour">
												<?php for ($x = 0; $x <= 23; $x++) {
													$selected = ($x == $hour ) ? 'selected="selected"' : '';
													echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
												} ?>
											</select>
										</div>						
										<div class="col-sm-4">
											<label class="control-label">Minutes : </label>
											<select class="form-control" id="recur_minute">
												<?php for ($x = 0; $x <= 59; $x++) {
													$selected = ($x == $minute ) ? 'selected="selected"' : '';
													echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
												} ?>
											</select>
										</div>						
									</div>									
								</div>


								<br>
								<div class="endtime-chooser row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $product->recursion_endtime ? 'checked' : '' ?>  id='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
											<div style="<?= !$product->recursion_endtime ? 'display:none' : '' ?>" class='custom_time_container'>
												<input type="text" class="form-control" value="<?= $product->recursion_endtime ? date("d-m-Y H:i",strtotime($product->recursion_endtime)) : '' ?>" name="recursion_endtime" id="endtime" placeholder="Choose EndTime" >
											</div>
										</div>
									</div>
								</div>
							</div>								
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-sm-3 col-form-label"><?= __('admin.product_featured_image') ?></label><br>
						<div class="col-sm-9">
							<div class="fileUpload btn btn-sm btn-primary">
								<span><?= __('admin.choose_file') ?></span>
								<input id="product_featured_image" name="product_featured_image" class="upload" type="file">
							</div>
							<?php $product_featured_image = $product->product_featured_image != '' ? 'assets/images/product/upload/thumb/' . $product->product_featured_image : 'assets/images/no_product_image.png' ; ?>
							<img src="<?php echo base_url($product_featured_image); ?>" id="featureImage" class="thumbnail" border="0" width="220px">
						</div>
					</div>
					
                    <div class="form-group row">
                        <label class="control-label col-sm-3" ><?= __('admin.allow_comment'); ?></label>
                        <div class="col-sm-9">                                            
                            <div class="radio">
                                <label><input type="radio" name="allow_comment" value="0" checked=""> <?= __('admin.disable'); ?></label> &nbsp;
                                <label><input type="radio" name="allow_comment" value="1" <?= $product->allow_comment ? 'checked' : '' ?> > <?= __('admin.enable'); ?></label>
                            </div>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-3" ><?= __('admin.allow_upload_file'); ?></label>
                        <div class="col-sm-9">                                            
                            <div class="radio">
                                <label><input type="radio" name="allow_upload_file" value="0" checked=""> <?= __('admin.disable'); ?></label> &nbsp;
                                <label><input type="radio" name="allow_upload_file" value="1" <?= $product->allow_upload_file ? 'checked' : '' ?> > <?= __('admin.enable'); ?></label>
                            </div>
                        </div>   
                    </div>

					<div class="form-group row">
                        <label class="control-label col-sm-3" ><?= __('admin.product_type'); ?></label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" name="product_type" value="virtual" <?= ($product->product_type == 'virtual' || $product->product_type == '') ? 'checked="checked"' : '' ?> > <?= __('admin.virtual_product'); ?>
                            </label>
                            &nbsp;
                            <label class="radio-inline">
                                <input type="radio" name="product_type" value="downloadable" <?= ($product->product_type == 'downloadable') ? 'checked="checked"' : '' ?> > <?= __('admin.downloadable_product'); ?>
                            </label>
                            <div class="form-group downloadable_file_div well" style="display: none;">
                                <div class="file-preview-button btn btn-primary">
                                    <?= __('admin.downloadable_file'); ?>
                                    <input type="file" class="downloadable_file_input" name="downloadable_files" multiple="">
                                </div>

                                <div id="priview-table" class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <?php foreach ($downloads as $key => $value) { ?>
                                                <tr>
                                                    <td width="70px"> <div class="upload-priview up-<?= $value['type'] ?>" ></div></td>
                                                    <td>
                                                        <?= $value['mask'] ?>
                                                        <input type="hidden" name="keep_files[]" value="<?= $key ?>">
                                                    </td>
                                                    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview-server" data-id="'+ i +'" >Remove</button></td>
                                                </tr>
                                            <?php } ?>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row allow_shipping-option">
                        <label class="control-label col-sm-3" ><?= __('admin.enable_shipping'); ?></label>
                        <div class="col-sm-9">                                            
                            <div class="radio">
                                <label><input type="radio" name="allow_shipping" value="0" checked=""> <?= __('admin.disable'); ?></label> &nbsp;
                                <label><input type="radio" name="allow_shipping" value="1" <?= $product->allow_shipping ? 'checked' : '' ?> > <?= __('admin.enable'); ?></label>
                            </div>
                        </div>   
                    </div>

					<div class="form-group row">
                        <label class="control-label col-sm-3" ><?= __('admin.show_on_store'); ?></label>
                        <div class="col-sm-9">                                            
                            <div class="radio">
                                <label><input type="radio" name="on_store" value="0" checked=""> <?= __('admin.no'); ?></label> &nbsp;
                                <label><input type="radio" name="on_store" value="1" <?= (int)$product->on_store ? 'checked' : '' ?> > <?= __('admin.yes'); ?></label>
                            </div>
                        </div>   
                    </div>

					<div class="text-center">
						<span class="loading-submit"></span>
						<?php if($product->product_slug){ ?>
							<?php 
								$productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product->product_slug );
							?>
							<a class="btn btn-lg btn-default btn-success" href="<?php echo $productLink ?>" target='_blank'><?= __('admin.preview') ?></a>
						<?php } ?>
						<button type="submit" class="btn btn-lg btn-default btn-submit btn-success" name="save_close"><?= __('admin.save_and_close') ?></button>
						<button type="submit" class="btn btn-lg btn-default btn-submit btn-success" name="save"><?= __('admin.save') ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>


<script type="text/javascript">
	var cache = {};

	$("#category_auto").autocomplete({
        source: function( request, response ) {
	        var term = request.term;
	        if ( term in cache ) {response( cache[ term ] );return;}
	 
	        $.getJSON( '<?= base_url('admincontrol/category_auto') ?>', request, function( data, status, xhr ) {
	          cache[ term ] = data;
	          response( data );
	        });
	    },
        minLength: 0,
        select: function (event, ui) {
            $("#category_auto").blur();
            event.preventDefault();
            if($(".category-selected input[value='"+ ui.item.value +"']").length == 0){
	            $(".category-selected").append('\
	            	<li>\
	            		<i class="fa fa-trash remove-category"></i>\
	            		<span>'+ ui.item.label +'</span>\
	            		<input type="hidden" name="category[]" type="" value="'+ ui.item.value +'">\
	            	</li>\
	        	');
            }
        },
    }).on('focus',function(){
        $(this).data("uiAutocomplete").search($(this).val());
    });

    $(".category-selected").delegate(".remove-category",'click', function(){
    	$(this).parents("li").remove();
    })

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#featureImage').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	document.getElementById("product_featured_image").onchange = function () {
		readURL(this);
	};
	
	$(".showonchange").on('change',function(){
		var val = $(this).val();
		$pare = $(this).parents('.row').eq(0);
		$pare.find(".toggle-divs p, .toggle-divs input, .toggle-divs .custom_recursion").hide();
		if(val == 'default'){
			$pare.find(".toggle-divs p").show();
		}else if(val){
			$pare.find(".toggle-divs input").show();
			$pare.find(".toggle-divs .custom_recursion").show();
		}
	})
	$(".showonchange").trigger('change');

	function readURLBanner(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#bannerImage').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".btn-submit").on('click',function(evt){
        evt.preventDefault();
        $btn = $(this);
        var formData = new FormData($("#form_form")[0]);

        $.each(fileArray, function(i,j){ formData.append("downloadable_file[]", j.rawData); });
        formData.append("action", $(this).attr("name"));
		
        formData = formDataFilter(formData);
        $this = $("#form_form");	       
        
       	$btn.btn("loading");
        $.ajax({
            url:'<?= base_url('admincontrol/editProduct') ?>',
            type:'POST',
            dataType:'json',
            cache:false,
            contentType: false,
            processData: false,
            data:formData,
            xhr: function (){
                var jqXHR = null;

                if ( window.ActiveXObject ){
                    jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                }else {
                    jqXHR = new window.XMLHttpRequest();
                }
                
                jqXHR.upload.addEventListener( "progress", function ( evt ){
                    if ( evt.lengthComputable ){
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        console.log( 'Uploaded percent', percentComplete );
                        $('.loading-submit').text(percentComplete + "% Loading");
                    }
                }, false );

                jqXHR.addEventListener( "progress", function ( evt ){
                    if ( evt.lengthComputable ){
                        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                        $('.loading-submit').text("Save");
                    }
                }, false );
                return jqXHR;
            },
            error:function(){ $btn.btn("reset"); },
            success:function(result){            	
            	$btn.btn("reset");
                $('.loading-submit').hide();
                $this.find(".has-error").removeClass("has-error");
                $this.find("span.text-danger").remove();
                
                if(result['location']){
                    window.location = result['location'];
                }
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
        });
	    
        return false;
    });

    $(document).on('change', '#recursion_type', function(){
		var recursion_type = $(this).val();		

		if( recursion_type == 'custom_time' ){
			$('.custom_time').show();
		}else{
			$('.custom_time').hide();
		}

	});

	$(document).on('change', '#recur_day, #recur_hour, #recur_minute', function(){
		var days = $('#recur_day').val();
		var hours = $('#recur_hour').val();
		var minutes = $('#recur_minute').val();
		var total_minutes;		
		
		total_hours = parseInt(days*24) + parseInt(hours);
		total_minutes = parseInt(total_hours*60) + parseInt(minutes);
		$('.custom_time').find('input[name="recursion_custom_time"]').val(total_minutes);

	});

	$('#endtime').datetimepicker({
		format:'d-m-Y H:i',
		inline:true,
	});

	$('#setCustomTime').on('change', function(){
		$(".custom_time_container").hide();
		if($(this).prop("checked")){
			$(".custom_time_container").show();
		}
	});

    $(document).on('ready',function() {
        $('input[name="product_type"]:checked').trigger('change');
        $('[name="allow_for"]').trigger("change");
        sumNote($('.summernote-img'));
    });

    var fileArray = [];
    $('.downloadable_file_input').change(function(e){
        $.each(e.target.files, function(index, value){
            var fileReader = new FileReader(); 
            fileReader.readAsDataURL(value);
            fileReader.name = value.name;
            fileReader.rawData = value;
            fileArray.push(fileReader);
        });

        render_priview();
    });

    var getFileTypeCssClass = function(filetype) {
        var fileTypeCssClass;
        fileTypeCssClass = (function() {
            switch (true) {
                case /image/.test(filetype): return 'image';
                case /video/.test(filetype): return 'video';
                case /audio/.test(filetype): return 'audio';
                case /pdf/.test(filetype): return 'pdf';
                case /csv|excel/.test(filetype): return 'spreadsheet';
                case /powerpoint/.test(filetype): return 'powerpoint';
                case /msword|text/.test(filetype): return 'document';
                case /zip/.test(filetype): return 'zip';
                case /rar/.test(filetype): return 'rar';
                default: return 'default-filetype';
            }
        })();
        return fileTypeCssClass;
    };

    function render_priview() {
        var html = '';

        $.each(fileArray, function(i,j){
            html += '<tr>';
            html += '    <td width="70px"> <div class="upload-priview up-'+ getFileTypeCssClass(j.rawData.type) +'" ></div></td>';
            html += '    <td>'+ j.name +'</td>';
            html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" data-id="'+ i +'" >Remove</button></td>';
            html += '</tr>';
        })

        $("#priview-table tbody").html(html);
    }

    $("#priview-table").delegate('.remove-priview','click', function(){
        if(!confirm("Are you sure ?")) return false;

        var index = $(this).attr("data-id");
        fileArray.splice(index,1);
        render_priview()
    })

    $(".remove-priview-server").on('click',function(){
        if(!confirm("Are you sure ?")) return false;
        $(this).parents("tr").remove();
    })

    $('input[name="product_type"]').on('change',function(){
        var val = $(this).val();
        if(val == 'downloadable'){ 
        	$('.downloadable_file_div').show(); 
        	$('.allow_shipping-option').hide(); 
        }
        else{ 
        	$('.downloadable_file_div').hide(); 
        	$('.allow_shipping-option').show(); 
        }
    });
</script>
				