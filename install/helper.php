<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if(!function_exists('shapeSpace_server_memory_usage')){
	function shapeSpace_server_memory_usage() {
	 
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem);
		$mem = array_merge($mem);
		$memory_usage = $mem[2] / $mem[1] * 100;
	 
		$t = round((float)$memory_usage,2);

		if(is_nan($t)) return 0;
		return $t;
		
	}
}

if(!function_exists('shapeSpace_disk_usage')){
	function shapeSpace_disk_usage() {
		
		$disktotal = disk_total_space ('/');
		$diskfree  = disk_free_space  ('/');

		$diskuse   = round (100 - (($diskfree / $disktotal) * 100));
		
		$t= round((float)$diskuse,2);
		
		if(is_nan($t)) return 0;
		return $t;
	}
}

if(!function_exists('shapeSpace_system_load')){
	function shapeSpace_system_load($coreCount = 2, $interval = 1) {
		$rs = sys_getloadavg();
		$interval = $interval >= 1 && 3 <= $interval ? $interval : 1;
		$load = $rs[$interval];

		$t = round( (float)(($load * 100) / $coreCount),2);

		if(is_nan($t)) return 0;
		return $t;
	}
}

if(!function_exists('server_os')){
	function server_os(){
	    $os_detail = php_uname();
	    $just_os_name = explode(" ", trim($os_detail));

	    return $just_os_name[0];
	}
}


if(!function_exists('check_server_ip')){
	function check_server_ip(){
		return trim(gethostbyname(gethostname()));
	}
}	


if(!function_exists('check_limit')){
	function check_limit(){
		$memory_limit = ini_get('memory_limit');
		if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
			if ($matches[2] == 'G') {
				$memory_limit = $matches[1] . ' ' . 'GB';
			} else if ($matches[2] == 'M') {
				$memory_limit = $matches[1] . ' ' . 'MB';
			} else if ($matches[2] == 'K') {
				$memory_limit = $matches[1] . ' ' . 'KB';
			} else if ($matches[2] == 'T') {
				$memory_limit = $matches[1] . ' ' . 'TB';
			} else if ($matches[2] == 'P') {
				$memory_limit = $matches[1] . ' ' . 'PB';
			}
		}
		return $memory_limit;
	}
}


if(!function_exists('format_php_size')){
	function format_php_size($size){
		if (!is_numeric($size)) {
			if (strpos($size, 'M') !== false) {
				$size = intval($size) * 1024 * 1024;
			} elseif (strpos($size, 'K') !== false) {
				$size = intval($size) * 1024;
			} elseif (strpos($size, 'G') !== false) {
				$size = intval($size) * 1024 * 1024 * 1024;
			}
		}

		return is_numeric($size) ? format_filesize($size) : $size;
	}
}


if(!function_exists('format_filesize')){
	function format_filesize($bytes){
		if (($bytes / pow(1024, 5)) > 1) {
			return number_format(($bytes / pow(1024, 5)), 0) . ' ' . 'PB';
		} elseif (($bytes / pow(1024, 4)) > 1) {
			return number_format(($bytes / pow(1024, 4)), 0) . ' ' . 'TB';
		} elseif (($bytes / pow(1024, 3)) > 1) {
			return number_format(($bytes / pow(1024, 3)), 0) . ' ' . 'GB';
		} elseif (($bytes / pow(1024, 2)) > 1) {
			return number_format(($bytes / pow(1024, 2)), 0) . ' ' . 'MB';
		} elseif ($bytes / 1024 > 1) {
			return number_format($bytes / 1024, 0) . ' ' . 'KB';
		} elseif ($bytes >= 0) {
			return number_format($bytes, 0) . ' ' . 'bytes';
		} else {
			return 'Unknown';
		}
	}
}


if(!function_exists('format_filesize_kB')){
	function format_filesize_kB($kiloBytes){
		if (($kiloBytes / pow(1024, 4)) > 1) {
			return number_format(($kiloBytes / pow(1024, 4)), 0) . ' ' . 'PB';
		} elseif (($kiloBytes / pow(1024, 3)) > 1) {
			return number_format(($kiloBytes / pow(1024, 3)), 0) . ' ' . 'TB';
		} elseif (($kiloBytes / pow(1024, 2)) > 1) {
			return number_format(($kiloBytes / pow(1024, 2)), 0) . ' ' . 'GB';
		} elseif (($kiloBytes / 1024) > 1) {
			return number_format($kiloBytes / 1024, 0) . ' ' . 'MB';
		} elseif ($kiloBytes >= 0) {
			return number_format($kiloBytes / 1, 0) . ' ' . 'KB';
		} else {
			return 'Unknown';
		}
	}
}


if(!function_exists('php_max_upload_size')){
	function php_max_upload_size(){
		if (ini_get('upload_max_filesize')) {
			$php_max_upload_size = ini_get('upload_max_filesize');
			return format_php_size($php_max_upload_size);
		} else {
			return 'N/A';
		}
	}
}


if(!function_exists('php_max_post_size')){
	function php_max_post_size(){
		if (ini_get('post_max_size')) {
			$php_max_post_size = ini_get('post_max_size');
			return format_php_size($php_max_post_size);
		} 

		return 'N/A';
	}
}


if(!function_exists('php_max_execution_time')){
	function php_max_execution_time(){
		if (ini_get('max_execution_time')) {
			return ini_get('max_execution_time');
		}

		return 'N/A';
	}
}


