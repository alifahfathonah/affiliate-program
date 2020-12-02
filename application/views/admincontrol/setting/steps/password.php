<div class="modal" id="model-adminpassword">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label">Enter Admin Password</label>
                    <input type="password" id="admin-password" class="password">
                </div>

                <div class="password-alert"></div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-primary btn-confirm-password">Confirm</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?= __('admin.close') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(".btn-confirm-password").on("click",function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url("installversion/check_confirm_password") ?>',
            type:'POST',
            dataType:'json',
            data:{password:$("#admin-password").val()},
            beforeSend:function(){$this.btn("loading");},
            complete:function(){$this.btn("reset");},
            success:function(json){
                $(".password-alert").hide();

                if(json['warning']){
                    $(".password-alert").html(json['warning']).show();
                }

                if(json['success']){
                    $("#model-adminpassword").modal("hide");
                    <?php if($for == 'files'){ ?>
                        migrateFiles($(".btn-upload-database"));
                    <?php } else { ?>
                        migrateDatabase($(".btn-upload-update"));
                    <?php } ?>
                }
            },
        })
    })
</script>