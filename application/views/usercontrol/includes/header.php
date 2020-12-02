<!DOCTYPE html>
<?php 
    $db =& get_instance();
    $userdetails=$db->Product_model->userdetails(); 
    $SiteSetting =$db->Product_model->getSiteSetting();
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= $SiteSetting['name'] ?> - <?= __('user.top_title') ?></title>
    <meta content="<?= $SiteSetting['meta_description'] ?>" name="description" />
    <meta content="<?= $SiteSetting['meta_author'] ?>" name="author" />
    <meta content="<?= $SiteSetting['meta_keywords'] ?>" name="keywords" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="<?php echo base_url(); ?>assets/vertical/assets/plugins/magnific-popup/magnific-popup.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/js/jquery-confirm.min.css?v=<?= av() ?>" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vertical/assets/plugins/morris/morris.css?v=<?= av() ?>" rel="stylesheet">
    

    <link href="<?php echo base_url(); ?>assets/vertical/assets/css/bootstrap.min.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/vertical/assets/css/icons.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/vertical/assets/css/style.css?v=<?= av() ?>" rel="stylesheet" type="text/css">
    
    <link href="<?php echo base_url(); ?>assets/vertical/assets/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css?v=<?= av() ?>" rel="stylesheet" type="text/css" media="screen">
    <?php if($SiteSetting['favicon']){ ?>
        <link rel="icon" href="<?php echo base_url('assets/images/site/'.$SiteSetting['favicon']) ?>" type="image/*" sizes="16x16">
    <?php } ?>
    <link href="<?php echo base_url(); ?>assets/css/jquery.uploadPreviewer.css?v=<?= av() ?>" rel="stylesheet" type="text/css" media="screen">
    <?php if($SiteSetting['google_analytics'] != ''){ ?><?= $SiteSetting['google_analytics'] ?><?php } ?>
    
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    


    <!-- <script src="<?php echo base_url(); ?>assets/js/jquery.uploadPreviewer.js"></script> -->

    <link href="<?= base_url('assets/plugins/datatable') ?>/select2.css?v=<?= av() ?>" rel="stylesheet" />
    <script src="<?= base_url('assets/plugins/datatable') ?>/select2.min.js"></script>


    <link rel='stylesheet' href='<?= base_url('assets/css/usercontrol-common.css') ?>?v=<?= av() ?>' />
        
    <script type="text/javascript">
        
        (function ($) {
            $.fn.btn = function (action) {
                var self = $(this);
                var tagName = self.prop("tagName");
                if(tagName == 'A'){
                    if (action == 'loading') {
                        $(self).attr('data-text',$(self).text());
                        $(self).text("Loading..");
                    }
                    if (action == 'reset') { $(self).text($(self).attr('data-text')); }
                }
                else {
                    if (action == 'loading') { $(self).addClass("btn-loading"); }
                    if (action == 'reset') { $(self).removeClass("btn-loading"); }
                }
            }
        })(jQuery);
        
        var formDataFilter = function(formData) {
            if (!(window.FormData && formData instanceof window.FormData)) return formData
            if (!formData.keys) return formData
            var newFormData = new window.FormData()
            Array.from(formData.entries()).forEach(function(entry) {
                var value = entry[1]
                if (value instanceof window.File && value.name === '' && value.size === 0) {
                    newFormData.append(entry[0], new window.Blob([]), '')
                } else {
                    newFormData.append(entry[0], value)
                }
            })
            
            return newFormData
        }
    </script>
    

    
</head>

<body class="fixed-left">

        <!-- Loader -->
        <div id="preloader">
            <div id="status">
                <div class="sk-three-bounce">
                    <div class="sk-child sk-bounce1"></div>
                    <div class="sk-child sk-bounce2"></div>
                    <div class="sk-child sk-bounce3"></div>
                </div>
            </div>
        </div>

        <!-- Begin page -->
        <div id="wrapper">
            

        