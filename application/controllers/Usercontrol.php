<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Usercontrol extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->helper('share');
		$this->load->helper('url');
		$this->load->library('user_agent');
		$session='';
		$path_info = (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : (!empty($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : '');			
		if($this->router->class!='usercontrol' &&  $this->router->method !='register')
		{
			if (!$session && $this->router->class !='usercontrol' && $this->router->method != 'index' ) {
				redirect('usercontrol');
			} else if ($session && ($path_info == '/usercontrol' || $path_info == '/usercontrol/')) {
				redirect('usercontrol/dashboard');
			}
		}
	}
	public function change_language($language_id){
		$language = $this->db->query("SELECT * FROM language WHERE id=".$language_id)->row_array();
		if($language){
			$_SESSION['userLang'] = $language_id;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else { show_404(); }
	}
	public function change_currency($currency_code){
		$language = $this->db->query("SELECT * FROM currency WHERE code = '{$currency_code}' ")->row_array();
		if($language){
			$_SESSION['userCurrency'] = $currency_code;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else { show_404(); }
	}
	
	public function getSiteSetting(){
		return $this->Product_model->getSettings('site');
	}
	
	public function userdetails(){			
		return $this->session->userdata('user');
	}
	/*public function myreferal(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		
		$referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }
        if(!$refer_status){ show_404(); }

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/myreferal/index', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}*/
	public function myreferal_ajax(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$data = $this->Product_model->getMyUnder($userdetails['id']);

		echo json_encode($data);die;
	}

	/*public function userslisttree(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }
        if(!$refer_status){ show_404(); }
		
		
		$data['userslist'] = $this->Product_model->getAllUsersTreeV3(array(),$userdetails['id']);
		// echo "<pre>"; print_r($data['userslist']); echo "</pre>";die; 
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/users/tree', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}*/
	
	public function resetpassword($token){
		$tok  =  $this->db->query("SELECT * FROM password_resets WHERE token like '{$token}' ")->row();
		$post = $this->input->post(null,true);

		if($tok){
			if (isset($post['conf_password'])) {
				if($post['password'] == $post['conf_password']){
					$password = $this->input->post('password',true);
					$res = array('password'=>sha1($password));
					$this->db->where('email',$tok->email);
					$this->db->update('users',$res);
					$this->db->query("DELETE  FROM password_resets WHERE email like '{$tok->email}' ");
					$this->session->set_flashdata('success' , __('user.password_reset_successfully_successfully'));
					$user = $this->db->query("SELECT * FROM `users` WHERE email like '{$tok->email}' ")->row();
					if($user->type == 'client'){
						redirect(base_url('store/login'));
					} else {
						redirect(base_url());
					}
				}
				else{
					$this->session->set_flashdata('error',__('user.confirm_password_not_match'));
					redirect(base_url('resetpassword/' . $token));
				}
			}
			$this->load->view('resetpassword');
		}
		else
		{
			die("Token Expire..!");
		}
	}

	public function index(){
		redirect('/', 'refresh');
	}
	
	/*public function index($refid = null, $product_slug = null, $user_id = null) {
		if($this->userdetails()){
			redirect('usercontrol/dashboard', 'refresh');
		}
		$this->session->set_userdata(array(
			'login_data'=> array(
				'refid' => $refid,
				'product_slug' => $product_slug,
				'user_id' => $user_id,
			),
		));
		$data['title'] = 'Login Page';
		$data['setting'] = $this->Product_model->getSettings('login');
		$this->load->view('usercontrol/login/login', $data);
	}*/
	public function notification(){
		if(!$this->userdetails()){ redirect('/login', 'refresh'); }
		$this->load->library('pagination');
    	$this->load->helper('url');
    	$config['base_url'] = base_url('usercontrol/notification');
		$config['per_page'] = 10;
		$post = $this->input->post(null,true);

		if (isset($post['delete_ids'])) {
			$delete_ids = implode(",", $post['delete_ids']);
			$this->db->query("DELETE FROM notification WHERE notification_id IN ({$delete_ids})");
			echo json_encode(array());
			die;
		}
		$data['title'] = 'Notification';
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$notification = $this->user->getAllNotificationPaging('user',$this->userdetails()['id'],$config['per_page'],$page);
		$config['total_rows'] = $notification['total'];
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['notifications'] = $notification['notifications'];
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/notification', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	    $output = NULL;
	    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
	        $ip = $_SERVER["REMOTE_ADDR"];
	        if ($deep_detect) {
	            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	    }
	    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	    $continents = array(
	        "AF" => "Africa",
	        "AN" => "Antarctica",
	        "AS" => "Asia",
	        "EU" => "Europe",
	        "OC" => "Australia (Oceania)",
	        "NA" => "North America",
	        "SA" => "South America"
	    );
	    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
	        
	        $curl = curl_init("http://www.geoplugin.net/json.gp?ip=" . $ip);
	        $request = '';
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_HEADER, false);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        
	        $ipdat = json_decode(curl_exec($curl));
	        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
	            switch ($purpose) {
	                case "location":
		                $id = 0;
	                    $code = @$ipdat->geoplugin_countryCode;
	                    $data = $this->db->query("SELECT id FROM countries WHERE sortname LIKE '{$code}' ")->row();
	                    if($data){
	                    	$id = $data->id;
	                    }
	                    $output = array(
							"city"           => @$ipdat->geoplugin_city,
							"state"          => @$ipdat->geoplugin_regionName,
							"country"        => @$ipdat->geoplugin_countryName,
							"country_code"   => @$ipdat->geoplugin_countryCode,
							"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
							"continent_code" => @$ipdat->geoplugin_continentCode,
							"id"             => $id
	                    );
	                    break;
	                case "address":
	                    $address = array($ipdat->geoplugin_countryName);
	                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
	                        $address[] = $ipdat->geoplugin_regionName;
	                    if (@strlen($ipdat->geoplugin_city) >= 1)
	                        $address[] = $ipdat->geoplugin_city;
	                    $output = implode(", ", array_reverse($address));
	                    break;
	                case "city":
	                    $output = @$ipdat->geoplugin_city;
	                    break;
	                case "state":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "region":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "country":
	                    //$output = @$ipdat->geoplugin_countryName;
	                    $output = 0;
	                    $code = @$ipdat->geoplugin_countryCode;
	                    $data = $this->db->query("SELECT id FROM countries WHERE sortname LIKE '{$code}' ")->row();
	                    if($data){
	                    	$output = $data->id;
	                    }
	                    break;
	                case "countrycode":
	                    $output = @$ipdat->geoplugin_countryCode;
	                    break;
	            }
	        }
	    }
	   
	    return $output;
	}
    public function getState(){
        $this->load->model('User_model');
        $country_id = $this->input->post('country_id',true);
        $states = $this->User_model->getState($country_id);
        echo json_encode($states);
        die;
    }
	public function auth($action){
		$json = array();
		$post = $this->input->post(null,true);
		if ($action == 'login') {
			$username = $this->input->post('username',true);
			$password = $this->input->post('password',true);

			$checking_key = (isset($post['type']) && $post['type'] == 'admin') ? 'admin_login' : 'affiliate_login';
			 
			$googlerecaptcha = $this->Product_model->getSettings('googlerecaptcha');
			if (isset($googlerecaptcha[$checking_key]) && $googlerecaptcha[$checking_key]) {
				if($post['g-recaptcha-response'] == ''){
					if($checking_key != 'admin_login'){
						$json['errors']['username'] = 'Invalid Recaptcha';
					}
					//$json['errors']['g-recaptcha-response'] = 'Invalid Recaptcha';
				}
			}

			if( count($json['errors']) == 0 ){
				if (isset($googlerecaptcha[$checking_key]) && $googlerecaptcha[$checking_key]) {
					$post = http_build_query(
					 	array (
					 		'response' => $post['g-recaptcha-response'],
					 		'secret' => $googlerecaptcha['secretkey'],
					 		'remoteip' => $_SERVER['REMOTE_ADDR']
					 	)
					);
					$opts = array('http' => 
						array (
							'method' => 'POST',
							'header' => 'application/x-www-form-urlencoded',
							'content' => $post
						)
					);
					$context = stream_context_create($opts);
					$serverResponse = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
					if (!$serverResponse) {
						$json['errors']['username'] = 'Failed to validate Recaptcha';
						$json['errors']['captch_response'] = 'Failed to validate Recaptcha';
					}
					$result = json_decode($serverResponse);

					if (!$result->success) {
						$json['errors']['username'] = 'Invalid Recaptcha';
						$json['errors']['captch_response'] = 'Invalid Recaptcha';
					}
				}
			}

			$post = $this->input->post(null,true);
			if( count($json['errors']) == 0 ){
				if($this->authentication->login($username, $password)){
					$user_details_array=$this->user->login($username);

					if(!empty($user_details_array['username']) && sha1($password)==$user_details_array['password']){

						if($user_details_array['status']){
							
							if($user_details_array['type'] == 'user' && isset($post['type']) && $post['type'] == 'user' ){
								$this->user->update_user_login($user_details_array['id']);
								$this->session->set_userdata(array('user'=>$user_details_array));
								$json['redirect'] = base_url('usercontrol/dashboard');

							}else if($user_details_array['type'] == 'admin' && isset($post['type']) && $post['type'] == 'admin' ){
		                        $this->user->update_user_login($user_details_array['id']);
		                        $this->session->set_userdata(array('administrator'=>$user_details_array));
		                        $json['redirect'] = base_url('admincontrol/dashboard');
		                    }else if($user_details_array['type'] == 'client' && !isset($post['type'])){
		                        $this->user->update_user_login($user_details_array['id']);
		                        $this->session->set_userdata(array('client'=>$user_details_array));
		                        $l = $this->session->userdata('login_data');
		                        if($l['refid'] && $l['product_slug'] && $l['user_id']){
									$json['redirect'] = base_url('product/payment/'. $l['product_slug'].'/'.$l['user_id']);
								}else if($this->session->userdata('refer_id')){
									$json['redirect'] = base_url('store/'. base64_encode($this->session->userdata('refer_id')));
								}else{
									$json['redirect'] = base_url('store/profile/');
								}
		                    }else {
								$json['errors']['username'] = __('user.invalid_valid_user');
							}
						} else{
							$json['errors']['username'] = __('user.account_block_message');		
						}
					}
				} else {
					$json['errors']['username'] = __('user.invalid_credentials');
				}
			}
		}
		else if ($action == 'register') {
			$refid = isset($post['refid']) ? $post['refid'] : '';
			$post['affiliate_id'] = !empty($refid) ? base64_decode($refid) : 0;
			if($this->userdetails()){
				$json['redirect'] = base_url('usercontrol/dashboard');
			} else {

				$this->load->library('form_validation');
				$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
				$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
				$this->form_validation->set_rules('username', 'Username', 'required|trim');
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
				$this->form_validation->set_rules('terms', 'Terms and Condition', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
                $this->form_validation->set_rules('address', 'Address', 'required|trim|xss_clean', array('required' => '%s is required'));
                $this->form_validation->set_rules('state', 'State', 'required', array('required' => '%s is required'));
                $this->form_validation->set_rules('paypal_email', 'Payal Email', 'required|valid_email|xss_clean', array('required' => '%s is required'));
                $this->form_validation->set_rules('phone_number', 'Phone Number', 'required|regex_match[/^[0-9]{10}$/]', array('required' => '%s is required'));
				$this->form_validation->set_rules('alternate_phone_number', 'Alternate Phone Number', 'required|regex_match[/^[0-9]{10}$/]', array('required' => '%s is required'));
				if ($this->form_validation->run() == FALSE) {
					$json['errors'] = $this->form_validation->error_array();
				} else {
					$checkEmail = $this->db->query("SELECT id FROM users WHERE email like ". $this->db->escape($this->input->post('email',true)) ." ")->num_rows();
					if($checkEmail > 0){ $json['errors']['email'] = "Email Already Exist"; }
					$checkUsername = $this->db->query("SELECT id FROM users WHERE username like ". $this->db->escape($this->input->post('username',true)) ." ")->num_rows();
					if($checkUsername > 0){ $json['errors']['username'] = "Username Already Exist"; }

					if(!isset($post['terms'])){
						$json['warning'] = __('user.accept_our_affiliate_policy');
					}

					if(!isset($json['errors'])){	
						$user_type = 'user';
						$geo = $this->ip_info();
						
						
						$refid = !empty($refid) ? base64_decode($refid) : 0;
						$commition_setting = $this->Product_model->getSettings('referlevel');

						$disabled_for = json_decode( (isset($commition_setting['disabled_for']) ? $commition_setting['disabled_for'] : '[]'),1); 
						if((int)$commition_setting['status'] == 0){ $refid  = 0; }
						else if((int)$commition_setting['status'] == 2 && in_array($refid, $disabled_for)){ $refid = 0; }

						$data = $this->user->insert(array(
							'firstname'                 => $this->input->post('firstname',true),
							'lastname'                  => $this->input->post('lastname',true),
							'email'                     => $this->input->post('email',true),
							'username'                  => $this->input->post('username',true),
							'password'                  => sha1($this->input->post('password',true)),
							'refid'                     => $refid,
							'type'                      => $user_type,
                            //'Country'                   => (int)$geo['id'],
							'Country'                   => $this->input->post('country',true),
							'City'                      => (string)$geo['city'],
							'phone'                     => $this->input->post('phone_number',true),
							'twaddress'                 => $this->input->post('address',true),
							'address1'                  => '',
							'address2'                  => '',
							'ucity'                     => '',
							'ucountry'                  => '',
							'state'                     => $this->input->post('state',true),
							'uzip'                      => '',
							'avatar'                    => '',
							'online'                    => '0',
							'unique_url'                => '',
							'bitly_unique_url'          => '',
							'created_at'                => date("Y-m-d H:i:s"),
							'updated_at'                => date("Y-m-d H:i:s"),
							'google_id'                 => '',
							'facebook_id'               => '',
							'twitter_id'                => '',
							'umode'                     => '',
							'PhoneNumber'               => $this->input->post('alternate_phone_number',true),
							'Addressone'                => '',
							'Addresstwo'                => '',
							'StateProvince'             => '',
							'Zip'                       => '',
							'f_link'                    => '',
							't_link'                    => '',
							'l_link'                    => '',
							'product_commission'        => '0',
							'affiliate_commission'      => '0',
							'product_commission_paid'   => '0',
							'affiliate_commission_paid' => '0',
							'product_total_click'       => '0',
							'product_total_sale'        => '0',
							'affiliate_total_click'     => '0',
							'sale_commission'           => '0',
							'sale_commission_paid'      => '0',
							'status'                    => '1'
						));

						$this->db->insert("paypal_accounts", array(
                            'paypal_email' => $this->input->post('paypal_email',true),
                            'user_id' => $data,
                        ));
						$post['refid'] = !empty($refid) ? base64_decode($refid) : 0;
						if(!empty($data) && $user_type == 'user'){
							$notificationData = array(
								'notification_url'          => '/userslist/'.$data,
								'notification_type'         =>  'user',
								'notification_title'        =>  __('user.new_user_registration'),
								'notification_viewfor'      =>  'admin',
								'notification_actionID'     =>  $data,
								'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' register as a '. $this->input->post("affliate_type",true) . ' on affiliate Program on '.date('Y-m-d H:i:s'),
								'notification_is_read'      =>  '0',
								'notification_created_date' =>  date('Y-m-d H:i:s'),
								'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
							);
							$this->insertnotification($notificationData);
							if ($post['affiliate_id'] > 0) {
								$notificationData = array(
									'notification_url'          => '/managereferenceusers',
									'notification_type'         =>  'user',
									'notification_title'        =>  __('user.new_user_registration_under_your'),
									'notification_viewfor'      =>  'user',
									'notification_view_user_id' =>  $post['affiliate_id'],
									'notification_actionID'     =>  $data,
									'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' has been register under you on '.date('Y-m-d H:i:s'),
									'notification_is_read'      =>  '0',
									'notification_created_date' =>  date('Y-m-d H:i:s'),
									'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
								);
								$this->insertnotification($notificationData);
							}
							$json['success']  =  "You've Successfully registered";
		                    $user_details_array=$this->user->login($this->input->post('username',true));
		                    $this->load->model('Mail_model');
		                    
		                    $this->user->update_user_login($user_details_array['id']);
							$this->Mail_model->send_register_mail($post,__('user.welcome_to_new_user_registration'));
		                    if ($user_type == 'user') {
		                    	$this->session->set_userdata(array('user'=>$user_details_array));
		                    	$json['redirect'] = base_url('usercontrol/dashboard');
		                    } else {
		                    	$this->session->set_userdata(array('client'=>$user_details_array));
		                    	$json['redirect'] = base_url('clientcontrol/dashboard');
		                    }
						}
						/*else if(!empty($data) && $user_type == 'client'){
						
							$this->session->set_flashdata('success', __('user.youve_successfully_registered'));
							$user_details_array=$this->user->login($this->input->post('username',true));
							$this->session->set_userdata(array('client'=>$user_details_array));
							
							$notificationData = array(
								'notification_url'          => '/listclients/'.$data,
								'notification_type'         =>  'client',
								'notification_title'        =>  __('user.new_client_registration'),
								'notification_viewfor'      =>  'admin',
								'notification_actionID'     =>  $data,
								'notification_description'  =>  $this->input->post('firstname',true).' '.$this->input->post('lastname',true).' register as a client on affiliate Program on '.date('Y-m-d H:i:s'),
								'notification_is_read'      =>  '0',
								'notification_created_date' =>  date('Y-m-d H:i:s'),
								'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
							);       
							$this->insertnotification($notificationData);
							
							$this->load->model('Mail_model');
							$this->Mail_model->send_register_mail($post,__('user.welcome_to_new_client_registration'));
							$l = $this->session->userdata('login_data');
							if($l['refid'] && $l['product_slug'] && $l['user_id']){
								$json['redirect'] = base_url('product/payment/'. $l['product_slug'].'/'.$l['user_id']);
							}else{	
								$json['redirect'] = base_url('clientcontrol/dashboard/');
							}
						}*/
					}
				}
			}
		}
		else if ($action == 'forget') {
			$email = $this->input->post('email',true);
			$data = $this->db->query("SELECT * FROM users WHERE email like '{$email}' ")->row();
			if ($data) {
				$token = md5(uniqid(rand(), true));
				$resetlink = base_url('resetpassword/'. $token);
				
				$this->db->query("DELETE  FROM password_resets WHERE email like '{$email}' ");
				$this->db->query("INSERT INTO password_resets SET 
					email = '{$email}',
					token = '{$token}'
				");
				$this->load->model('Mail_model');
				$this->Mail_model->send_forget_mail($data, $resetlink);
				$json['success'] = __('user.password_reset_instructions_will_be_sent_to_your_registered_email_address');
			}
			else
			{
				$json['errors']['email'] = __('user.email_address_not_found');
			}
		}
		echo json_encode($json);
	}
	/*public function register($refid = null) {
		 	
		if($this->userdetails()){
			redirect('usercontrol/dashboard', 'refresh');
		}
		
		$this->session->set_userdata(array(
			'login_data'=> array(
				'refid' => $refid,
			),
		));
		$data['screen'] = 'register';
		$data['refid'] = $refid;
		$data['title'] = __('user.user_login_page');
		$data['setting'] = $this->Product_model->getSettings('login');
		$this->load->view('usercontrol/login/login', $data);
	}*/
	
	public function insertnotification($postData = null){
		if(!empty($postData)){
			$data['custom'] = $this->Product_model->create_data('notification', $postData);
		}
	}
	public function changePassword(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$post = $this->input->post(null,true);

		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('old_pass', __('user.old_password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('password', __('user.new_password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
			} else {
				$admin = $this->db->from('users')->where('id',$userdetails['id'])->get()->row_array();
				if($admin['password'] == sha1($post['old_pass'])){
					$res = array('password'=>sha1($post['password']));
					$this->db->where('id',$admin['id']);
					$this->db->update('users',$res);
					$this->session->set_flashdata(array('flash' => array('success' => __('user.user_profile_updated_successfully'))));
					redirect('usercontrol/changePassword', 'refresh');
				}else{
					$this->session->set_flashdata(array('flash' => array('error' => __('user.old_password_not_matched'))));
					redirect('usercontrol/changePassword');
				}
			}
		}
		
		$data['title'] = __('user.change_password');
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/change-password', $data);
		$this->load->view('usercontrol/includes/footer', $data);
		
	}
	
	public function dashboardlist(){
		$data['title'] = __('user.user_dashboard');
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/dashboardlist', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	
	
	public function dashboard(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$post = $this->input->post(null,true);

		if (isset($post['renderChart'])) {
			if (isset($post['selectedyear'])) {
				$data = $this->Order_model->getSaleChart(array('affiliate_id' => $userdetails['id'],'selectedyear' => $post['selectedyear']),$post['renderChart']);
			}else{
				$data = $this->Order_model->getSaleChart(array('affiliate_id' => $userdetails['id']),$post['renderChart']);
			}

			echo json_encode($data);die;
		}
		
		
		$this->load->model("Form_model");
		$data['total'] = $this->Product_model->getallPercentageByallorders($userdetails['id']);
		//$data['totalsales'] = $this->Product_model->getallPercentageByallsales($userdetails['id']);
		$data['ordercount'] = $this->Product_model->getallorders($userdetails['id']);
		$data['salescount']    = $this->Order_model->getCount(array( 'affiliate_id' => $userdetails['id'] ));
		$data['product_count'] = $this->Product_model->getAllProductrecord();
		$data['user_count'] = $this->Product_model->getrefUsers($userdetails['id']);
		$data['formcount'] = $this->Form_model->formcount();
		$data['title'] = __('user.user_dashboard');
        $this->load->model('Wallet_model');
		$data['totals'] = $this->Wallet_model->getTotals(array('user_id' => $userdetails['id']),true);
		$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
        $data['notifications'] = $this->user->getAllNotification($userdetails['id']);
		$data['populer_users'] = $this->Product_model->getPopulerUsers(array("limit" => 10));

		$data['totals']['full_hold_orders'] = $data['totals']['integration']['hold_orders'];

        $this->load->model('IntegrationModel');
        $data['tools'] = $this->IntegrationModel->getProgramTools([
			'user_id' => $userdetails['id'],
			'status' => 1,
			'redirectLocation' => 1,
			'restrict' => $userdetails['id'],
		]);

        $data['form_default_commission'] = $this->Product_model->getSettings('formsetting');
        $data['default_commition'] =$this->Product_model->getSettings('productsetting');

       	$products = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type']);
		$forms = $this->Form_model->getForms($userdetails['id']);

 		foreach ($products as $key => $value) {
 			$products[$key]['is_product'] = 1;
 		}

 		foreach ($forms as $key => $value) {
 			$forms[$key]['coupon_name'] = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
 			$forms[$key]['public_page'] = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
 			$forms[$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
 			if($value['coupon']){
 				$forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
 			}
 			$forms[$key]['seo'] = str_replace('_', ' ', $value['seo']);
 			$forms[$key]['is_form'] = 1;
 			$forms[$key]['product_created_date'] = $value['created_at'];
 		}

 		$data_list = array_merge($products,$forms,$data['tools']);
 		usort($data_list,function($a,$b){
 			$ad = strtotime($a['product_created_date']);
		    $bd = strtotime($b['product_created_date']);
		    return ($ad-$bd);
 		});
 		$data['data_list'] = array_reverse($data_list);
 		


        $data['integration_orders'] = $this->IntegrationModel->getOrders(array(
        	"limit" => 5,
        	'user_id' => $userdetails['id'],
        ));
        
    	$data['buyproductlist'] = $this->Order_model->getOrders($filter);
	    foreach ($data['buyproductlist'] as $key => $value) {
			$p = $this->Order_model->getProducts($value['id'],['refer_id' => $userdetails['id']]);
			$t = $this->Order_model->getTotals($p,array());
			$data['buyproductlist'][$key]['total'] = $t['total']['value'];
		}
		
		

        $referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }

        $data['refer_status'] = $refer_status;
        $data['store'] = $this->Product_model->getSettings('store');
         

        $data['integration_logs']   = $this->IntegrationModel->getLogs(array(
			'page'  => 1,
			'limit' => 5,
			'user_id' => $userdetails['id'],
		))['records'];
        
		
        $this->load->model('Report_model');
		$data['live_window'] = $this->Report_model->combine_window($data);


		$data['months'] = array('All','01','02','03','04','05','06','07','08','09','10','11','12');
		$data['years'] = array('All',date("Y",strtotime("-3 year")),date("Y",strtotime("-2 year")),date("Y",strtotime("-1 year")),date("Y",strtotime("0 year")));


		$data['totals']['full_digital_orders'] =$this->db->query('SELECT COUNT(op.id) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status > 0 AND op.refer_id ='. (int)$userdetails['id'])->row()->total;
		$data['totals']['external_inte_order'] =$this->db->query('SELECT COUNT(id) as total FROM `integration_orders` WHERE user_id='.  (int)$userdetails['id'])->row()->total;

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
        $this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/index', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function get_integartion_data($return  = false){
		$post = $this->input->post();
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$json = array();

		if($post['integration_data_year'] && $post['integration_data_month']){
			$integration_filters = array(
				'integration_data_year' => $post['integration_data_year'],
				'integration_data_month' => $post['integration_data_month'],
			);
		}else{
			$integration_filters = array();
		}

		$integration_filters['user_id'] = $userdetails['id'];

		$totals = $this->Wallet_model->getTotals($integration_filters, true);
		if($totals){
			$html = '';
			if ($totals['integration']['all'] ==null) {
			    $html .= '<div class="text-center">
			        <img class="img-responsive" src="'. base_url('assets/vertical/assets/images/no-data-2.png') .'" style="margin-top:100px;">
			        <h3 class="m-t-40 text-center text-muted">'. __('admin.no_integration_done_yet') .'</h3>
			    </div>';
			} else {
			    $html .= '<div role="tabpanel" class="tab-pane" id="site-all" style="display: block">
			        <ul class="list-group p-t-10" style="min-height:360px">
			            <li class="list-group-item">
			                '. __( 'admin.total_balance' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. c_format($totals['integration']['balance']) .'        
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_sales' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. c_format($totals['integration']['balance']) .' / '. c_format($totals['integration']['sale']) .'        
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_clicks' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. (int)$totals['integration']['click_count'] .' / '. c_format($totals['integration']['click_amount']) .'
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __('admin.total_actions') .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. (int)$totals['integration']['action_count'] .' / '. c_format($totals['integration']['action_amount']) .'
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_commission' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. c_format($totals['integration']['total_commission']) .' 
			                </span>
			            </li>
			            <li class="list-group-item">
			                '. __( 'admin.total_orders' ) .'
			                <span class="badge badge-light font-14 pull-right">
			                    '. (int)$totals['integration']['total_orders'] .' 
			                </span>
			            </li>
			        </ul>
			    </div>';
			    $index = 0; 
			    foreach ($totals['integration']['all'] as $website => $value) {
			        $html .= '<div role="tabpanel" class="tab-pane" id="site-'. ++$index .'" style="height:360px;display: none;">
			            <ul class="list-group p-t-10" >
			                <li class="list-group-item">
			                    '. __( 'admin.total_balance' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. c_format($value['balance']) .'
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_sales' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. c_format($value['balance']) .' / '. c_format($value['sale']) .'        
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_clicks' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. (int)$value['click_count'] .' / '. c_format($value['click_amount']) .'
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __('admin.total_actions') .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. (int)$value['action_count'] .' / '. c_format($value['action_amount']) .'
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_commission' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. c_format($value['click_amount'] + $value['sale'] + $value['action_amount']) .' 
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    '. __( 'admin.total_orders' ) .'
			                    <span class="badge badge-light font-14 pull-right">
			                        '. (int)$value['total_orders'] .' 
			                    </span>
			                </li>
			                <li class="list-group-item">
			                    <a class="btn btn-lg btn-default btn-success" href="http://'. $website .'" target="_blank">'. __( 'admin.priview_store' ) .'</a>
			                </li>
			            </ul>
			        </div>';
			    }
			}

			$integration_data_selected = 'all';
			if(isset($post['integration_data_selected']) && $post['integration_data_selected'] != '') $integration_data_selected = $post['integration_data_selected'];

			$newHTML = "<div class='p-3'>
                    <select name='integration-chart-type' id='integration-chart-type' class='form-control show-tabs select2-input'>
                        <option value='all' data-id='all' data-website='all'>All</option>";
                        $index = 0;
                        foreach ($totals['integration']['all'] as $website => $value) {
                        	$k = base64_encode($website); 
                            $newHTML .= "<option ". ( $integration_data_selected == $k ? 'selected' : '' ) ." value='". $k ."' data-id='". ++$index ."' data-website='". $website ."' >". $website ."</option>";
                       	}
                    $newHTML .= "</select>
                </div>
                <div class='tab-content'>
                    {$html}
                </div>";


            $json['html'] = $newHTML;


            $type = isset($post['integration_data_website_selected']) && $post['integration_data_website_selected'] != '' ?  $post['integration_data_website_selected'] : 'all';

			if($type == 'all'){
				$data = array(
					'balance'				=>	(float)$totals['integration']['balance'],
					'total_orders_amount'	=>	(float)$totals['integration']['total_orders_amount'],
					'sale'					=>	(float)$totals['integration']['sale'],
					'click_count'			=>	(float)$totals['integration']['click_count'],
					'click_amount'			=>	(float)$totals['integration']['click_amount'],
					'action_count'			=>	(float)$totals['integration']['action_count'],
					'action_amount'			=>	(float)$totals['integration']['action_amount'],
					'total_commission'		=>	(float)$totals['integration']['total_commission'],
					'total_orders'			=>	(float)$totals['integration']['total_orders'],
				);
			}else{
				$integration = $totals['integration']['all'];
				if(isset($integration[$type])){
					$data = array(
						'balance'				=>	isset($integration[$type]['balance']) ? (float)$integration[$type]['balance'] : 0,
						'total_orders_amount'	=>	isset($integration[$type]['total_orders_amount']) ? (float)$integration[$type]['total_orders_amount'] : 0,
						'sale'					=>	isset($integration[$type]['sale']) ? (float)$integration[$type]['sale'] : 0,
						'click_count'			=>	isset($integration[$type]['click_count']) ? (float)$integration[$type]['click_count'] : 0,
						'click_amount'			=>	isset($integration[$type]['click_amount']) ? (float)$integration[$type]['click_amount'] : 0,
						'action_count'			=>	isset($integration[$type]['action_count']) ? (float)$integration[$type]['action_count'] : 0,
						'action_amount'			=>	isset($integration[$type]['action_amount']) ? (float)$integration[$type]['action_amount'] : 0,
						'total_commission'		=>	isset($integration[$type]['total_commission']) ? (float)$integration[$type]['total_commission'] : 0,
						'total_orders'			=>	isset($integration[$type]['total_orders']) ? (float)$integration[$type]['total_orders'] : 0,
					);
				}
			}

			$json['chart'][$post['integration_data_year']] = $data;
		}else{
			$json['html'] = false;
		}



		if($return) return $json;
		echo json_encode($json);die;
	}

	public function logs(){
		$data = array();
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$input = $this->input->post(null,true);
		
		$filter = array(
			'user_id' => $userdetails['id'],
		);

		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		if($input['type'] == 'sale'){
			$data['title'] = "Sales Logs";

			$filter['type'] = "sale_commission";
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'hold_orders'){
			$data['title'] = "Hold Orders Logs";

			$filter['type'] = "sale_commission";
			$filter['status'] = 0;
			 
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'click'){
			$data['title'] = "Clicks Logs";
			$filter['click_log'] = 1;
			$data['data'] = $this->Wallet_model->getTransaction($filter);

			$data['title2'] = "Clicks Logs";
			$record = array();

			$where = ' AND user_id = '. $userdetails['id'];

			$record[] = $this->db->query('SELECT country_code,created_at,ip  as user_ip,commission as pay_commition,"Integration Click" as type FROM integration_clicks_action WHERE is_action=0'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Product Click" as type  FROM product_action WHERE  1'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,pay_commition,"Form Click" as type  FROM form_action WHERE 1'.$where)->result_array();
			$record[] = $this->db->query('SELECT country_code,created_at,user_ip,commission as pay_commition,"Affiliate Click" as type FROM affiliate_action WHERE 1'.$where)->result_array();

			$_record = array();
			foreach ($record as $key => $re) {
				foreach ($re as $_key => $value) {
					$_record[] = array(
						'created_at' => $value['created_at'],
						'comment' => 'Click from ip_message ',
						'status' => $value['type'],
						'country_code' => $value['country_code'],
						'user_ip' => $value['user_ip'],
					);
				}
			}

			usort($_record, array('Admincontrol', 'date_compare') ); 
			$data['data2'] = $_record;
		}
		else if($input['type'] == 'orders'){
			$order_status = $this->Order_model->status;
			$data['title'] = "Digital Orders";
			$record = $this->db->query('SELECT o.* FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status > 0 AND op.refer_id='. (int)$userdetails['id'])->result_array();

			$_record = array();
			foreach ($record as $_key => $value) {
				$_record[] = array(
					'created_at'   => $value['created_at'],
					'comment'      => 'Order from ip_message ',
					'status'       => $order_status[$value['status']],
					'country_code' => $value['country_code'],
					'user_ip'      => $value['ip'],
					'amount'       => $value['total'],
				);
			}

			$data['data'] = $_record;

		}
		else if($input['type'] == 'ex_orders'){
			$data['title'] = "External Orders";
			$record = $this->db->query('SELECT * FROM `integration_orders` WHERE user_id='. (int)$userdetails['id'])->result_array();
 
			$_record = array();
			foreach ($record as $_key => $value) {
				$_record[] = array(
					'created_at'   => $value['created_at'],
					'comment'      => 'Order from ip_message ',
					'status'       => 'Complete',
					'country_code' => $value['country_code'],
					'user_ip'      => $value['ip'],
					'amount'       => $value['total'],
				);
			}

			$data['data'] = $_record;

		}
		else if($input['type'] == 'action'){
			$data['title'] = "Actions Logs";
			
			$filter['type'] = "external_click_commission";
			$filter['is_action'] = 1;
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		else if($input['type'] == 'hold_actions'){
			$data['title'] = "Hold Action Logs";
			
			$filter['type'] = "external_click_commission";
			$filter['is_action'] = 1;
			$filter['status'] = 0;
			$data['data'] = $this->Wallet_model->getTransaction($filter);
		}
		
		$data['html'] = $this->load->view("common/log_model",$data,true);

		echo json_encode($data);die;
	}
	
	public function logout(){
		$this->session->unset_userdata('user');
		redirect('/login');
	}
	
	public function deleteUser($id){

		$data['users'] = $this->admin_model->deleteUser($id);
		$this->session->set_flashdata('success', __('user.user_deleted_successfullly'));
		redirect('usercontrol/manageUsers');
	}
	
	public function addComission(){
		$post = $this->input->post(null,true);
		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('buyid', 'BuyId', 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('amount', 'Amount', 'required|trim', array('required' => '%s is required.')
			);
			$this->form_validation->set_rules('qty', 'Qty', 'required|trim', array('required' => '%s is required.')
			);
			
			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
				} else {
				$db = new MY_Controller();
				$userdetails=$db->userdetails();
				$kirim = array('RefiD'=>$userdetails['refid'],'buyiD'=>$post['buyid'],'userID'=>$userdetails['id'],'worlbit_qty'=>$post['qty'],'Amount'=>$post['amount']);
				
				$res = $this->commisioninfo->set_commission($kirim);
				$this->session->set_flashdata(array('flash' => array('success' => __('user.comission_added_successfully!'))));
				redirect('usercontrol/addComission', 'refresh');
			}
		}
		$data['title'] = 'Add Comission';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/addComission', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}
	
	public function addUser(){
		$post = $this->input->post(null,true);
		if(isset($post) && !empty($post)){
			$this->form_validation->set_rules('firstname', __('user.first_name'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('lastname', __('user.last_name'), 'required|trim', array('required' => '%s is required.'));
			$this->form_validation->set_rules('username', __('user.username'), 'required|trim|is_unique[users.username]', array('required' => '%s is required'));
			$this->form_validation->set_rules('email', __('user.email'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('password', __('user.password'), 'required|trim', array('required' => '%s is required'));
			$this->form_validation->set_rules('conf_password', __('user.confirm_password'), 'required|trim|matches[password]', array('required' => '%s is required'));
			
			if ($this->form_validation->run() == FALSE) {
				$data['validate_err'] = validation_errors();
				} else {
				
				$res = array('firstname'=>$post['firstname'],'lastname'=>$post['lastname'],'email'=>$post['email'],'username'=>$post['username'],'password'=>sha1($post['password']),'updated_at'=>date('Y-m-d H:i:s'));
				
				$this->db->insert('users',$res);
				$this->session->set_flashdata(array('flash' => array('success' => __('user.user_added_successfully'))));
				redirect('usercontrol/manageUsers', 'refresh');
			}
		}
		$data['title'] = 'Add User';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/addUser', $data);
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function editUser($id){
		$post = $this->input->post(null,true);
		if(isset($post['id']) && !empty($post['id'])){
			$res = array('firstname'=>$post['firstname'],'lastname'=>$post['lastname'],'updated_at'=>date('Y-m-d H:i:s'));
			$this->db->where('id',$post['id']);
			$this->db->update('users',$res);
			$this->session->set_flashdata(array('flash' => array('success' => __('user.user_profile_updated_successfully'))));
			redirect('usercontrol/manageUsers', 'refresh');
		}
		
		
		$data['users'] = $this->admin_model->getUsers($id);
		$data['title'] = 'Edit User';
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/edit-user', $data);
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function messages(){
		$data['title'] = 'Message';
		$data['users'] = $this->admin_model->getUsers($id=null);
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/dashboard/message', $data);
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function chatmessage()
	{
		$this->load->helper('smiley');
		$data['title'] = 'Message';
		$data['users'] = $this->admin_model->getUsers($id=null);
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('chat', $data);
		$this->load->view('usercontrol/includes/footer', $data); 		
	}
	public function google_login()
	{
		$get = $this->input->get(null,true);
		$clientId = '163214076002-9o582d2urnpc10nebsd032sgadhcgvmf.apps.googleusercontent.com'; //Google client ID
		$clientSecret = 'Ent8s37alsTYf6Ai8Z7y0Z6l'; //Google client secret
		$redirectURL = base_url() . 'admin/google_login/';
		
		//Call Google API
		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($clientId);
		$gClient->setClientSecret($clientSecret);
		$gClient->setRedirectUri($redirectURL);
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		
		if(isset($get['code']))
		{
			$gClient->authenticate($get['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
		}
		
		if (isset($_SESSION['token']))
		{
			$gClient->setAccessToken($_SESSION['token']);
		}
		
		if ($gClient->getAccessToken()) {
			$userProfile = $google_oauthV2->userinfo->get();
			echo "<pre>";
			print_r($userProfile);
			die;
		}
		else
		{
			$url = $gClient->createAuthUrl();
			header("Location: $url");
			exit;
		}
	}

	public function store_orders($page = 1){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$data['status'] = $this->Order_model->status;

		unset($data['status']['0']);

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			$post = $this->input->post(null,true);
			$filter = array(
				'limit' => 100,
				'page' => $page,
				'user_id' => $userdetails['id'],
				'o_status' => 1,
			);

			if(isset($post['filter_status']) && $post['filter_status'] != ''){
				$filter['o_status'] = $this->input->post('filter_status',true);
			}

			list($data['orders'],$total) = $this->Order_model->getAllOrders($filter);

			$data['start_from'] = (($page-1) * $filter['limit'])+1;
			$json['html'] = $this->load->view("usercontrol/store/order_list.php",$data,true);



			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/store_orders/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);

			$json['pagination'] = $this->pagination->create_links();

			echo json_encode($json);die;
		}
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/store/orders', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

    public function store_logs($page = 0){

    	$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			$page = max((int)$page,1);
			
			$filter = array(
				'limit'   => 100,
				'page'    => $page,
				'user_id' => $userdetails['id'],
			);
			$data['userdetails'] = $userdetails;

			list($data['clicks'],$total) = $this->Order_model->getAllClickLogs($filter);
			$data['start_from'] = (($page-1) * $filter['limit'])+1;

			$json['html'] = $this->load->view("usercontrol/store/log_list.php",$data,true);

			$this->load->library('pagination');
			$config['base_url'] = base_url('usercontrol/store_logs/');
			$config['per_page'] = $filter['limit'];
			$config['total_rows'] = $total;
			$config['use_page_numbers'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			$this->pagination->initialize($config);


			$json['pagination'] = $this->pagination->create_links();
			echo json_encode($json);die;
		}
    
        $this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/store/logs', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

    public function store_markettools(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)) redirect('login');

		$this->load->model('Form_model');
        $this->load->model('Report_model');
        $this->load->model('Wallet_model');
        $this->load->model('IntegrationModel');
        
		$data['form_default_commission'] = $this->Product_model->getSettings('formsetting');
		$data['default_commition']       = $this->Product_model->getSettings('productsetting');

        $data['tools'] = $this->IntegrationModel->getProgramTools([
			'user_id'          => $userdetails['id'],
			'status'           => 1,
			'redirectLocation' => 1,
			'restrict'         => $userdetails['id'],
		]);

       	$products = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type']);
		$forms = $this->Form_model->getForms($userdetails['id']);
 		foreach ($products as $key => $value) { $products[$key]['is_product'] = 1; }
 		foreach ($forms as $key => $value) {
			$forms[$key]['coupon_name']          = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
			$forms[$key]['public_page']          = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
			$forms[$key]['count_coupon']         = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
			$forms[$key]['seo']                  = str_replace('_', ' ', $value['seo']);
			$forms[$key]['is_form']              = 1;
			$forms[$key]['product_created_date'] = $value['created_at'];
			$forms[$key]['fevi_icon'] = $value['fevi_icon'] ? 'assets/images/form/favi/'.$value['fevi_icon'] : 'assets/images/no_image_available.png';

			if($value['coupon']){
 				$forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
 			}
 		}

 		$data_list = array_merge($products,$forms,$data['tools']);
 		usort($data_list,function($a,$b){
 			$ad = strtotime($a['product_created_date']);
		    $bd = strtotime($b['product_created_date']);
		    return ($ad-$bd);
 		});
 		$data['data_list'] = array_reverse($data_list);

		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/store/markettools', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function listproduct(){
		$userdetails = $this->userdetails();
		
		if(empty($userdetails)){ redirect('/login'); }

		$store_setting =$this->Product_model->getSettings('store');
		if(!$store_setting['status']){
			redirect('/usercontrol/dashboard');			
		}
		$this->load->model('Form_model');
		$data['totals'] = $this->Wallet_model->getTotals(array('user_id' => $userdetails['id']), true);
		$data['productlist'] = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type']);
       	$data['default_commition'] =$this->Product_model->getSettings('productsetting');
       	//$data['client_count'] =$this->db->query('SELECT count(*) as total FROM users WHERE  type="client"')->row()->total;
       	$data['ordercount'] =$this->db->query('SELECT COUNT(op.id) as total FROM `order_products` op LEFT JOIN `order` as o ON o.id = op.order_id WHERE o.status > 0 AND op.`refer_id` = '. (int)$userdetails['id'] )->row()->total;
       	
		/*$data['form_default_commission'] = $this->Product_model->getSettings('formsetting');

       	$products = $this->Product_model->getAllProduct($userdetails['id'], $userdetails['type']);
		$forms = $this->Form_model->getForms($userdetails['id']);

 		foreach ($forms as $key => $value) {
 			$forms[$key]['coupon_name'] = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
 			$forms[$key]['public_page'] = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
 			$forms[$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
 			if($value['coupon']){
 				$forms[$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
 			}
 			$forms[$key]['seo'] = str_replace('_', ' ', $value['seo']);
 			$forms[$key]['is_form'] = 1;
 			$forms[$key]['product_created_date'] = $value['created_at'];
 		}

 		$data_list = array_merge($products,$forms);
 		usort($data_list,function($a,$b){
 			$ad = strtotime($a['product_created_date']);
		    $bd = strtotime($b['product_created_date']);
		    return ($ad-$bd);
 		});
 		$data['data_list'] = array_reverse($data_list);*/


       		
		$data['user'] = $userdetails;
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
        $this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/product/index', $data);	
		$this->load->view('usercontrol/includes/footer', $data);
	}
	public function managereferenceusers(){
		redirect('usercontrol/my_network');
	}

	/*public function managereferenceusers(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }
        if(!$refer_status){ show_404(); }

		$data['refUsers'] = $this->Product_model->getrefUsers($userdetails['id']);

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/users/index', $data);	
		$this->load->view('usercontrol/includes/footer', $data);
	}*/

	public function my_network(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$referlevelSettings = $this->Product_model->getSettings('referlevel');
        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);
        $refer_status = true;
        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
        else if((int)$referlevelSettings['status'] == 2 && in_array($userdetails['id'], $disabled_for)){ $refer_status = false; }
        //if(!$refer_status){ show_404(); }

		//$data['refUsers'] = $this->Product_model->getrefUsers($userdetails['id']);
		$data['userslist'] = $this->Product_model->getAllUsersTreeV3(array(),$userdetails['id']);

		$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
		
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/users/my_network', $data);	
		$this->load->view('usercontrol/includes/footer', $data);
	}
	
	public function addpayment($id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		$post = $this->input->post(null,true);

		if (isset($post['add_paypal'])) {
			$email = $this->input->post('paypal_email',true);
			if ((int)$post['id'] > 0) {
				$this->db->update("paypal_accounts", array(
					'paypal_email' => $email,
					'user_id' => $userdetails['id'],
				),
				array(
					'id' => $post['id']
				));
			}
			else
			{
				$this->db->insert("paypal_accounts", array(
					'paypal_email' => $email,
					'user_id' => $userdetails['id'],
				));
			}
			$this->session->set_flashdata('success', __('user.paypal_account_saved_successfully'));
			redirect('usercontrol/mywallet/#tab-paymentdetails');
		} else if(!empty($post)){
			$this->load->helper(array('form', 'url'));
			
            $this->load->library('form_validation');
			
            $this->form_validation->set_rules('payment_account_number', __('user.account_number'), 'required');
            $this->form_validation->set_rules('payment_account_name', __('user.account_name'), 'required' );
            $this->form_validation->set_rules('payment_ifsc_code', __('user.ifsc_code'), 'required');
			if($this->form_validation->run())
			{
				$errors= array();
				
				$details = array(
					'payment_bank_name'      =>  $this->input->post('payment_bank_name',true),
					'payment_account_number' =>  $this->input->post('payment_account_number',true),
					'payment_account_name'   =>  $this->input->post('payment_account_name',true),
					'payment_ifsc_code'      =>  $this->input->post('payment_ifsc_code',true),
					'payment_status'         =>  1,
					'payment_ipaddress'      =>  $_SERVER['REMOTE_ADDR'],
				);
				if(empty($errors)){
					 
					if( (int)$post['payment_id'] > 0 ){
						$this->session->set_flashdata('success', __('user.payment_updated_successfully'));
						$details['payment_updated_by'] = $userdetails['id'];
						$details['payment_updated_date'] = date('Y-m-d H:i:s');
						$this->Product_model->update_data('payment_detail', $details,array('payment_id' => (int)$post['payment_id']));	
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					}
					else {
						$this->session->set_flashdata('success', __('user.payment_added_successfully'));
						$details['payment_created_by'] = $userdetails['id'];
						$details['payment_created_date'] = date('Y-m-d H:i:s');
						$this->Product_model->create_data('payment_detail', $details);	
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					}
					
				} else {
					if(!empty($id)){
						$this->session->set_flashdata('error', $errors['avatar_error'] );
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					} else {
						$this->session->set_flashdata('error', $errors['avatar_error'] );
						redirect('usercontrol/mywallet/#tab-paymentdetails');
					}
				}
			} else {
				$this->session->set_flashdata('error', __('user.form_validation_error'));
				redirect('usercontrol/addpayment');
			}
		
		} else {
			redirect('usercontrol/mywallet/#tab-paymentdetails');

			/*$data['payment'] 	= $this->Product_model->getPaymentById($id);
			$data['paymentlist'] = $this->Product_model->getAllPayment($userdetails['id']);
			if (isset($data['paymentlist'][0])) {
				$data['paymentlist'] = array(
					'payment_id'             => $data['paymentlist'][0]['payment_id'],
					'payment_bank_name'      => $data['paymentlist'][0]['payment_bank_name'],
					'payment_account_number' => $data['paymentlist'][0]['payment_account_number'],
					'payment_account_name'   => $data['paymentlist'][0]['payment_account_name'],
					'payment_ifsc_code'      => $data['paymentlist'][0]['payment_ifsc_code'],
				);
			} else {
				$data['paymentlist'] = array(
					'payment_id'             => 0,
					'payment_bank_name'      => '',
					'payment_account_number' => '',
					'payment_account_name'   => '',
					'payment_ifsc_code'      => '',
				);
			}

			$data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($userdetails['id']);
			if (isset($data['paypalaccounts'][0])) {
				$data['paypalaccounts'] = array(
					'paypal_email' => $data['paypalaccounts'][0]['paypal_email'],
					'id'           => $data['paypalaccounts'][0]['id'],
				);
			} else {
				$data['paypalaccounts'] = array(
					'paypal_email' => '',
					'id'           => 0,
				);
			}
			
			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
			$this->load->view('usercontrol/payment/add_payment', $data);	
			$this->load->view('usercontrol/includes/footer', $data);*/
			
		}				
	}

	public function generateproductcode($affiliateads_id = null){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		else {
			if($affiliateads_id){
				$data['product_id'] = $affiliateads_id;		
				$data['user_id'] = $userdetails['id'];		
				$data['getProduct'] 	= $this->Product_model->getProductByIdArray($affiliateads_id);
				$this->load->view('usercontrol/product/generatecode', $data);	
			}
		}	
	}

	public function listbuyproduct(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$data['buyproductlist'] = $this->Product_model->getAllBuyProduct($userdetails['id']);
		
		$data['user'] = $userdetails;
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/product/listofallbuyproduct', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}


        
	public function listbuyaffiproduct(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$store_setting = $this->Product_model->getSettings('store');
		if(!$store_setting['status']){ show_404(); }

		$filter = array( 'affiliate_id' => $userdetails['id'] );

		$data['buyproductlist'] = $this->Order_model->getOrders($filter);
		foreach ($data['buyproductlist'] as $key => $value) {
			$p = $this->Order_model->getProducts($value['id'],['refer_id' => $userdetails['id']]);
			$t = $this->Order_model->getTotals($p,array());
			$data['buyproductlist'][$key]['total'] = $t['total']['value'];
		}

		$data['status'] = $this->Order_model->status;
		$data['user'] = $userdetails;

		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/product/listbuyaffiproduct', $data);	
		$this->load->view('usercontrol/includes/footer', $data);	
	}
	public function editProfile(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else { $id =  $userdetails['id']; }

		$this->load->model('PagebuilderModel');
		$this->load->model('User_model');
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
			$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
			$this->form_validation->set_rules('country_id', 'Country', 'required');
			$post = $this->input->post(null,true);

			if($post['password'] != ''){
				$this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required'));
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim', array('required' => '%s is required'));
	            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]', array('required' => '%s is required'));
			}
			
			$json['errors'] = array();

			$register_form = $this->PagebuilderModel->getSettings('registration_builder');
			if($register_form){
				$customField = json_decode($register_form['registration_builder'],1);
				
				foreach ($customField as $_key => $_value) {
					$field_name = 'custom_'. $_value['name'];

					if($_value['required'] == 'true'){
						if(!isset($post[$field_name]) || $post[$field_name] == ''){
							$json['errors'][$field_name] = $_value['label'] ." is required.!";
						}
					}

					if(!isset($json['errors'][$field_name]) && (int)$_value['maxlength'] > 0){
						if(strlen( $post[$field_name] ) > (int)$_value['maxlength']){
							$json['errors'][$field_name] = $_value['label'] ." Maximum length is ". (int)$_value['maxlength'];
						}
					}

					if(!isset($json['errors'][$field_name]) && (int)$_value['minlength'] > 0){
						if(strlen( $post[$field_name] ) > (int)$_value['minlength']){
							$json['errors'][$field_name] = $_value['label'] ." Minimum length is ". (int)$_value['minlength'];
						}
					}

					if(!isset($json['errors'][$field_name]) && $_value['mobile_validation']  == 'true'){
						
						/*if(!preg_match('/^[0-9]{10}+$/', $post[$field_name])){
							$json['errors'][$field_name] = $_value['label'] ." Invalid mobile number ";
						}*/
					}
				}
			}

			if ($this->form_validation->run() == FALSE) {
				$json['errors'] = array_merge($this->form_validation->error_array(), $json['errors']);
			}
			if( count($json['errors']) == 0){
				$checkmail = $this->Product_model->checkmail($this->input->post('email',true),$id);
				$checkuser = $this->Product_model->checkuser($this->input->post('username',true),$id);
				
				if(!empty($checkmail)){ $json['errors']['email'] = "Email Already Exist"; }
				if(!empty($checkuser)){ $json['errors']['username'] = "Username Already Exist"; }


				if(count($json['errors']) == 0){

					$custom_fields = array();
                    foreach ($this->input->post() as $key => $value) {
                    	if(!in_array($key, array('affiliate_id','terms','cpassword','firstname','lastname','email','username','password'))){
                    		$custom_fields[$key] = $value;
                    	}
                    }

                    $userArray = array(
						'firstname'                 => $this->input->post('firstname',true),
						'lastname'                  => $this->input->post('lastname',true),
						'email'                     => $this->input->post('email',true),
						'username'                  => $this->input->post('username',true),
						//'password'                  => sha1($this->input->post('password',true)),
						'twaddress'                 => '',
						'address1'                  => '',
						'address2'                  => '',
						'uzip'                      => '',
						'avatar'                    => '',
						'online'                    => '0',
						'unique_url'                => '',
						'bitly_unique_url'          => '',
						'google_id'                 => '',
						'facebook_id'               => '',
						'twitter_id'                => '',
						'umode'                     => '',
						'PhoneNumber'               => '',
						'Addressone'                => '',
						'Addresstwo'                => '',
						'StateProvince'             => '',
						'Zip'                       => '',
						'f_link'                    => '',
						't_link'                    => '',
						'l_link'                    => '',
						'product_commission'        => '0',
						'affiliate_commission'      => '0',
						'product_commission_paid'   => '0',
						'affiliate_commission_paid' => '0',
						'product_total_click'       => '0',
						'product_total_sale'        => '0',
						'affiliate_total_click'     => '0',
						'sale_commission'           => '0',
						'sale_commission_paid'      => '0',
						'status'                    => '1',
						'ucountry'                    => $this->input->post('country_id',true),
						'Country'                    => $this->input->post('country_id',true),
						'value'                    => json_encode($custom_fields),
					);

					if($post['password'] != ''){
                    	$userArray['password'] = sha1( $post['password'] );
					}

					if(!empty($_FILES['avatar']['name'])){
						$upload_response = $this->upload_photo('avatar','assets/images/users');

						if($upload_response['success']){
							$userArray['avatar'] = $upload_response['upload_data']['file_name'];
						}
					}

					$this->user->update_user($id, $userArray);
					$this->session->set_userdata(array('user'=>$userArray));


					$this->session->set_flashdata('success', 'Profile Updated Successfully');
					$json['location'] = base_url('usercontrol/editProfile/');
				}
			}


			echo json_encode($json);die;
		} else {
			$data['user']  = (array)$this->user->get($id);
			//$data['country'] = $this->Product_model->getcountry();
			$data['countries'] = $this->User_model->getCountries();
			
			$register_form = $this->PagebuilderModel->getSettings('registration_builder');	
			$data['data'] = json_decode($register_form['registration_builder'],1);
			$data['edit_view'] = true;

			
			$data['html_form'] = $this->load->view('auth/user/templates/register_form',$data, true);

			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/includes/sidebar', $data);$this->load->view('usercontrol/includes/topnav', $data);
			$this->load->view('usercontrol/users/edit_profile', $data);	
			$this->load->view('usercontrol/includes/footer', $data);
		}
		
		function getstate($country_id = null) {
			$userdetails = $this->userdetails();
			if(empty($userdetails)){
				redirect('usercontrol');
			}
			else {
				$states = $this->Product_model->getAllstate($country_id);
				echo '<option selected="selected">Select State</option>';
				if(!empty($states)){
					foreach($states as $state){
						echo '<option value="'.$state['name'].'">'.$state['name'].'</option>';
					}
				}
				die;
				
			}
		}
	}

	public function friendly_seo_string($vp_string){
		$vp_string = trim($vp_string);
		$vp_string = html_entity_decode($vp_string);		
		$vp_string = strip_tags($vp_string);
		$vp_string = strtolower($vp_string);		
		$vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
		$vp_string = preg_replace('~ ~', '-', $vp_string);	
		$vp_string = preg_replace('~-+~', '-', $vp_string);
		return $vp_string;
	}

	public function upload_photo($fieldname,$path) {
		
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'png|gif|jpeg|jpg';
		
		$this->load->helper('string');
		$config['file_name']  = random_string('alnum', 32);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		
		if (!$this->upload->do_upload($fieldname)) {
			echo $this->upload->display_errors();
			die;
			$data = array('success' => false, 'msg' => $this->upload->display_errors());
		} else {
			$upload_details = $this->upload->data();
			
			$config1 = array(
				'source_image' => $upload_details['full_path'],
				'new_image' => $path.'/thumb',
				'maintain_ratio' => true,
				'width' => 300,
				'height' => 300
			);
			$this->load->library('image_lib', $config1);
			$this->image_lib->resize();
			$data = array('success' => true, 'upload_data' => $upload_details, 'msg' => "Upload success!");
		}
		return $data;
	}
	
	public function updatenotify($country_id = null) {
		$userdetails = $this->userdetails();
		$post = $this->input->post(null,true);

		if(empty($userdetails)){ redirect('/login'); }
		else {
			if(!empty($post['id'])){
				$noti = $this->db->query("SELECT * FROM notification WHERE notification_id= ". $post['id'])->row();
				
				if($noti->notification_type == 'integration_click'){
					$json['location'] = base_url('integration/click_logs');
				}
				else if($noti->notification_type == 'integration_orders'){
					$json['location'] = base_url('integration/user_orders');
				} else{
					$json['location'] = base_url('usercontrol/'.$noti->notification_url);
				}
				
				$this->Product_model->update_data('notification', array('notification_is_read' => 1),array('notification_id' => $post['id']));
			}
		}

		echo json_encode($json);
	}

	public function getnotificationnew() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotificationnew('user', $userdetails['id']);
			echo trim(count($notifications));
		}
	}

	public function getnotificationall() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotificationall('user', $userdetails['id']);
			echo trim(count($notifications));
		}
	}

    public function delete_image($image_id = null){
        $userdetails = $this->userdetails();
        $post = $this->input->post(null,true);

        if(empty($userdetails)){ redirect('/login'); }
        else {
            if(!empty($post['image_id'])){
                $this->Product_model->deleteImage($post['image_id']);
            }
        }
    }

	public function getnotification() {
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }
		else {
			$notifications = $this->Product_model->getnotification('user', $userdetails['id']);
			if(!empty($notifications)){
				foreach($notifications as $notification){
					if($notification['notification_type'] == 'order'){
						if($notification['notification_view_user_id'] == $userdetails['id']){
							echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
							<div class="notify-icon bg-primary"><i class="mdi mdi-cart-outline"></i></div>
							<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
							</a>';
						}
					}
					
					if($notification['notification_type'] == 'client'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account-circle"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'paymentrequest'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account-circle"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'user'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-account"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'product'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-basket"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'commission'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-basket"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
					if($notification['notification_type'] == 'commissionrequest'){
						echo '<a href="javascript:void(0)" onclick=shownofication('.$notification['notification_id'].',"'.base_url().'usercontrol'.$notification['notification_url'].'") class="dropdown-item notify-item">
						<div class="notify-icon bg-primary"><i class="mdi mdi-cash-usd"></i></div>
						<p class="notify-details"><b>'.$notification['notification_title'].'</b><small class="text-muted">'.$notification['notification_description'].'</small></p>
						</a>';
					}
					
				}
			}
			die;
			
		}
	}

	public function vieworder($order_id){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$this->load->model('Form_model');
		$data['order'] = $this->Order_model->getOrder($order_id);
		$data['products'] = $this->Order_model->getProducts($order_id,['refer_id' => $userdetails['id']]);
		if($data['products']){
			
			$data['affiliateuser'] = $this->Order_model->getAffiliateUser($order_id);
			$data['payment_history'] = $this->Order_model->getHistory($order_id);
			$data['status'] = $this->Order_model->status;
			$data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
			$data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
			
			
			$this->load->view('usercontrol/includes/header', $data);
			$this->load->view('usercontrol/includes/sidebar', $data);
			$this->load->view('usercontrol/includes/topnav', $data);
			$this->load->view('usercontrol/product/vieworder', $data);
			$this->load->view('usercontrol/includes/footer', $data);
		}
		else{
			die("You are not allow to see.. !");
		}
	}
	
	/*public function wallet_withdraw(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		
		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 2,
		);

		$data['request_status'] = $this->Wallet_model->request_status;
		$data['transaction'] = $this->Wallet_model->getTransaction($filter);
		$data['totals'] = $this->Wallet_model->getTotals($filter);
		$data['status'] = $this->Wallet_model->status;
	
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/users/wallet_withdraw', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}*/
	
	public function mywallet(){
		$userdetails = $this->userdetails();
		$get = $this->input->get(null,true);

		if(empty($userdetails)){ redirect('/login'); }
		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 1,
			'parent_id' => 0,
		);

		if ( isset($get['type']) && $get['type'] ) {
			$filter['types'] = $get['type'];
		}

		$data['totals'] = $this->Wallet_model->getTotals(array('user_id' => $userdetails['id']),true);
		//array('user_id' => $userdetails['id']),true

		/*if (isset($_POST['request_payment'])) {
			$json = array();
			$wallet = $this->Wallet_model->getbyId($_POST['request_payment']);
			if($wallet){
				$this->load->model('Mail_model');
				$this->Mail_model->send_wallet_withdrawal_req($wallet);
				
				$this->Wallet_model->changeStatus($_POST['request_payment'],2);
				$json['success'] = __('user.request_send_successfully');
			}
			
			echo json_encode($json);die;
		}*/

		$post = $this->input->post(null,true);
		$get = $this->input->get(null,true);

		if (isset($post['request_payment_all'])) {
			$json = array();
			$wallet = $this->Wallet_model->getallUnpaid($userdetails['id']);

			if($wallet){
				$this->load->model('Mail_model');
				$this->Mail_model->send_wallet_withdrawal_req($data['totals']['wallet_unpaid_amount'], $userdetails);
				foreach ($wallet as $key => $value) {
					$this->Wallet_model->changeStatus($value['id'],2);
				}

				$json['success'] = __('user.request_send_successfully');
			}
			
			echo json_encode($json);die;
		}

		
		$filter['sortBy'] = isset($get['sortby']) ? $get['sortby'] : '';
		$filter['orderBy'] = isset($get['order']) ? $get['order'] : '';

		$data['request_status'] = $this->Wallet_model->request_status;
		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		$data['transaction'] = $this->Wallet_model->getTransaction($filter);
		
		$data['refer_total'] = $this->Product_model->getReferalTotals($userdetails['id']);
		$data['site_setting'] = $this->Product_model->getSettings('site');


		/*My Payout*/
		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 2,
		);
		$data['payout_transaction'] = $this->Wallet_model->getTransaction($filter);


		/* Add Payout*/
		$data['paymentlist'] = $this->Product_model->getAllPayment($userdetails['id']);
		if (isset($data['paymentlist'][0])) {
			$data['paymentlist'] = array(
				'payment_id'             => $data['paymentlist'][0]['payment_id'],
				'payment_bank_name'      => $data['paymentlist'][0]['payment_bank_name'],
				'payment_account_number' => $data['paymentlist'][0]['payment_account_number'],
				'payment_account_name'   => $data['paymentlist'][0]['payment_account_name'],
				'payment_ifsc_code'      => $data['paymentlist'][0]['payment_ifsc_code'],
			);
		} else {
			$data['paymentlist'] = array(
				'payment_id'             => 0,
				'payment_bank_name'      => '',
				'payment_account_number' => '',
				'payment_account_name'   => '',
				'payment_ifsc_code'      => '',
			);
		}

		$data['paypalaccounts'] = $this->Product_model->getPaypalAccounts($userdetails['id']);
		if (isset($data['paypalaccounts'][0])) {
			$data['paypalaccounts'] = array(
				'paypal_email' => $data['paypalaccounts'][0]['paypal_email'],
				'id'           => $data['paypalaccounts'][0]['id'],
			);
		} else {
			$data['paypalaccounts'] = array(
				'paypal_email' => '',
				'id'           => 0,
			);
		}

		$data['table'] = $this->load->view("usercontrol/users/parts/wallet_tr", $data, true);
	
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/users/mywallet', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function getRecurringTransaction(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$id = (int)$this->input->post('id');
		$filter = array(
			'user_id' => $userdetails['id'],
			'status_gt' => 1,
			'parent_id' => $id,
		);

		$data['recurring'] = $id;
		$data['request_status'] = $this->Wallet_model->request_status;
		$data['status'] = $this->Wallet_model->status;
		$data['status_icon'] = $this->Wallet_model->status_icon;
		$data['transaction'] = $this->Wallet_model->getTransaction($filter);
		$json['table'] = $this->load->view("usercontrol/users/parts/wallet_tr", $data, true);
	
		echo json_encode($json);
	}

	public function form(){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		$store_setting = $this->Product_model->getSettings('store');
		if(!$store_setting['status']){ show_404(); }
 
		$this->load->model("Form_model");
		$data['forms'] = $this->Form_model->getForms($userdetails['id']);		
 		foreach ($data['forms'] as $key => $value) { 			 
 			$data['forms'][$key]['coupon_name'] = $this->Form_model->getFormCouponname(($value['coupon']) ? $value['coupon'] : 0);
 			$data['forms'][$key]['public_page'] = base_url('form/'.$value['seo'].'/'.base64_encode($this->userdetails()['id']));
 			$data['forms'][$key]['count_coupon'] = $this->Form_model->getFormCouponCount($value['form_id'],$this->userdetails()['id']);
 			$data['forms'][$key]['coupon_code'] = $this->Form_model->getFormCouponCode($value['coupon']);
 			$data['forms'][$key]['seo'] = str_replace('_', ' ', $value['seo']) ;
 		}
		$this->load->view('usercontrol/includes/header', $data);
		$this->load->view('usercontrol/includes/sidebar', $data);
		$this->load->view('usercontrol/includes/topnav', $data);
		$this->load->view('usercontrol/form/index', $data);
		$this->load->view('usercontrol/includes/footer', $data);
	}

	public function generateformcode($form = 0){
		$userdetails = $this->userdetails();
		if(empty($userdetails)){ redirect('/login'); }

		else {
			if($form){
				$data['form_id'] = $form;
				$data['user_id'] = $userdetails['id'];
				$this->load->model("Form_model");
				$data['getForm'] 	= $this->Form_model->getForm($form);
				$this->load->view('usercontrol/form/generatecode', $data);
			}
		}
	}
}