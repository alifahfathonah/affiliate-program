<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
    $store_setting =$db->Product_model->getSettings('store');
?>
<div class="row">
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
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo c_format($totals['store']['balance']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_sales' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo c_format($totals['store']['balance']) ?> / <?php echo c_format($totals['all_sale_comm']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_clicks' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo (int)$totals['store']['click_count'] ?> /  <?php echo c_format($totals['store']['click_amount']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_commission' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo c_format($totals['store']['total_commission']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_orders' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo $ordercount; ?></span>
                            </li>
                            <li class="list-group-item">
                                <span class="badge badge-light"><?php $store_url = base_url('store'); ?></span>
                                <a class="btn btn-lg btn-default btn-success" href="<?php echo $store_url ?>"
                                    target="_blank"><?= __('admin.priview_store') ?></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="">
                    <div>
                        <b><?= __('user.store_nurl') ?></b>
                        <div class="row">
                            <div class="col-sm-8">
                                <?php $store_url = base_url('store/'.base64_encode($userdetails['id']) ); ?>
                                <div class="input-group">
                                    <input type="text" id="store-link" readonly="readonly" value="<?php echo $store_url ?>" class="form-control">
                                    <button onclick="copy_text()" class="input-group-addon">
                                    <img src="<?php echo base_url('assets/images/clippy.svg') ?>" class="tooltiptext" width="25px" height="25px" alt="Copy to clipboard">
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="share-store-list">
                                    <a href="<?php echo $store_url ?>" target="_blank"><?= __('user.priview_nstore') ?></a>
                                    <a onclick="shareinsocialmedia('https://www.facebook.com/sharer/sharer.php?u=<?php echo $store_url ?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-facebook fa-6" aria-hidden="true"></i></a>
                                    <a onclick="shareinsocialmedia('https://plus.google.com/share?url=<?php echo $store_url ?>/<?php echo $user['id'];?>')" href="javascript:void(0)"><i class="fa fa-google-plus fa-6" aria-hidden="true"></i></a>
                                    <a onclick="shareinsocialmedia('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $store_url ?>/<?php echo $user['id'];?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-linkedin fa-6" aria-hidden="true"></i></a>
                                    <a onclick="shareinsocialmedia('http://twitter.com/home?status=Buy Product and earn by affiliate program+<?php echo $store_url ?>/<?php echo $user['id'];?>')" href="javascript:void(0)"><i class="fa fa-twitter fa-6" aria-hidden="true"></i></a>
                                    <a href="mailto:?subject=Buy Product and earn by affiliate program&amp;body=Check out this site <?= $productLink ?>" title="Share by Email">
                                    <i class="fa fa-envelope cursors" aria-hidden="true" style="color:#2a3f54"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <?php if ($productlist ==null) {?>
                        <div class="text-center">
                            <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                            <h3 class="m-t-40 text-center"><?= __('admin.no_products') ?></h3>
                        </div>
                        <?php } else { ?>
                            <?php 
                                $pro_setting = $this->Product_model->getSettings('productsetting');
                            ?>
                            <div class="table-responsive">
                                <table id="tech-companies-1" class="table  table-striped">
                                    <thead>
                                        <tr>
                                            <th><?= __('user.name') ?></th>
                                            <th width="60px"><?= __('user.featured_nimage') ?></th>
                                            <th><?= __('user.price') ?></th>
                                            <th><?= __('user.sku') ?></th>
                                            <th width="220px"><?= __('user.get_ncommission') ?></th>
                                            <th><?= __('user.sales_n_ncommission') ?></th>
                                            <th><?= __('user.clicks_n_ncommission') ?></th>
                                            <th><?= __('user.total_ncommission') ?></th>
                                            <th width="160px"><?= __('user.action') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($productlist as $product){ ?>
                                            <?php
                                                if(empty($product['view'])){
                                                    $product['view'] = 0;
                                                }
                                                if(empty($product['click'])){
                                                    $product['click'] = 0;
                                                }
                                                if(empty($product['commission'])){
                                                    $product['commission'] = 0;
                                                }
                                            
                                                $productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] );
                                            ?>
                                            <tr>
                                                <td>

                                                    <div class="tooltip-copy">

                                                        <?php if($product['product_type'] == 'downloadable'){ ?>
                                                            <img src="<?= base_url('assets/images/download.png') ?>" width="20px">
                                                        <?php } ?>
                                                        <?php echo $product['product_name'];?>
                                                        <div><small>
                                                            <a href="javascript:void(0)" copyToClipboard="<?= $productLink ?>">Copy link</a> /
                                                            <a target="_blank" href="<?= $productLink ?>"><?= __('user.public_npage') ?></a> / 
                                                            <a href="javascript:void(0);" onclick="generateCode(<?php echo $product['product_id'];?>);" ><?= __('user.get_ncode') ?></a>
                                                        </small></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="tooltip-copy">
                                                        <img width="50px" height="50px" src="<?php echo base_url();?>/assets/images/product/upload/thumb/<?php echo $product['product_featured_image'];?>" ><br>
                                                    </div>
                                                </td>
                                                <td class="txt-cntr"><?php echo c_format($product['product_price']); ?>
                                                    <br>
                                                </td>
                                                <td class="txt-cntr">
                                                    <?php echo $product['product_sku'];?>
                                                </td>
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
                                                            echo "<small>PPC : {$product['product_click_commision_per']} Click for : ";
                                                            echo c_format($product['product_click_commision_ppc']) ."</small>";
                                                        }
                                                    ?>

                                                    <?php 
                                                        if($product['product_recursion_type']){
                                                            if($product['product_recursion_type'] == 'custom'){
                                                                if($product['product_recursion'] != 'custom_time'){
                                                                    echo '<b>Recurring </b> : ' . $product['product_recursion'];
                                                                } else {
                                                                    echo '<b>Recurring </b> : '. timetosting($product['recursion_custom_time']);
                                                                }
                                                            } else{
                                                                if($pro_setting['product_recursion'] == 'custom_time' ){
                                                                    echo '<b>Recurring </b> : '. timetosting($pro_setting['recursion_custom_time']);
                                                                } else {
                                                                    echo '<b>Recurring </b> : '. $pro_setting['product_recursion'];
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                                <td class="txt-cntr">
                                                    <?php echo $product['order_count'];?> / <?php echo c_format($product['commission']); ?>
                                                </td>
                                                <td class="txt-cntr">
                                                    <?php echo (int)$product['commition_click_count'];?> / <?php echo c_format($product['commition_click']); ?>
                                                </td>
                                                <td class="txt-cntr">
                                                    <?php echo c_format(
                                                        ((float)$product['commition_click'] + (float)$product['commission'])
                                                        ); ?>
                                                </td>
                                                <td class="txt-cntr">
                                                    <div class="share-list">
                                                        <a onclick="shareinsocialmedia('https://www.facebook.com/sharer/sharer.php?u=<?= $productLink ?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-facebook fa-6" aria-hidden="true"></i></a>
                                                        <a onclick="shareinsocialmedia('https://plus.google.com/share?url=<?= $productLink ?>')" href="javascript:void(0)"><i class="fa fa-google-plus fa-6" aria-hidden="true"></i></a>
                                                        <a onclick="shareinsocialmedia('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?= $productLink ?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-linkedin fa-6" aria-hidden="true"></i></a>
                                                        <a onclick="shareinsocialmedia('http://twitter.com/home?status=Buy Product and earn by affiliate program+<?= $productLink ?>')" href="javascript:void(0)"><i class="fa fa-twitter fa-6" aria-hidden="true"></i></a>
                                                        <a href="mailto:?subject=Buy Product and earn by affiliate program&amp;body=Check out this site <?= $productLink ?>" title="Share by Email">
                                                            <i class="fa fa-envelope cursors" aria-hidden="true" style="color:#2a3f54"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if(false){ ?>
                                <div class="table-responsive b-0" >
                                    <table id="product-list" class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="1"></th>
                                                <th>Image</th>
                                                <th>Commission</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <?php $pagination = 10; ?>
                                        <tbody>
                                            <?php foreach($data_list as $index => $product){ ?>
                                                <?php
                                                    $display_class = $index >= $pagination ? 'd-none' : '';
                                                ?>
                                                <?php if(isset($product['is_form'])){ ?>
                                                <tr class="<?= $display_class ?>">
                                                    <td class="text-center">
                                                        <button type="button" class="toggle-child-tr"><i class="fa fa-plus"></i></button>
                                                    </td>
                                                    <td><img width="50px" height="50px" src="<?php echo resize('assets/images/share-icon.png' ,100,100) ?>" ></td>
                                                    <td>
                                                        <?php
                                                            echo "<b>You Will Get</b> ";
                                                            if($product['sale_commision_type'] == 'default'){
                                                            	$commissionType = $form_default_commission['product_commission_type'];
                                                            	if($form_default_commission['product_commission_type'] == 'percentage'){
                                                            		echo $form_default_commission['product_commission'] .'% Per Sale';
                                                            	}
                                                            	else if($form_default_commission['product_commission_type'] == 'Fixed'){
                                                            		echo c_format($form_default_commission['product_commission']) .' Per Sale';
                                                            	}
                                                            }
                                                            else if($product['sale_commision_type'] == 'percentage'){
                                                            	echo $product['sale_commision_value'] .'% Per Sale';
                                                            }
                                                            else if($product['sale_commision_type'] == 'fixed'){
                                                            	echo c_format($product['sale_commision_value']) .' Per Sale';
                                                            }
                                                            
                                                            echo "<br> <b>You Will Get</b> ";
                                                            if($product['click_commision_type'] == 'default'){
                                                            	$commissionType = $form_default_commission['product_commission_type'];
                                                            	if($form_default_commission['product_commission_type'] == 'percentage'){
                                                            		echo $form_default_commission['product_ppc'] .'% of Per '. $form_default_commission['product_noofpercommission'] .' Click';
                                                            	}
                                                            	else if($form_default_commission['product_commission_type'] == 'Fixed'){
                                                            		echo c_format($form_default_commission['product_ppc']) .' of Per '. $form_default_commission['product_noofpercommission'] .' Click';
                                                            	}
                                                            }
                                                            else if($product['click_commision_type'] == 'custom') {
                                                            	echo c_format($product['click_commision_ppc']) .' of Per '. $product['click_commision_per'] .' Click';
                                                            }
                                                            ?>
                                                    </td>
                                                    <td>
                                                        <div class="form-group m-0">
                                                            <div class="input-group">
                                                                <input readonly="readonly" value="<?= $product['public_page'] ?>" class="form-control">
                                                                <button type="button" copyToClipboard="<?= $product['public_page'] ?>" class="input-group-addon" style="    border-left: 0;">
                                                                <img src="<?= base_url() ?>/assets/images/clippy.svg" class="tooltiptext" width="20px" height="20px" >
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="code-share-<?= $key ?>"></div>
                                                        <script type="text/javascript">
                                                            $(document).on('ready',function(){
                                                            	$(".code-share-<?= $key ?>").jsSocials({
                                                            		url: "<?= $product['public_page'] ?>",
                                                            		showCount: false,
                                                            		showLabel: false,
                                                            		shareIn: "popup",
                                                            		shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
                                                            	});
                                                            })
                                                        </script>
                                                    </td>
                                                </tr>
                                                <tr class="detail-tr">
                                                    <td colspan="100%">
                                                    	<div>
            	                                            <ul>
            	                                                <li><b><?= __('admin.name'); ?>: </b> <span><?= $product['title'] ?></span></li>
            	                                                <li><b><?= __('admin.coupon_code'); ?>: </b> <span><?= $product['coupon_code'] ? $product['coupon_code'] : 'N/A' ?></span></li>
            	                                                <li><b><?= __('admin.coupon_use'); ?>: </b> <span><?= ($product['coupon_name'] ? $product['coupon_name'] : '-').' / '.$product['count_coupon'] ?></span></li>
            	                                                <li><b><?= __('admin.sales_commission'); ?>: </b> <span><?= (int)$product['count_commission'].' / '.c_format($product['total_commission']) ?></span></li>
            	                                                <li><b><?= __('admin.clicks_commission'); ?>: </b> <span><?= (int)$product['commition_click_count'].' / '.c_format($product['commition_click']); ?></span></li>
            	                                                <li><b><?= __('admin.total_commission'); ?>: </b> <span><?= c_format($product['total_commission']+$product['commition_click']); ?></span></li>
            	                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } else {  ?>
                                                <?php 
                                                    $productLink = base_url('store/'. base64_encode($userdetails['id']) .'/product/'.$product['product_slug'] );
                                                    ?>
                                                <tr class="<?= $display_class ?>">
                                                    <td class="text-center">													
                                                        <button type="button" class="toggle-child-tr"><i class="fa fa-plus"></i></button>
                                                    </td>
                                                    <td><img width="50px" height="50px" src="<?php echo resize('assets/images/product/upload/thumb/'. $product['product_featured_image'] ,100,100) ?>" ></td>
                                                    <td>
                                                        <b>You Will Get</b> : 
                                                        <?php
                                                            if($product['product_commision_type'] == 'default'){
                                                            	if($default_commition['product_commission_type'] == 'percentage'){
                                                            		echo $default_commition['product_commission']. "% Per Sale";
                                                            	} else {
                                                            		echo c_format($default_commition['product_commission']) ." Per Sale";
                                                            	}
                                                            } else if($product['product_commision_type'] == 'percentage'){
                                                            	echo $product['product_commision_value']. "% Per Sale";
                                                            } else{
                                                            	echo c_format($product['product_commision_value']) ." Per Sale";
                                                            }
                                                            ?>
                                                        <br><b>You Will Get</b> :
                                                        <?php
                                                            if($product['product_click_commision_type'] == 'default'){
                                                            	echo c_format($default_commition['product_ppc']) ." Per {$default_commition['product_noofpercommission']} Click"; 	
                                                            	echo "</small>";
                                                            } else{
                                                            	echo c_format($product['product_click_commision_per']) ." Per {$product['product_click_commision_ppc']} Click";
                                                            }
                                                            ?>
                                                    </td>
                                                    <td>
                                                        <div class="form-group m-0">
                                                            <div class="input-group">
                                                                <input readonly="readonly" value="<?= $productLink ?>" class="form-control">
                                                                <button type="button" copyToClipboard="<?= $productLink ?>" class="input-group-addon" style="    border-left: 0;">
                                                                <img src="<?= base_url() ?>/assets/images/clippy.svg" class="tooltiptext" width="20px" height="20px" >
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
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
                                                    </td>
                                                </tr>
                                                <tr class="detail-tr">
                                                    <td colspan="100%">
                                                    	<div>
            	                                            <ul>
            	                                                <li><b><?= __('admin.product_name') ?>:</b><span><?php echo $product['product_name'];?></span></li>
            	                                                <li><b><?= __('admin.price') ?> :</b><span><?php echo c_format($product['product_price']); ?></span></li>
            	                                                <li><b><?= __('admin.sku') ?> :</b><span><?php echo $product['product_sku'];?></span></li>
            	                                                <li>
            	                                                    <b><?= __('admin.sales_/_commission') ?> :</b>
            	                                                    <span>
            	                                                    <?php echo $product['order_count'];?> / 
            	                                                    <?php echo c_format($product['commission']) ;?>
            	                                                    </span>
            	                                                </li>
            	                                                <li>
            	                                                    <b><?= __('admin.clicks_/_commission') ?> :</b>
            	                                                    <span>
            	                                                    <?php echo (int)$product['commition_click_count'];?> / <?php echo c_format($product['commition_click']) ;?>
            	                                                    </span>
            	                                                </li>
            	                                                <li>
            	                                                    <b><?= __('admin.total') ?> :</b>
            	                                                    <span>
            	                                                    <?php echo c_format((float)$product['commition_click'] + (float)$product['commission']); ?>
            	                                                    </span>
            	                                                </li>
            	                                                <li><b><?= __('admin.display') ?> :</b> <span><?= $product['on_store'] == '1' ? 'Yes' : 'No' ?></span></li>
            	                                            </ul>
                                                    	</div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                        <?php if($index > $pagination){ ?>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="10%">
                                                        <button type="button" class="btn btn-primary show-more">Show More</button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <?php } ?>
                                    </table>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="model-codemodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" async="">
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
    $(".show-more").on('click',function(){
    	$(this).parents("tfoot").remove();
    	$("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
    });
    
    function generateCode(affiliate_id){
        $this = $(this);
        $.ajax({
            url:'<?php echo base_url();?>usercontrol/generateproductcode/'+affiliate_id,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
                $this.btn("reset");
            },
            success:function(json){
                $('#model-codemodal .modal-body').html(json)
                $("#model-codemodal").modal("show")
            },
        })
    }
</script>