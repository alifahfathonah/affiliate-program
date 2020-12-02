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
		<?php if(!$zip_loaded){ ?>
			<div class="alert alert-danger">
				Zip extension is not installed on your hosting and you will not be able to take backups and to get versions update.<br>
				<span style="border-bottom:dotted 2px  ">Please contact your hosting support and ask them to install "zip extension".</span>
			</div>
		<?php } ?>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="card m-b-30">
					<div class="card-body">
						<form enctype="multipart/form-data" method="POST" action="">
							<div class="form-group">
								<p class="control-label" > Upload Backup File (zip)</p>
								<input type="file" name="backup_file">
							</div>
							<div class="form-group">
								<button class="btn btn-primary">Upload</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-12">
				<div class="card m-b-30">
					<div class="card-header">
						<h4 class="card-title pull-left"><?= __('admin.database_backup') ?></h4>
						<div class="pull-right">
							<a class="btn btn-success" href="<?php echo base_url('admincontrol/backup/getbackup') ?>"><?= __('admin.get_backup') ?></a>
						</div>
					</div>
					<div class="card-body">
						<div class="table-rep-plugin">
							 <?php if ($backups ==null) {?>
                                <div class="text-center">
                                <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:100px;">
                                 <h3 class="m-t-40 text-center text-muted"><?= __('admin.no_backups') ?></h3></div>
                                <?php }
                                else {?>
							<div class="table-responsive b-0" data-pattern="priority-columns">
								
									<table id="tech-companies-1" class="table  table-striped">
										<thead>
											<tr>
												
												<th><?= __('admin.file_name') ?></th>
												<th width="200px"><?= __('admin.date_time') ?></th>
												<th width="250px"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($backups as $backup){ ?>
												<tr>
													<td>
														<?= $backup['file'] ?> <br>
														<span class="text-muted"><b><?= __('admin.size') ?></b> <?= $backup['size'] ?></span>
													</td>
													<td><?= $backup['date'] ?></td>
													<td>
														<a href="<?php echo base_url('admincontrol/backup/download?file_name='. $backup['file']) ?>" class="btn btn-success"  target="_blank" ><?= __('admin.download') ?></a>
														<a href="<?php echo base_url('admincontrol/backup/restore?file_name='. $backup['file']) ?>" class="btn btn-primary" onclick="return confirm('Are you sure Restore this file ?')"><?= __('admin.restore') ?></a>
														<a href="<?php echo base_url('admincontrol/backup/delete?file_name='. $backup['file']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure delete file ?')"><?= __('admin.delete') ?></a>
													</td>
												</tr>
											<?php } ?>
											<?php } ?>
										</tbody>
									</table>
								
							</div>
						</div>
					</div>
				</div> 
			</div> 
		</div>

 