if(!function_exists('database_software')){
	function database_software($con = false){
		if(function_exists('get_instance')){
			$ci=& get_instance();
			$ci->load->database(); 

			$query = $ci->db->query("SHOW VARIABLES LIKE 'version_comment'");
		  	$db_software_dump = $query->row()->Value;

		  	if (!empty($db_software_dump)) {
				$db_soft_array = explode(" ", trim($db_software_dump));
				return $db_soft_array[0];
			}
		} else{
			$db = mysqli_query($con,"SHOW VARIABLES LIKE 'version_comment'");
			$db_software_dump = $db->fetch_assoc();

			if (!empty($db_software_dump)) {
				$db_soft_array = explode(" ", trim($db_software_dump['Value']));
				return $db_soft_array[0];
			}
		}

		return 'N/A';
	}
}


if(!function_exists('database_version')){
	function database_version($con = false){
		if(function_exists('get_instance')){
			$ci=& get_instance();
			$ci->load->database(); 

			$query = $ci->db->query("SELECT VERSION() AS version from DUAL");
		  	$db_software_dump = $query->row()->version;

			if (preg_match('/\d+(?:\.\d+)+/', $db_software_dump, $matches)) {
				return $matches[0];
			}
		} else{
			$db = mysqli_query($con,"SELECT VERSION() AS version from DUAL");
			$db_software_dump = $db->fetch_assoc();


			if (preg_match('/\d+(?:\.\d+)+/', $db_software_dump['version'], $matches)) {
				return $matches[0];
			}
		} 

		return 'N/A';
	}
}


if(!function_exists('checkReq')){
	function checkReq(){
	    $error = array();

	    if (phpversion() < '5.6') {
	        $error['php'] = 'Warning: You need to use PHP 5.6 or above for Script to work! | Minimum version 5.6';
	    }

	    if (!extension_loaded('mysqli')) {
	        $error['mysqli'] = 'Warning: A database extension needs to be loaded in the php.ini for Script to work! | <div>Extension <i>mysqli</i></div> ';
	    }

	    if (!extension_loaded('curl')) {
	        $error['curl'] = 'Warning: CURL extension needs to be loaded for Script to work! | Extension <i>php_curl</i>';
	    } else{
	        $ip = $_SERVER["REMOTE_ADDR"];

	        $curl = curl_init("http://www.geoplugin.net/json.gp?ip=" . $ip);
	        $request = '';
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_HEADER, false);
	        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        
	        $ipdat = json_decode(curl_exec($curl),1);
	        if(is_array($ipdat) && isset($ipdat['geoplugin_status'])){

	        } else{
	            $error['ipapi'] = 'Warning: IP Api Not Working | Extension <i>php_curl</i>';
	        }
	    }

	    if (!function_exists('openssl_encrypt')) {
	        $error['openssl_encrypt'] = 'Warning: OpenSSL extension needs to be loaded for Script to work! | Extension <i>openssl_encrypt</i>';
	    }

	    if (! class_exists('ZipArchive') ) {
	        $error['ziparchive'] = 'Warning: ZipArchive extension needs to be installed for Script to work! | Extension <i>php_curl</i>';
	    }
	    
	    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
        
	    } else{
	        $error['gzip'] = 'Warning:Enable Gzip compression for Script to work!';
	    }

	    $ini = phpinfo_array(true);
         
	    $base = __DIR__;
	    $checkDir = array(
	        'Backup Directory Is Not Writable, Set 777 Premmission to : application/backup/mysql' => ($base . '/../application/backup/mysql'),
	        'Download Directory Is Not Writable, Set 777 Premmission to : application/downloads' => ($base . '/../application/downloads'),
	        'Config Directory Is Not Writable Set, 777 Premmission to : application/config' => ($base . '/../application/config'),
	        'language Directory Is Not Writable Set, 777 Premmission to : application/language' => ($base . '/../application/language'),
	        'cache Directory Is Not Writable Set, 777 Premmission to : application/cache' => ($base . '/../application/cache'),
	        'market_cache Directory Is Not Writable, Set 777 Premmission to : application/market_cache' => ($base . '/../application/market_cache'),
	        'downloads_order Directory Is Not Writable, Set 777 Premmission to : application/downloads_order' => ($base . '/../application/downloads_order'),
	        'Assets Directory Is Not Writable Set, 777 Premmission to : assets/images/site' => ($base . '/../assets/images/site'),
	    );

	    foreach ($checkDir as $key => $value) {
	        if(!is_writable($value)){
	            $error['writable'] = $key;
	        }
	    }
	    
	    //$error['ssl'] = is_ssl();
	    return $error;
	}
}

if(!function_exists('is_ssl')){
	function is_ssl() {
	    if ( isset($_SERVER['HTTPS']) ) {
	        if ( 'on' == strtolower($_SERVER['HTTPS']) )
	        return true;
	        if ( '1' == $_SERVER['HTTPS'] )
	        return true;
	    } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
	        return true;
	    }

	    return false;
	}
}
if(!function_exists('phpinfo_array')){
	function phpinfo_array($return=false){
	    ob_start(); 
	    phpinfo(-1);

	    $pi = preg_replace(
	    array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
	    '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
	    "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
	      '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
	      .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
	      '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
	      '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
	      "# +#", '#<tr>#', '#</tr>#'),
	    array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
	      '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
	      "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
	      '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
	      '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
	      '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
	    ob_get_clean());

	    $sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
	    unset($sections[0]);

	    $pi = array();
	    foreach($sections as $section){
	       $n = substr($section, 0, strpos($section, '</h2>'));
	       preg_match_all(
	       '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
	         $section, $askapache, PREG_SET_ORDER);
	       foreach($askapache as $m)
	           $pi[$n][$m[1]]=(!isset($m[3])||$m[2]==$m[3])?$m[2]:array_slice($m,2);
	    }

	    return ($return === false) ? print_r($pi) : $pi;
	}
}