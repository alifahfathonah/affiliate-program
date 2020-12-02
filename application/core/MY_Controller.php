<?php
class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->db->query("SET SQL_MODE = ''");
		 
		$this->load->model("Product_model");
		$site_setting = $this->Product_model->getSettings('site');

		if (isset($site_setting['time_zone']) && $site_setting['time_zone'] != '') {
			date_default_timezone_set($site_setting['time_zone']);
		} else{
			date_default_timezone_set('Asia/Kolkata');
		}
	}
}
