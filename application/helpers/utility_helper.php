<?php
function av(){
    return '1.7';
}
function cycle_details($total_recurring,$next_transaction,$endtime = false,$total_recurring_amount = false ){
    $str =  'Runs '. (int)$total_recurring;
    
    if($next_transaction != ''){
        $str .= " | Next At : ". date("d-m-Y H:i",strtotime($next_transaction));
    }
    if($endtime != ''){
        $str .= " | Endtime : ". date("d-m-Y H:i",strtotime($endtime));
    }
    if($total_recurring_amount){
        $str .= " | Total Amount : ". c_format($total_recurring_amount);
    }

    return $str;
}

function dateFormat($date){
    return date("d-m-Y H:i:s",strtotime($date));
}
function timetosting($minutes){
    $day = floor ($minutes / 1440);
    $hour = floor (($minutes - $day * 1440) / 60);
    $minute = $minutes - ($day * 1440) - ($hour * 60);

    $str = '';
    if($day > 0) $str .= "{$day} day ";
    if($hour > 0) $str .= "{$hour} hour ";
    if($minute > 0) $str .= "{$minute} minute ";
    
    return $str;
}
function asset_url() {
    echo base_url() . 'assets/';
}
function pr($data) {
    echo '<pre>'; print_r($data); echo '</pre>';
}
function flashMsg($flash) { 
    if (isset($flash['error'])) {
        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>' .$flash['error']. '</div>';
    }
    if (isset($flash['success'])) {
        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>' .$flash['success'] . '</div>';
    }
}
function e3DpOIO10($check_cache = true){
    $cache_file = str_replace("install/../", '', APL_CACHE);
    $res = '';
    
    if($check_cache){
        if( file_exists($cache_file) ){
            $res = json_decode(file_get_contents($cache_file),1);
        }
    } else {
        $res = getLicense(getBaseUrl(false));
        @unlink($cache_file);

        $fp = fopen($cache_file, 'w');
        fwrite($fp, json_encode($res));
        fclose($fp);
    }

    if(isset($res['success']['is_lifetime']) && $res['success']['is_lifetime'] == false){
        if ($res['success']['remianing_time'] <= 0) {
             $base_url = base_url();
            @unlink($cache_file);
            require 'install/license_expire.php';
            die();
        }
    }
    
    if($res && isset($res['success'])){
        
    }
    else if($res && isset($res['error'])){ 
        @unlink($cache_file);
        header('location:'. base_url('install/index.php?error='. $res['error']));die;
    }
}
function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'admin@cyclopsltd.com';
    $secret_iv = 'admin@admin#@!';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function DOCROOT($file, $from) {
    if ($from == 'full') {
        return @$_SERVER["DOCUMENT_ROOT"] . '/cyclops/assets/uploads/' . $file;
    } elseif ($from == 'thumb') {
        return @$_SERVER["DOCUMENT_ROOT"] . '/cyclops/assets/uploads/thumb/' . $file;
    }
}
global $language; 
function __($key){
    global $language;
    $userLang = $_SESSION['userLang'];
    if($userLang == ''){
        $CI =& get_instance();
        $default_language = $CI->db->query("SELECT * FROM language WHERE status=1 AND is_default=1")->row_array();
        if($default_language){
            $userLang = $_SESSION['userLang'] = $default_language['id'];
        }
    }
    if(!$language){
        fillLang($userLang);
    }
    
    return isset($language[$key]) ? $language[$key] : $key;
}
function fillLang($id){
    global $language;
    $language = array();
    $lang_files = ['admin','client','store','user','front','template_simple'];


    foreach ($lang_files as $file) {
        if(is_file(APPPATH.'/language/default/'. $file .'.php')){
            require  APPPATH.'/language/default/'. $file .'.php';
            foreach ($lang as $key => $value) {
                $language[$file . '.'.$key] = $value;
            }
        }
        $lang = array();
    }

    foreach ($lang_files as $file) {
        if(is_file(APPPATH.'/language/'. $id .'/'. $file .'.php')){
            require  APPPATH.'/language//'. $id .'//'. $file .'.php';
            foreach ($lang as $key => $value) {
                if($value) $language[$file . '.'.$key] = $value;
            }
        }
        $lang = array();
    }
}

function recurse_copy($src,$dst) { 
    $dir = opendir($src);
    if (!file_exists($dst)) {
        mkdir($dst, 0777, true);
    }
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
function lang_copy($src,$dst){
    $dir = opendir($src);
    if (!file_exists($dst)) {
        mkdir($dst, 0777, true);
    }
   
    $lang_files = ['admin','client','store','user','front','template_simple'];
    foreach ($lang_files as $file) {
        if(is_file($src .'/'. $file .'.php')){
            $lang = array();
            require  $src .'/'. $file .'.php';
            
            $path = $dst."/".$file.".php";
            $file_content = '<?php '.PHP_EOL;
     
            foreach ($lang as $key => $value) {
                $file_content .= '$lang[\''. $key .'\'] = \'\';' .PHP_EOL;
            }
            file_put_contents($path, $file_content);
        }
        $lang = array();
    }
}
function langCount($id){
    $id = $id == "1" ? 'default' : $id;
    
    $missing = $all = [];
    $count = array('all' => 0, 'missing' => 0);
    $lang_files = ['admin','client','store','user','front','template_simple'];
    foreach ($lang_files as $file) {
        if(is_file(APPPATH.'/language/'. $id .'/'. $file .'.php')){
            $lang = array();
            require  APPPATH.'/language//'. $id .'//'. $file .'.php';
            foreach ($lang as $key => $value) {
                $count['all']++;
                $all[$key] = $value;
                if($value != ''){
                    $missing[$key] = $value;
                    //$count['missing']++;
                }
            }
        }
        $lang = array();
    }
    
    $count = array('all' => count($all), 'missing' => count($missing));
    return $count;
}

function get_payment_gateways(){
    $CI =& get_instance();

    $files = array();
    foreach (glob(APPPATH."/payments/controllers/*.php") as $file) { $files[] = $file; }
    $methods = array_unique($files);

    $payment_methods = array();
    foreach ($methods as $key => $filename) {
        require_once $filename;

        $code = basename($filename, ".php");
        $obj = new $code($CI);

        $pdata            = array();
        $pdata['title']   = $obj->title;
        $pdata['icon']    = $obj->icon;
        $pdata['website'] = $obj->website;
        $pdata['code']    = $code;

        $setting_data = $CI->Product_model->getSettings('storepayment_'.$code);
        $pdata['status']  = 0;
        if (isset($setting_data['status']) && $setting_data['status']) {
            $pdata['status']  = 1;
        }
        $payment_methods[$code] = $pdata;
    }

    return $payment_methods;
}