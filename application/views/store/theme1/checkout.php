<div class="container">
    <div class="row">
        <div class="col-sm-12">
			<br><h1><?= __('store.checkout') ?></h1><br><br>
			<?php if(!$is_logged){ ?>
			
				<div class="checkout-setp auth-step">
					<div class="step-head">
						<h4><?= __('store.personal_details') ?></h4>
					</div>
					<div class="step-body">
						<div class="row">
							<div class="col-sm-6">
								<h5 class="sub-title"><?= __('store.login_with_existing_account') ?></h5>
								<form id="login-form">
									<div class="form-group">
										<label class="control-label"><?= __('store.username') ?></label>
										<input class="form-control" name="username" type="text">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.password') ?></label>
										<input class="form-control" name="password" type="password">
									</div>
									<div class="form-group text-right">
										<button class="btn btn-primary btn-submit"><?= __('store.login') ?></button>
									</div>
								</form>
							</div>
							<div class="col-sm-6">
								<h5 class="sub-title"><?= __('store.create_a_new_account') ?></h5>
								<form id="register-form">
									<div class="form-group">
										<label class="control-label"><?= __('store.first_name') ?></label>
										<input class="form-control" name="f_name" type="text">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.last_name') ?></label>
										<input class="form-control" name="l_name" type="text">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.username') ?></label>
										<input class="form-control" name="username" type="text">
									</div>
									
									<div class="form-group">
										<label class="control-label"><?= __('store.email') ?></label>
										<input class="form-control" name="email" type="email">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.password') ?></label>
										<input class="form-control" name="password" type="password">
									</div>
									<div class="form-group">
										<label class="control-label"><?= __('store.confirm_password') ?></label>
										<input class="form-control" name="c_password" type="password">
									</div>
									<div class="form-group text-right">
										<button class="btn btn-primary btn-submit"><?= __('store.register') ?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="step-footer"></div>
				</div>
				<?php } ?>
				<div class="checkout-setp cart-step">
					<div class="step-head">
						<h4> <?= __('store.purchase_of_details') ?></h4>
					</div>
					<div class="step-body">
						<div class="cart-loader"></div>
						<div class="cart-body"></div>
					</div>
					<div class="step-footer"></div>
				</div>
				<div class="non-confirm">
					<?php if($allow_shipping){ ?>
					<div class="checkout-setp shipping-step">
						<div class="step-head">
							<h4><?= __('store.shipping_details') ?></h4>
						</div>
						<div class="step-body">
							<div class="cart-loader"></div>
							<div class="cart-body"></div>
						</div>
						<div class="step-footer"></div>
					</div>
					<?php } ?>
					<div class="checkout-setp">
						<div class="step-head">
							<h4><?= __('store.payment_methods') ?></h4>
						</div>
						<div class="step-body">
							<div class="dynamic-payment"></div>
							
							<br>
							<?php if($allow_upload_file){ ?>
								<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.uploadPreviewer.css') ?>">
								<div class="form-group downloadable_file_div well" style="white-space: inherit;">
									<div class="file-preview-button btn btn-primary">
							            <?= __('store.order_upload_file') ?>
							            <input type="file" class="downloadable_file_input" multiple="">
							        </div>

							        <div id="priview-table" class="table-responsive" style="display: none;">
							            <table class="table table-hover">
							                <tbody></tbody>
							            </table>
							        </div>
							    </div>
							<?php } ?>

							<div class="checkbox">
								<label>
									<input type="checkbox" value="1" name="agree">
									<?= __('store.agree_text') ?>
								</label>
							</div>
							<br><div class="warning-div"></div><br>
							<br>
						</div>
						<div class="step-footer">
							<button class="btn btn-info confirm-order"><?= __('store.confirm_and_pay') ?></button>
						</div>
					</div>
				</div>

				<!-- <div class="checkout-setp">
					<div class="step-head">
						<h4><?= __('store.confirm_order') ?></h4>
					</div>
					<div class="step-body">
						<div class="payment-method-step">
							<br><div class="warning-div"></div><br>
							<div class="text-right">
								<span class="loading-submit"></span>
								<button class="btn btn-info confirm-order"><?= __('store.confirm_and_pay') ?></button>
							</div>
						</div>
					</div>
					<div class="step-footer"></div>
				</div> -->

				<div class="confirm-checkout">
					<div class="checkout-setp confirm-step">
						<div class="step-head">
							<h4><?= __('store.confirm_order') ?></h4>
						</div>
						<div class="step-body">
							<div class="">
								<div id="checkout-confirm"></div>
								
							</div>
						</div>
						<div class="step-footer"></div>
					</div>
				</div>
			
        </div>
    </div>
