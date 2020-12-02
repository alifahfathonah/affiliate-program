<?php

function is_ssl() {
    if ( isset($_SERVER['HTTPS']) ) {
        if ( 'on' == strtolower($_SERVER['HTTPS']) )
        return true;
        if ( '1' == $_SERVER['HTTPS'] )
        return true;
    } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
        return true;
    }

    return true;
}

function view($file, $data = array()){
	ob_start();extract($data);include $file.".php";$output = ob_get_contents();ob_clean();
	return $output;
}

function api($endpoint, $data = array(), $is_json = true){
	$curl = curl_init('https://buy.affiliatepro.org/'. $endpoint);
	$request = http_build_query($data);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	if($is_json) $response = json_decode(curl_exec($curl),1);
	else $response = curl_exec($curl);

	return $response;
}

function base_path($remove = ''){  
    $root=(isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
    $root.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    return str_replace($remove, '', trim($root,'/'));
}

function getBaseUrl($remove = true) { 
    $url = base_path();
    if($remove) $url = str_replace(basename($url),"",$url);
    return trim(str_replace('/install','',$url),"/");
}

function root_url(){
	$root_url = strtok(trim(str_replace('/install', '', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']),"/"),"?");
    $root_url = str_replace("proccess.php","", $root_url);
    $root_url = trim( $root_url,"/");
    $root_url = trim(str_replace(['https','http',':','//','www.','index.php'],['','','','','',''],$root_url),"/");

    return trim($root_url,"/");
}

function getmylic(){
	$user             = $_SESSION['api_login'];
	$post['root_url'] = root_url();
	$post['base_url'] = getBaseUrl();
	$post['email']    = $user['email'];

	$res = api('api/getmylic',$post);

	$_SESSION['api_login']['license'] = $res;
	return $res;
}

function checkIsInstall(){
    $ROOTDIR = str_replace(['install'], [''], __DIR__);
    $dir = $ROOTDIR . '/application/config/database.php';
    include $dir;

    return isset($db['default']['database']);
}

function getInstall($user,$_data){
	$root_url = root_url();
    $base_url = getBaseUrl();
    $ROOTDIR = str_replace(['install'], [''], __DIR__);
    
    $data = array(
		"email"      => $user['email'],
		"product_id" => 1,
		"root_url"   => $root_url,
		"base_url"   => $base_url
	);
    
    if ($base_url) {
		$output = '<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");' . "\n";
		$output .= '$db["default"]["hostname"] = "' . $_data['db_hostname'] . '";' . "\n";
		$output .= '$db["default"]["username"] = "' . $_data['db_username'] . '";' . "\n";
		$output .= '$db["default"]["password"] = \'' . $_data['db_password'] . '\';' . "\n";
		$output .= '$db["default"]["database"] = "' . $_data['db_database'] . '";' . "\n";
		$output .= '$db["default"]["dbdriver"] = "mysqli";' . "\n";
		$output .= '$db["default"]["dbprefix"] = "";' . "\n";
		$output .= '$db["default"]["pconnect"] = FALSE;' . "\n";
		$output .= '$db["default"]["db_debug"] = TRUE;' . "\n";
		$output .= '$db["default"]["cache_on"] = FALSE;' . "\n";
		$output .= '$db["default"]["stricton"] = FALSE;' . "\n";
		$output .= '$db["default"]["cachedir"] = "";' . "\n";
		$output .= '$db["default"]["char_set"] = "utf8";' . "\n";
		$output .= '$db["default"]["dbcollat"] = "utf8_general_ci";' . "\n";
		$output .= '$active_group = "default";' . "\n";
		$output .= '$active_record = TRUE;' . "\n";

        $dir = $ROOTDIR . '/application/config/database.php';
        $databse_sql = file_get_contents('database.sql');
        $con = mysqli_connect($_data['db_hostname'], $_data['db_username'], $_data['db_password'], $_data['db_database']);

        $file = fopen($dir, 'w');
        fwrite($file, $output);
        fclose($file);

        $res = mysqli_query($con, "SHOW TABLES");
        if (mysqli_num_rows($res) == 0) {
            $lines = explode("\n", $databse_sql);
            $sql_query = '';
            foreach($lines as $line) {
                if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
                    $sql_query .= $line;
                    if (preg_match('/;\s*$/', $line)) {
                        mysqli_query($con, $sql_query);
                        $sql_query = '';
                    }
                }
            }
        }

        $dir = $ROOTDIR . '/application/config/config.php';
        $handle = fopen($dir, "r");
        $ci_config = '$config[\'base_url\']';
        $new_congif = '';
        $len = strlen($ci_config);

        if ($handle) {
            $found = false;
            while (($line = fgets($handle)) !== false) {
                if (!$found && strpos($line, $ci_config) !== false) {
                    $found = true;
                    $line = '$config[\'base_url\']  = \''. getBaseUrl() .'\';/*';
                }
                $new_congif .= PHP_EOL. $line;
            }
            fclose($handle);

            $new_congif = preg_replace("/[\r\n]+/", "\n", $new_congif);
            $new_congif = trim($new_congif);
            file_put_contents($dir, $new_congif);
        }

        require_once 'version.php';
        $newversion_file = '<?php define(\'SCRIPT_VERSION\', "'. SCRIPT_VERSION .'");';
        file_put_contents($ROOTDIR."/install/version.php", $newversion_file); 

		$json['success'] = true;		
    } else {
    	$json['warning'] = (isset($data['error']) && $data['error']) ? $data['error'] : 'Unknown Error..!';	
    }

    return $json;
}