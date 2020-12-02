<?php	
class Mail_model extends MY_Model{
    public $mobile_number = '';

    public function external_order($order_id){
        $this->load->model("IntegrationModel");

        $order = $this->IntegrationModel->getOrder($order_id);
        $data = array(
            'external_website_name' => $order['base_url'],
            'commission'            => c_format($order['commission']),
            'username'              => $order['user_name'],
            'product_ids'           => $order['product_ids'],
            'total'                 => c_format($order['total']),
            'currency'              => $order['currency'],
            'commission_type'       => $order['commission_type'],
            'script_name'           => $order['script_name'],
            'email'                 => $order['email'],
        );

      
        $template = $this->getTemplateByID(11);

        
        $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);
        $to      = $data['email'];
        $template['subject'] = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
        $this->sendMail($to,$template['subject'],$body);
        
        $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
        $to      = $data['email'];
        $template['subject'] = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
        $this->sendMail('admin',$template['subject'],$body);
        
    }

    public function send_forget_mail($user,$resetlink){
        $data = (array)$user;
        $data['reset_link'] = '<a href="' .$resetlink . '">RESET PASSWORD</a>';
        $template = $this->getTemplateByID(3);   

        if($data['type'] == 'client'){
            $body = $this->parseStoreTemplate($template['client_text'],$template['shortcode'],$data);
            $to      = $data['email'];
            $template['subject'] = $this->parseStoreTemplate($template['client_subject'],$template['shortcode'],$data);
            return $this->sendMail($to,$template['subject'],$body, true);
        } 
        else if($data['type'] == 'admin'){
            $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
            $to      = $data['email'];
            $template['subject'] = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
            return $this->sendMail($to,$template['subject'],$body);
        } else{
            $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);         
            $to      = $data['email'];
            $template['subject'] = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
            return $this->sendMail($to,$template['subject'],$body);
        }
    }
    public function send_wallet_withdrawal_req($total, $userdetails){

        $data = array(
            'amount'          => c_format($total),
            'comment'         => $userdetails['firstname']. ' ' . $userdetails['lastname'] .' send a withdrawal request',
            'name'            => $userdetails['firstname']. ' ' . $userdetails['lastname'],
            'user_email'      => $userdetails['email'],
            'commission_type' => '',
        );

        $template = $this->getTemplateByID(4);   
        $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);
        $to      = $data['email'];
        $template['subject'] = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
        $this->sendMail($to,$template['subject'],$body);


        $template = $this->getTemplateByID(4);   
        $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
        $template['subject'] = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
        $this->sendMail('admin',$template['subject'],$body);


        $this->load->model('Product_model');
        $notificationData = array(
            'notification_url'          => '/wallet/withdraw',
            'notification_type'         =>  'wallet',
            'notification_title'        =>  'You made a withdrawal request ',
            'notification_viewfor'      =>  'user',
            'notification_view_user_id' =>  $userdetails['id'],
            'notification_actionID'     =>  0,
            'notification_description'  =>  'You made a withdrawal request ',
            'notification_is_read'      =>  '0',
            'notification_created_date' =>  date('Y-m-d H:i:s'),
            'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
        );
        $this->Product_model->create_data('notification', $notificationData);
        $notificationData = array(
            'notification_url'          => '/wallet/withdraw',
            'notification_type'         =>  'wallet',
            'notification_title'        =>  $userdetails['firstname']. ' ' . $userdetails['lastname']. ' made a withdrawal request',
            'notification_viewfor'      =>  'admin',
            'notification_actionID'     =>  0,
            'notification_description'  =>  $userdetails['firstname']. ' ' . $userdetails['lastname']. ' made a withdrawal request',
            'notification_is_read'      =>  '0',
            'notification_created_date' =>  date('Y-m-d H:i:s'),
            'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
        );
        $this->Product_model->create_data('notification', $notificationData);
        return true;
      
    }

    public function wallet_noti_in_wallet($noti){

        $data = array(
            'amount'          => c_format($noti->amount),
            'comment'         => $noti->comment,
            'name'            => $noti->firstname. ' ' . $noti->lastname,
            'user_email'      => $noti->email,
        );

        $template = $this->getTemplateByID(12);   
        $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);
       
        $template['subject'] = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
        $this->sendMail( $tran->email ,$template['subject'],$body);

        return true;      
    }
    public function send_wallet_withdrawal_status($data){
        //$wallet = (array)$wallet;
        //$status = $this->Wallet_model->status;
        /*$data = array(
            'amount'          => c_format($wallet['amount']),
            'comment'         => $wallet['comment'],
            'name'            => $wallet['name'],
            'user_email'      => $wallet['user_email'],
            'commission_type' => $wallet['type'],
            'new_status'      => $status[$new_status],
        );*/
        $template = $this->getTemplateByID(5);   
        $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);
        $to      = $data['user_email'];
        $template['subject'] = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
        $this->sendMail($to,$template['subject'],$body);

        $template = $this->getTemplateByID(5);   
        $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
        $template['subject'] = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
        $this->sendMail('admin',$template['subject'],$body);
    }
    public function send_test_mail($email){
        $data['mob']       = $this->mobile_number;
        $data['base_url']  = base_url();
        
        $template = $this->load->view('mails/header', $data, true);
        $template .= $this->load->view('mails/test', $data, true);
        $template .= $this->load->view('mails/footer', $data, true);
        $subject = "Email Testing";
        return $this->sendMail($email,$subject,$template);   
    }

    public function affiliate_mail($email,$data){
        $body = $this->parseTemplate($data['message'],'',array());
        
        return $this->sendMail($email,$data['subject'],$body, false, true);
    }
    public function send_store_contact_mail($data){
        $template = $this->getTemplateByID(6); 
        $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
        $_subject = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
        $this->sendMail('admin',$_subject,$body);
        $body = $this->parseStoreTemplate($template['client_text'],$template['shortcode'],$data);
        $to   = $data['email'];
        $template_subject = $this->parseStoreTemplate($template['client_subject'],$template['shortcode'],$data);
        $this->sendMail($to,$template_subject,$body, true);
    }
    public function send_register_mail($user,$subject){
        $data = (array)$user;
        if($data['user_type'] == 'user'){
             $this->load->model('Product_model');
              $setting = $this->Product_model->getSettings('site');
        
        
            $template = $this->getTemplateByID(1);
            $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);
            $to      = $user['email'];
            $template_subject = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
            $this->sendMail($to,$template_subject,$body);
            $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
            $template_subject = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
            $to = $setting['notify_email'];
            $this->sendMail($to,$template_subject,$body);
        } else if($data['user_type'] == 'client'){
            $template = $this->getTemplateByID(2);
            $body = $this->parseStoreTemplate($template['client_text'],$template['shortcode'],$data);
            $to      = $data['email'];
            $template_subject = $this->parseStoreTemplate($template['client_subject'],$template['shortcode'],$data);
            
            $this->sendMail($to,$template_subject,$body, true);
            $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
            $template_subject = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
            $this->sendMail('admin',$template_subject,$body);
        }
        /*if($data['refid'] > 0){
            $this->load->model('user_model', 'user');
            $aff_user = $this->user->get_user_by_id($data['refid']);
            if($aff_user){
                $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);
                $template_subject = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
                $this->sendMail($aff_user['email'],$template_subject,$body);
            }
        }*/
    }

    public function send_register_integration_mail($data,$subject){
        $this->load->model('Product_model');
        $setting = $this->Product_model->getSettings('site');
    
        $template = $this->getTemplateByID(13);
        $body = $this->parseTemplate($template['text'],$template['shortcode'],$data);
        $template_subject = $this->parseTemplate($template['subject'],$template['shortcode'],$data);
        $this->sendMail( $data['email'],$template_subject,$body);

        $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$data);
        $template_subject = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$data);
        $this->sendMail($setting['notify_email'],$template_subject,$body);
        
    }
    public function send_order_mail($order_id){
        $this->load->model('Order_model');
        $data['order']          = $this->Order_model->getOrder($order_id);
        $data['status']         = $this->Order_model->status;
        $data['orderLink']      = base_url('store/vieworder/'.$order_id);
        $data['mob']            = $this->mobile_number;
        $mailData               = $data['order'];
        $mailData['total']      = c_format($mailData['total']);
        $mailData['order_id']   = orderId($mailData['id']);
        $mailData['order_link'] = '<a href="'. $data['orderLink'] .'"> View Order </a>';
        $mailData['status']     = $data['status'][$mailData['status']];
        $comment  = $this->db->query("SELECT * FROM orders_history WHERE order_id = {$order_id} ORDER BY id DESC LIMIT 1")->row_array();
        $mailData['comment']     = $comment['comment'];
        $template = $this->getTemplateByID(7);   
        $body = $this->parseStoreTemplate($template['text'],$template['shortcode'],$mailData);
        $to = $data['order']['email'];
        $template['subject'] = $this->parseStoreTemplate($template['subject'],$template['shortcode'],$mailData);
        return $this->sendMail($to,$template['subject'],$body, true);
       
    }
    public function send_new_order_mail($order_id){
        $this->load->model('Order_model');
        $this->load->model('Product_model');
        $data['order'] = $this->Order_model->getOrder($order_id);
        $data['products'] = $this->Order_model->getProducts($order_id);
        $data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
        $data['payment_history'] = $this->Order_model->getHistory($order_id);
        $data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
        $data['status'] = $this->Order_model->status;
        $data['orderLink'] = base_url('store/vieworder/'.$order_id);
        $data['mob'] = $this->mobile_number;
            
        $data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');

        $mailData  = $data['order'];
        $mailData['order_link']  = '<a href="'. $data['orderLink'] .'"> View Order </a>';
        $mailData['status']  = $data['status'][$mailData['status']];
        $mailData['total']  = c_format($mailData['total']);
        $mailData['order_id']  = orderId($mailData['id']);
            
        $template = $this->getTemplateByID(8);   
        $data['show_commition'] = 1;
        $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$mailData);
        $body .= $this->load->view('form/order_mail',$data, true);
        $_subject = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$mailData);
        $this->sendMail('admin',$_subject,$body);

        /* For Client */
        $data['show_commition'] = 0;
        $body = $this->parseStoreTemplate($template['client_text'],$template['shortcode'],$mailData);
        $body .= $this->load->view('form/order_mail',$data, true);
        $_subject = $this->parseStoreTemplate($template['client_subject'],$template['shortcode'],$mailData);
        $to = $data['order']['email'];
        $this->sendMail($to,$_subject,$body,true);

        $this->send_commition_mail($order_id);
        return true;
    }

    public function send_commition_mail($order_id,$allow_bank_transfer = false)
    {
        $this->load->model('Order_model');
        $this->load->model('Product_model');
        $data['order'] = $this->Order_model->getOrder($order_id);
        $data['products'] = $this->Order_model->getProducts($order_id);
        $data['totals'] = $this->Order_model->getTotals($data['products'],$data['order']);
        $data['payment_history'] = $this->Order_model->getHistory($order_id);
        $data['order_history'] = $this->Order_model->getHistory($order_id, 'order');
        $data['status'] = $this->Order_model->status;
        $data['orderLink'] = base_url('store/vieworder/'.$order_id);
        $data['mob'] = $this->mobile_number;

        $data['paymentsetting'] = $this->Product_model->getSettings('paymentsetting');

        $mailData  = $data['order'];
        $mailData['order_link']  = '<a href="'. $data['orderLink'] .'"> View Order </a>';
        $mailData['status']  = $data['status'][$mailData['status']];
        $mailData['total']  = c_format($mailData['total']);
        $mailData['order_id']  = orderId($mailData['id']);

        if($data['order']['payment_method'] != 'bank_transfer' || $allow_bank_transfer){
            $_getAffiliateUser = $this->Order_model->getAffiliateUser($order_id);
            if($_getAffiliateUser){
                foreach ($_getAffiliateUser as $key => $getAffiliateUser) {
                    $to = $getAffiliateUser['email'];
                    foreach ($getAffiliateUser as $key => $value) {
                        $mailData['affiliate_'. $key] = $value;
                    }

                    $template = $this->getTemplateByID(8);

                    $mailData['commission_type'] = $getAffiliateUser['commission_type'];
                    $mailData['product_name'] = $getAffiliateUser['product_name'];
                    $mailData['product_description'] = $getAffiliateUser['product_short_description'];
                    $mailData['commission'] = c_format($getAffiliateUser['commission']);

                    $body = $this->parseTemplate($template['text'],$template['shortcode'],$mailData);
                    $template['subject'] = $this->parseTemplate($template['subject'],$template['shortcode'],$mailData);
                    $this->sendMail($to,$template['subject'],$body);
                }
            }
        }

        return true;
    }

    public function market_click_notification($user_id,$affiliate_id,$affiliateads_type,$affiliate_commission){
        $this->load->model('user_model', 'user');
        $user = $this->user->get_user_by_id($user_id);
        $data['affiliateads_type']    = $affiliateads_type;
        $data['affiliate_commission'] = $affiliate_commission;
        $data['user']                 = $user;
        $data['mob']                  = $this->mobile_number;
        $data['base_url']             = base_url();
        $setting = $this->Product_model->getSettings('site');
        $admin = $setting['notify_email'];
        $this->load->model('Product_model');
        $notificationData = array(
            'notification_url'          => '/mywallet',
            'notification_type'         =>  'market_click',
            'notification_title'        =>  "You got commition from market {$affiliateads_type} click",
            'notification_viewfor'      =>  'user',
            'notification_view_user_id' =>  $user['id'],
            'notification_actionID'     =>  $affiliate_id,
            'notification_description'  =>  "You got commition from market {$affiliateads_type} click",
            'notification_is_read'      =>  '0',
            'notification_created_date' =>  date('Y-m-d H:i:s'),
            'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
        );
        $this->Product_model->create_data('notification', $notificationData);
        $notificationData = array(
            'notification_url'          => '/mywallet',
            'notification_type'         =>  'market_click',
            'notification_title'        =>  $user['firstname'] ." ". $user['lastname'] ." got commition from market {$affiliateads_type} click",
            'notification_viewfor'      =>  'admin',
            'notification_actionID'     =>  $affiliate_id,
            'notification_description'  =>  $user['firstname'] ." ". $user['lastname'] ." got commition from market {$affiliateads_type} click",
            'notification_is_read'      =>  '0',
            'notification_created_date' =>  date('Y-m-d H:i:s'),
            'notification_ipaddress'    =>  $_SERVER['REMOTE_ADDR']
        );
        $this->Product_model->create_data('notification', $notificationData);

        $mailData = $data['user'];
        $mailData['affiliateads_type'] = $data['affiliateads_type'];
        $mailData['affiliate_commission'] = $data['affiliate_commission'];
        $template = $this->getTemplateByID(10);
        $body = $this->parseTemplate($template['text'],$template['shortcode'],$mailData);
        $template['subject'] = $this->parseTemplate($template['subject'],$template['shortcode'],$mailData);
        $this->sendMail($to,$template['subject'],$body);

        $template = $this->getTemplateByID(10);
        $body = $this->parseTemplate($template['admin_text'],$template['shortcode'],$mailData);
        $template['subject'] = $this->parseTemplate($template['admin_subject'],$template['shortcode'],$mailData);
        $this->sendMail('admin',$template['subject'],$body);
        return true;
    }
    private function sendMail($to, $subject, $htmlContent, $is_store = false, $allow_bootstrap = false){
        $this->load->library('email');
        $setting = $this->Product_model->getSettings('email');
        if($to == 'admin'){
            $to = $setting['from_email'];
        }
        //$to = 'jaydeepakbari@gmail.com';
     
        $config = array(
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
        );

        if($allow_bootstrap){
            $data['bootstrap_cdn'] = true;
        }

        $data['emailsetting']   = $this->Product_model->getSettings('emailsetting');
        $data['mob']       = $this->mobile_number;
        $data['base_url']  = base_url();
        $data['html']  = $htmlContent;

        if($is_store){
            $s_setting = $this->Product_model->getSettings('store');
            $data['emailsetting']['footer'] = $s_setting['footer'];
            $data['emailsetting']['logo'] = $s_setting['logo'];
        } else{
            $site = $this->Product_model->getSettings('site');
            $data['emailsetting']['logo'] = $site['logo'];
        }
        
        $template = $this->load->view('mails/header', $data, true);
        $template .= $this->load->view('mails/body', $data, true);
        $template .= $this->load->view('mails/footer', $data, true);
        if ($setting['smtp_username']) {
             
            $config['protocol']  = 'smtp';
            $config['smtp_host'] = $setting['smtp_hostname'];
            $config['smtp_port'] = $setting['smtp_port'];
            $config['smtp_user'] = $setting['smtp_username'];
            $config['smtp_pass'] = $setting['smtp_password'];
            $config['mailtype']  = 'html';
            $config['charset']   = 'utf-8';
        }
         
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        $this->email->to($to);
        $this->email->from($setting['from_email'],$setting['from_name']);
        $this->email->subject($subject);
        $this->email->message($template);
    
        return $this->email->send();
    }
    public function test_new($data){
         
        $template = $this->getTemplateByID($data['id']);
        $testing = array();
        $prifix = '';
        if($data['test_for'] == 'for-admin') $prifix = 'admin_';
        else if($data['test_for'] == 'for-client') {
            $prifix = 'client_';

            $body = $this->parseStoreTemplate($data[$prifix . 'text'],$template['shortcode'],$testing);
            $_subject = $this->parseStoreTemplate($data[$prifix . 'subject'],$template['shortcode'],$testing);
            
            $this->sendMail($data['test_email'],$_subject,$body, true);

            return true;
        }

        $body = $this->parseTemplate($data[$prifix . 'text'],$template['shortcode'],$testing);
        $_subject = $this->parseTemplate($data[$prifix . 'subject'],$testing);
        $this->sendMail($data['test_email'],$_subject,$body);
    }
    private function getTemplateByID($template_id, $data = array()) {
        return $this->db->query("SELECT * FROM mail_templates WHERE id = ". $template_id)->row_array();
    }
    private function parseTemplate($body, $shortcode , $data = array()) {
        $this->load->model('Product_model');
        $shortcode = explode(",", $shortcode);
        $setting = $this->Product_model->getSettings('site');
        $data['website_name'] = $setting['name'];

        if($setting['logo']){
        	$data['website_logo'] = "<img src='".base_url("assets/images/site/".$setting['logo'])."' >";
        }else{
        	$data['website_logo'] = '';
        }
        foreach ($shortcode as $key => $value) {
            if(isset($data[$value])){
                $body = str_replace("[[". $value ."]]", $data[$value], $body);
            }
        }
         
        return $body;
    }
    private function parseStoreTemplate($body, $shortcode , $data = array()) {
        $this->load->model('Product_model');
        $shortcode = explode(",", $shortcode);
        $setting = $this->Product_model->getSettings('store');
        $data['website_name'] = $setting['name'];
        
        $logo = base_url(trim($setting['logo']) ? 'assets/images/site/'.$setting['logo'] : 'assets/vertical/assets/images/users/avatar-1.jpg');

        $data['website_logo'] = "<img src='". $logo ."' >";
        foreach ($shortcode as $key => $value) {
            if(isset($data[$value])){
                $body = str_replace("[[". $value ."]]", $data[$value], $body);
            }
        }
        
        return $body;
    }
}