</div>
<script type="text/javascript">
	$('[name="payment_method"]').on('change',function(){
		if($(this).val() == 'bank_transfer'){
			$('.bank-transfer-instruction').slideDown();
		}else{
			$('.bank-transfer-instruction').slideUp();
		}
	});
	$(".cart-step").delegate(".btn-remove-cart","click",function(){
		$this = $(this);
		$.ajax({
			url:$this.attr("data-href"),
			type:'POST',
			dataType:'json',
			beforeSend:function(){},
			complete:function(){},
			success:function(json){
				getCart();				
			},
		})
		return false;
	});
	
	var xhr;
	$(".cart-step").delegate(".qty-input","change",function(){
		if(xhr && xhr.readyState != 4) xhr.abort();

		$this = $(this);
		xhr = $.ajax({
			url:'<?= $cart_update_url ?>',
			type:'POST',
			dataType:'json',
			data:$("#checkout-cart-form").serialize(),
			beforeSend:function(){},
			complete:function(){},
			success:function(json){
				getCart();				
			},
		})
		return false;
	})
	function getCart() {
		$(".cart-step .cart-body").load('<?= base_url('store/checkout-cart') ?>');
	}
	function getShipping() {
		$(".shipping-step .cart-body").load('<?= base_url('store/checkout_shipping') ?>');
	}
	/*function getConfirm() {
		$("#checkout-confirm").load('<?= base_url('store/checkout_confirm') ?>');
	}*/

	function getPaymentMethods(){
		$.ajax({
			url:'<?= base_url('store/get_payment_mothods') ?>',
			type:'POST',
			dataType:'json',
			data:{
				data:$("#checkout-cart-form").serialize(),
			},
			beforeSend:function(){},
			complete:function(){},
			success:function(json){
				$(".dynamic-payment").html(json['html']);
			},
		})
	}
	getCart();getShipping();getPaymentMethods();
	//getConfirm();
	$('.shipping-step').delegate('[name="country"]',"change",function(){
		$this = $(this);
		$.ajax({
			url:'<?= base_url('store/getState') ?>',
			type:'POST',
			dataType:'json',
			data:{id:$this.val()},
			beforeSend:function(){$('[name="state"]').prop("disabled",true);},
			complete:function(){$('[name="state"]').prop("disabled",false);},
			success:function(json){
				var html = '<option value="">Select State</option>';
				$.each(json['states'], function(i,j){
					var s = '';
					if(selected_state && selected_state == j['id']){
						s = 'selected';selected_state = 0;
					}
					html += "<option "+ s +" value='"+ j['id'] +"'>"+ j['name'] +"</option>";
				})
				$('[name="state"]').html(html);
			},
		})
	})
	
	$(".confirm-order").on('click',function(){
		$this = $(this);
		$container = $(".checkout-setp");		 
		var formData = new FormData();

		$container.find("input[type=text],input[type=file],select,input[type=checkbox]:checked,input[type=radio]:checked,textarea").each(function(i,j){
			formData.append($(j).attr("name"),$(j).val());
		})
		if(typeof fileArray != 'undefined'){
			$.each(fileArray, function(i,j){ formData.append("downloadable_file[]", j.rawData); });
		}
		
		formData = formDataFilter(formData);

		$.ajax({
			url:'<?= $base_url ?>confirm_order',
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
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(result){
				$container.find(".has-error").removeClass("has-error");
				$container.find("span.text-danger,.alert-danger").remove();
				$('.loading-submit').hide();
				
				/*if(result['success']){}
				if(result['location']){
					window.location = result['location']
				}*/
				if(result['confirm']){
					$("#checkout-confirm").html(result['confirm']);
					$(".confirm-checkout").show();
					$(".non-confirm").hide();
				}
				if(result['error']){
					$(".warning-div").html('<div class="alert alert-danger">'+ result['error'] +'</div>');
				}
				if(result['errors']){
				    $.each(result['errors'], function(i,j){
				        $ele = $container.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
	});

	function backCheckout(){
		$("#checkout-confirm").html('');
		$(".confirm-checkout").hide();
		$(".non-confirm").show();
	}
	$("#login-form").on('submit',function(){
		$this = $(this);
		$.ajax({
			url:'<?= $base_url ?>ajax_login',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			beforeSend:function(){$this.find(".btn-submit").btn("loading");},
			complete:function(){$this.find(".btn-submit").btn("reset");},
			success:function(result){
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();
				
				if(result['success']){
					location = '<?= $checkout_url ?>';
				}
				if(result['errors']){
				
				    $.each(result['errors'], function(i,j){
				        $ele = $this.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
		return false;
	})
	$("#register-form").on('submit',function(){
		$this = $(this);
		$.ajax({
			url:'<?= $base_url ?>ajax_register',
			type:'POST',
			dataType:'json',
			data:$this.serialize(),
			beforeSend:function(){$this.find(".btn-submit").btn("loading");},
			complete:function(){$this.find(".btn-submit").btn("reset");},
			success:function(result){
				$this.find(".has-error").removeClass("has-error");
				$this.find("span.text-danger").remove();
				if(result['success']){
					location = '<?= $checkout_url ?>';
				}
				
				if(result['errors']){
				
				    $.each(result['errors'], function(i,j){
				        $ele = $this.find('[name="'+ i +'"]');
				        if($ele){
				            $ele.parents(".form-group").addClass("has-error");
				            $ele.after("<span class='text-danger'>"+ j +"</span>");
				        }
				    })
				}
			},
		})
		return false;
	})
</script>

<script type="text/javascript">
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
            html += '    <td width="70px"><button type="button" class="btn btn-danger btn-sm remove-priview" onClick="removeTr(this)" data-id="'+ i +'" >Remove</button></td>';
            html += '</tr>';
        })

        $("#priview-table tbody").html(html);
        if(html) {
        	$("#priview-table").show();
        } else {
        	$("#priview-table").hide();
        }
    }

    function removeTr(t){
        if(!confirm("Are you sure ?")) return false;

        var index = $(t).attr("data-id");
        fileArray.splice(index,1);
        render_priview()
    }
</script>