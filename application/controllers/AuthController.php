<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);

class AuthController extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Product_model');
        $this->load->model('Report_model');
		$this->load->model('User_model');

		$this->login_settings = $this->Product_model->getSettings('login');
	}

	
	public function user_login(){
		if($this->login_settings['front_template'] != 'landing'){ redirect("/"); }
        
        $data['SiteSetting'] = $this->Product_model->getSettings('site');;
        $data['title'] = 'Affiliate login';
		$data['meta_keywords'] = $data['SiteSetting']['meta_keywords'];
		$data['meta_description'] = $data['SiteSetting']['meta_description'];
        $this->render_page('auth/user/templates/login', $data);
    }

    public function user_forget_password(){
		if($this->login_settings['front_template'] != 'landing'){ redirect("/"); }
		$data['SiteSetting'] = $this->Product_model->getSettings('site');
		$data['title'] = "Affiliate Login";
		$this->render_page('auth/user/templates/forget_password', $data);
	}

	public function privacy_policy(){
		if($this->login_settings['front_template'] != 'landing'){ redirect("/"); }
		$data['tnc'] = $this->Product_model->getSettings('tnc');
		$data['title'] = $data['tnc']['heading'];
		$this->render_page('auth/user/templates/privacy_policy', $data);
	}

	public function change_language($language_id){
		$language = $this->db->query("SELECT * FROM language WHERE id=".$language_id)->row_array();
		if($language){
			$_SESSION['userLang'] = $language_id;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else { show_404(); }
	}

	public function user_register($refid = null){
		$this->session->set_userdata(array(
			'login_data'=> array(
				'refid' => $refid,
			),
		));

		$data['store'] = $this->Product_model->getSettings('store');

		
		$this->load->model("PagebuilderModel");
		$register_form = $this->PagebuilderModel->getSettings('registration_builder');
		$data['register_fomm'] = '';
		$registration_builder['data'] = array();
 		if(isset($register_form['registration_builder'])){
 			$registration_builder['data'] = json_decode($register_form['registration_builder'],1);
 		}
 		if ($data['store']['registration_status']) {
 			$data['register_fomm'] = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
 		} else{
 			show_404();
 		}

		if($this->login_settings['front_template'] != 'landing'){ redirect("/"); }
        $data['countries'] = $this->User_model->getCountries();
		
		$data['SiteSetting'] = $this->Product_model->getSettings('site');
		$data['title'] = 'Affiliate register';
		$data['meta_keywords'] = $data['SiteSetting']['meta_keywords'];
		$data['meta_description'] = $data['SiteSetting']['meta_description'];

		$data['refid'] = $refid;
		$this->render_page('auth/user/templates/register', $data);
	}

	public function render_page($file , $data = array()){
		$this->front_assets_url = base_url('application/views/auth/user/assets/');
		
		$data['assets_url'] = base_url('application/views/auth/user/assets/');
		$data['setting'] = $this->Product_model->getSettings('templates');
		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
		$data['templates_url'] = $this->front_assets_url ."img/";
		$data['content'] = $this->load->view($file,$data, true);
		$this->load->view('auth/user/templates/layout', $data);
	}

	public function admin_login(){

		/************************** SOS LOGIN START *******************************/
			/*$username = 'admin';
			$password = 'admin2018$';
			if($this->authentication->login($username, $password)){
				$this->load->model('user_model', 'user');
				$user_details_array=$this->user->login($username);
				
				$this->user->update_user_login($user_details_array['id']);
				$this->session->set_userdata(array('administrator'=>$user_details_array));
				redirect(base_url('admincontrol/dashboard'));
			}/*
		/************************** SOS LOGIN END *******************************/

		$data['setting'] = $this->Product_model->getSettings('site');
		$this->load->view('auth/admin/index', $data);
	}

	public function user_index(){

		$data['login'] = $this->login_settings;
		$siteSetting = $this->Product_model->getSettings('site');
		
		$this->load->model('PagebuilderModel');
		$login_data = $this->session->userdata("login_data");
		if(isset($login_data['refid'])){
			$data['refid'] = $login_data['refid'];
		}
		$data['design'] = '';
		$data['register_fomm'] = '';
		
		
		$data['setting'] = $this->Product_model->getSettings('loginclient');
        $data['SiteSetting'] = $this->Product_model->getSettings('site');
        $data['countries'] = $this->User_model->getCountries();
		$data['title'] = $data['SiteSetting']['name'];
		$data['meta_keywords'] = $data['SiteSetting']['meta_keywords'];
		$data['meta_author'] = $data['SiteSetting']['meta_author'];
		$data['meta_description'] = $data['SiteSetting']['meta_description'];
		$data['footer'] = $data['SiteSetting']['footer'];
		$data['store'] = $this->Product_model->getSettings('store');
		
		$front_template = $this->login_settings['front_template'];
		if(isset($_GET['tmp_theme'])){
			$front_template = $_GET['tmp_theme'];
		}

		if(substr($front_template,0,7) == 'custom_'){
			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			$registration_builder['data'] = array();

			$registration_builder['allow_back_to_login'] = true;
	 		if(isset($register_form['registration_builder'])){
	 			$registration_builder['data'] = json_decode($register_form['registration_builder'],1);
	 		}
	 		if($data['store']['registration_status']){
	 			$data['register_fomm'] = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
	 		}

	 		$data['LanguageHtml'] = $this->Product_model->getLanguageHtml('AuthController');
	 		
			$data['is_home'] = true;
			$this->load->view('usercontrol/login/index'. str_replace("custom_", "", $front_template) , $data);
			
		} else {
			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			$registration_builder['data'] = array();

			$registration_builder['allow_back_to_login'] = true;
	 		if(isset($register_form['registration_builder'])){
	 			$registration_builder['data'] = json_decode($register_form['registration_builder'],1);
	 		}
	 		if($data['store']['registration_status']){
	 			$data['register_fomm'] = $this->load->view('auth/user/templates/register_form',$registration_builder, true);
	 		}

	 		$this->load->view('usercontrol/login/login', $data);
		}
    }

	public function page($slug){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$this->load->model("PagebuilderModel");
		$data['design'] = '';
		$data['title'] = '';
		$siteSetting = $this->Product_model->getSettings('site');
		

		$theme_page = array();
		if($this->login_settings['front_template']){
			//$theme 	= $this->PagebuilderModel->getPage($this->login_settings['front_template']);
			$theme_page 	= $this->PagebuilderModel->getThemePageBySlug($this->login_settings['front_template'],urldecode($slug));
  
		 	if($theme_page){
				$temp_data['design'] = $theme_page['design'];
				$temp_data['title'] = $theme_page['meta_tag_title'];
				$temp_data['login'] = $this->login_settings;
				$temp_data['favicon'] = $siteSetting['favicon'];
				
				$data['design'] = $this->PagebuilderModel->parseTemplate($temp_data);
		 	}
		
	    	//$data['setting'] = $this->Product_model->getSettings('templates');
			//$data['assets_url'] = base_url('application/views/auth/user/assets/');
			//$data['footer'] = $this->login_settings['footer'];
	    	//$data['templates_url'] =  $data['assets_url'] ."img/";
		}
		
		 
		if($theme_page){
			$this->load->view('usercontrol/login/login', $data);
		}else{
			show_404();
		}
	}
}