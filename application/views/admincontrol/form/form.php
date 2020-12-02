<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
      
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-header translation-header">
                        <h4 class="card-title pull-left main-h4 "><?= __('admin.form') ?></h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="table-rep-plugin">
                            <div class="table-responsive-b-0">
                                <form id="form_form">
                                    <input type="hidden" class="form-control" name="id" value="<?= (int)$form['form_id'] ?>">
                                    <input type="hidden" class="form-control redirect" name="redirect" value="">
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.title'); ?></label>
                                        <input type="text" class="form-control" name="title" value="<?= $form['title'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.seo_title'); ?></label>
                                        <input type="text" class="form-control" name="seo" value="<?= $form['seo'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.body_content'); ?></label>
                                        <textarea rows="3" placeholder="" class="form-control body_content summernote-img" name="description"  type="text"><?= $form['description'] ?></textarea>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?= __('admin.form_sale_commission') ?></label>
                                        <div class="col-sm-4">
                                            <?php
                                                $selected_commition_type = $form['sale_commision_type'];
                                                $selected_commision_value = $form['sale_commision_value'];
                                                $commission_type= array(
                                                    'default'    => 'Default',
                                                    'percentage' => 'Percentage (%)',
                                                    'fixed'      => 'Fixed',
                                                );
                                            ?>
                                            <select name="form_commision_type" class="form-control showonchange">
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
                                                }else if($setting['product_commission_type'] == 'Fixed'){
                                                    $commnent_line .= 'Fixed : '. $setting['product_commission'];
                                                }
                                                echo $commnent_line; ?>
                                            </p>
                                            <input placeholder="Enter form Sale Commission Value " name="form_commision_value" id="form_commision_value" class="form-control" value="<?php echo $selected_commision_value; ?>" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?= __('admin.form_click_commission') ?></label>
                                        <div class="col-sm-4">
                                            <?php
                                                $selected_commition_type = $form['click_commision_type'];
                                                $form_click_commision_ppc = $form['click_commision_ppc'];
                                                $form_click_commision_per = $form['click_commision_per'];
                                            ?>
                                            <select name="form_click_commision_type" class="form-control showonchange">
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
                                                    <input placeholder="PPC/Visits/View" name="form_click_commision_ppc" id="form_click_commision_ppc" class="form-control" value="<?php echo $form_click_commision_ppc; ?>" type="text">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input placeholder="Number of clicks per commission" name="form_click_commision_per" id="form_click_commision_value" class="form-control" value="<?php echo $form_click_commision_per; ?>" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="example-text-input" class="col-sm-3 col-form-label">Form Recursion</label>
                                        <div class="col-sm-4">
                                            <?php                                          

                                                $form_recursion_type = $form['form_recursion_type'];
                                                $form_recursion = $form['form_recursion'];
                                            ?>
                                            <select name="form_recursion_type" class="form-control showonchange">
                                                <option <?= '' == $form_recursion_type ? 'selected' : '' ?> value="">-- None --</option>
                                                <option <?= 'default' == $form_recursion_type ? 'selected' : '' ?> value="default"><?= __('admin.default') ?></option>
                                                <option <?= 'custom' == $form_recursion_type ? 'selected' : '' ?> value="custom">Custom</option>                             
                                            </select>                           
                                        </div>
                                        <div class="col-sm-4 toggle-divs">
                                            <p class="text-muted hide">                                            
                                                <?php
                                                if($setting['form_recursion'] == 'custom_time'){
                                                    echo __('admin.default_recursion')." : " . timetosting($setting['recursion_custom_time']). " | EndTime: " . dateFormat($setting['recursion_endtime']);
                                                }else{
                                                    echo __('admin.default_recursion')." : " . $setting['product_recursion']. " | EndTime: " . dateFormat($setting['recursion_endtime']);
                                                }
                                                
                                                ?>                              
                                            </p>
                                            <div class="custom_recursion <?php echo ($form_recursion_type != 'default') ? 'hide' : '';  ?>">
                                                <div class="form-group">
                                                    <select name="form_recursion" class="form-control" id="recursion_type">
                                                        <option value="">Select recursion</option>
                                                        <option <?php if($form_recursion == 'every_day') { ?> selected <?php } ?> value="every_day"><?=  __('admin.every_day') ?></option>
                                                        <option <?php if($form_recursion == 'every_week') { ?> selected <?php } ?>  value="every_week"><?=  __('admin.every_week') ?></option>
                                                        <option <?php if($form_recursion == 'every_month') { ?> selected <?php } ?>  value="every_month"><?=  __('admin.every_month') ?></option>
                                                        <option <?php if($form_recursion == 'every_year') { ?> selected <?php } ?>  value="every_year"><?=  __('admin.every_year') ?></option>
                                                        <option <?php if($form_recursion == 'custom_time') { ?> selected <?php } ?>  value="custom_time"><?=  __('admin.custom_time') ?></option>
                                                    </select>
                                                </div>
                                                <div class="form-group custom_time <?php echo (!$form_recursion || $form_recursion != 'custom_time') ? 'hide' : '';  ?>">
                                                                                        
                                                    <?php
                                                    $minutes = $form['recursion_custom_time'];

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
                                                                <?php 
                                                                for ($x = 0; $x <= 23; $x++) {
                                                                    $selected = ($x == $hour ) ? 'selected="selected"' : '';
                                                                    echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>                      
                                                        <div class="col-sm-4">
                                                            <label class="control-label">Minutes : </label>
                                                            <select class="form-control" id="recur_minute">
                                                                <?php 
                                                                for ($x = 0; $x <= 59; $x++) {
                                                                    $selected = ($x == $minute ) ? 'selected="selected"' : '';
                                                                    echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>                      
                                                    </div>                                  
                                                </div>
                                                <br>
                                                <div class="endtime-chooser row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="control-label d-block"><?= __('admin.choose_custom_endtime') ?> <input <?= $form['recursion_endtime'] ? 'checked' : '' ?>  id='setCustomTime' name='recursion_endtime_status' type="checkbox"> </label>
                                                            <div style="<?= !$form['recursion_endtime'] ? 'display:none' : '' ?>" class='custom_time_container'>
                                                                <input type="text" class="form-control" value="<?= $form['recursion_endtime'] ? date("d-m-Y H:i",strtotime($form['recursion_endtime'])) : '' ?>" name="recursion_endtime" id="endtime" placeholder="Choose EndTime" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>   



                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?= __('admin.form_feature_image') ?></label><br>
                                        <div class="col-sm-9">
                                            <div class="fileUpload btn btn-sm btn-primary">
                                                <span><?= __('admin.choose_file') ?></span>
                                                <input id="form_fevi_icon" name="form_fevi_icon" class="upload" type="file">
                                            </div>
                                            <?php $form_fevi_icon = $form['fevi_icon'] != '' ? 'assets/images/form/favi/'.$form['fevi_icon'] : 'assets/images/no_image_available.png' ; ?>
                                            <img src="<?php echo base_url($form_fevi_icon); ?>" id="form_fevi_icon-img" class="thumbnail" border="0" width="220px">
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.allow_for_product'); ?></label>
                                        <select class="form-control" name="allow_for">
                                            <option value="A"><?= __('admin.all'); ?></option>
                                            <option value="S" <?= $form['allow_for'] == 'S' ? 'selected': '' ?>>Selected Only</option>
                                        </select>
                                    </div>
                                    <div class="select-product">
                                        <div class="well">
                                            <table class="simple-table">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Type</th>
                                                    <th>Allow Shipping</th>
                                                </tr>
                                                <tr><td colspan="100%">&nbsp;</td></tr>
                                                <?php $ids =explode(",", $form['product']);
                                                foreach ($product as $key => $p) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="checkbox">
                                                                <label><input type="checkbox" <?= in_array($p['product_id'], $ids) ? 'checked' : '' ?> name="product[]" value="<?= $p['product_id'] ?>"> <?= $p['product_name'] ?></label>
                                                            </div>
                                                        </td>
                                                        <td><?= c_format($p['product_price']) ?></td>
                                                        <td><?= product_type($p['product_type']) ?></td>
                                                        <td><?= $p['allow_shipping'] ? 'Yes' : 'No' ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                  
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.coupon'); ?></label>
                                        <select class="form-control" name="coupon">
                                            <option value="">No Selected</option>
                                            <?php foreach ($coupons as $key => $value) { ?>
                                                <option value="<?= $value['form_coupon_id'] ?>" <?= $value['form_coupon_id'] == $form['coupon'] ? 'selected': '' ?>><?= $value['name'] ?></option>
                                            <?php } ?>                                            
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.footer_content'); ?></label>
                                        <input type="text" class="form-control" name="footer_title" value="<?= $form['footer_title'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" ><?= __('admin.footer_google_analitics'); ?></label>
                                        <textarea cols="5" rows="5" class="form-control" name="google_analitics"><?= $form['google_analitics'] ?></textarea>                                        
                                    </div>
                                    <div class="form-group text-right">
                                        <span class="loading-submit"></span>
                                        <input type="hidden" name="save_type" value="exit">
                                        <?php if((int)$form['form_id']){ ?>
                                            <a class="btn btn-primary " href="<?php echo base_url('form/'. str_replace(' ', '_', trim($form['seo'])) .'/' . base64_encode($form['form_id'])) ?>" target='_blank'><?= __('admin.preview') ?></a>
                                        <?php } ?>
                                        <button type="submit" class="btn btn-primary btn-submit save_exit" data-rtype = 'save_exit'><?= __('admin.save_and_close') ?></button>
                                        <button type="submit" class="btn btn-primary btn-submit save_stay" data-rtype = 'save_stay'><?= __('admin.save'); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>

<script type="text/javascript">
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

    var btn;
    $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format:"dd-mm-yyyy"
    })
    $('.btn-submit').click(function(){
        btn = $(this);
        $('.redirect').val(btn.data('rtype'));
    })

    $('[name="allow_for"]').change(function(){
        $(".select-product").hide();
        if($(this).val() == 'S') $(".select-product").show();
    });

    $(".datepicker").each(function(){
        var d= $(this).val().split("-");
        if(d[0]){
            var date = d[1]  + "-" + d[2] + "-" + d[0];
            $(this).datepicker('update', new Date(date))
        }
        else{ $(this).val(''); }
    })

    function readURL(input) {
        if (input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#form_fevi_icon-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById("form_fevi_icon").onchange = function(){
        readURL(this);
    };

    $(".showonchange").change(function(){
        var val = $(this).val();
        
        $pare = $(this).parents('.row').eq(0);
        $pare.find(".toggle-divs p, .toggle-divs input, .toggle-divs .custom_recursion").hide();
        if(val == 'default'){
            $pare.find(".toggle-divs p").show();
        }else if(val){
            //$pare.find(".toggle-divs input").val('');
            $pare.find(".toggle-divs input").show();
            $pare.find(".toggle-divs .custom_recursion").show();
        }
    })

    $(document).on('ready',function() {
        sumNote($('.summernote-img'));
    });

  

    $(".showonchange").trigger('change');
    $("#form_form").submit(function(evt){
        evt.preventDefault();
        var formData = new FormData(this);
        
        formData = formDataFilter(formData);
        $this = $(this);
        
        $.ajax({
            url:'<?= base_url('admincontrol/save_form') ?>',
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
            success:function(result){
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
        })
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

    $(document).ready(function() {
        $('[name="allow_for"]').trigger("change");
    });
</script>