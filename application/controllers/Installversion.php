<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
ini_set('display_errors', 0);

class Installversion extends MY_Controller {
	function __construct() {
		parent::__construct();

		$this->loginID = $this->session->userdata('administrator');
		if(!$this->loginID){ redirect('admin', 'refresh'); }
	}

	public function downloadDatabase(){
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=database.sql");

		$database_structure = $this->getOurDB();
		print_r($database_structure);
	}

	public function check_confirm_password(){
		$password = $this->input->post('password',true);

		$user = $this->db->query("SELECT * FROM users WHERE id=". (int)$this->loginID['id'])->row();
		if(sha1($password) == $user->password){
			$json['success'] = true;
			$this->session->set_userdata('tmp_login' , $user->id);
		} else {
			$json['warning'] = "Wrong Password..!";
		}

		echo json_encode($json);
	}

	public function confirm_password(){
		$json = [];

		$lastLogin = $this->session->userdata('tmp_login');
		if(!$lastLogin){
			$data['for'] = $this->input->post('for', true);
			$json['html'] = $this->load->view("admincontrol/setting/steps/password",$data,true);
		} else {
			$json['callback'] = true;
		}

		echo json_encode($json);
	}

	public function getStep(){
		$number = (int)$this->input->post("number");
		$json = [];
		
		if($number == 0){
			$this->session->unset_userdata('tmp_login');
			$json['html'] = $this->load->view("admincontrol/setting/steps/database",$data,true);
		}

		if($number == 1){
			$this->session->unset_userdata('tmp_login');
			$json['html'] = $this->load->view("admincontrol/setting/steps/files",$data,true);
		}

		if($number == 2){
			$json['html'] = $this->load->view("admincontrol/setting/steps/finish",$data,true);
			$json['version']= SCRIPT_VERSION;
		}

		echo json_encode($json);
	}

	public function migrateFiles(){
		$files = $_FILES['update'];
		$json = [];

		if(!isset($files['name']) || $files['name'] == ''){
			$json['errors']['update'] = 'Please select update.zip file';
		} else {
			$ext = pathinfo($files['name'], PATHINFO_EXTENSION);
			if($ext != 'zip'){
				$json['errors']['update'] = 'Please select .zip file';
			}
		}

		if(!isset($json['errors'])){
			$newVersion = str_replace(['update-','.zip'], ['',''], $files['name']);
			if(version_compare($newVersion,SCRIPT_VERSION) < 0){
				$json['errors']['update'] = 'Script version must be greater than '. SCRIPT_VERSION;
			}
		}

		if(!isset($json['errors'])){
			ini_set('max_execution_time', 600);
			ini_set('max_execution_time', 0);

			$destination = "updates.zip";
			unlink($destination);
			file_put_contents($destination, file_get_contents($files['tmp_name'])); 

			$zip = new ZipArchive;
			$res = $zip->open($destination);

			if ($res === TRUE) {
				if(!$zip->extractTo('.')) { 
					$json['errors']['update'] = "Error during extracting"; 
				} else {
				    $zip->close();
				    unlink($destination);

		            $newversion_file = '<?php define(\'SCRIPT_VERSION\', \''. $newVersion .'\');';
		            file_put_contents(FCPATH."/install/version.php", $newversion_file); 

		            $data['btn'] = 'Finish';
		            $data['success_message'] = 'Files Migrated Successfully';
					$data['getStep'] = 2;
					$json['success'] = $this->load->view("admincontrol/setting/steps/success",$data,true);
				}
			    
			}
		}

		echo json_encode($json);
	}

	public function migrateDatabase(){
		$files = $_FILES['database'];
		$json= array();

		if(!isset($files['name']) || $files['name'] == ''){
			$json['errors']['database'] = 'Please select a database file';
		} else {
			$ext = pathinfo($files['name'], PATHINFO_EXTENSION);
			if($ext != 'sql'){
				$json['errors']['database'] = 'Please select .sql file';
			}
		}

		if(!isset($json['errors'])){
			$database_sql = file_get_contents($files['tmp_name']);

			$updates_query = $this->getDiff($database_sql);
			if(is_array($updates_query)){
				foreach ($updates_query as $key => $value) {
					$this->db->query($value);
				}
			}

			$data['btn'] = 'Next Migrate Files';
			$data['success_message'] = 'Databse Migrated Successfully';
			$data['getStep'] = 1;
			$json['success'] = $this->load->view("admincontrol/setting/steps/success",$data,true);
		}

		echo json_encode($json);
	}

	
	public function getDiff($master_db){ 
		$user_db_tables = $this->getOurDB();
		$tables_drop = [];

		require_once APPPATH.'core/dbStruct.php';
		$updater = new dbStructUpdater();		
		$result = $updater->getUpdates($user_db_tables, (string)$master_db);
		return $result;
	}

	private function getOurDB(){
		$dir = APPPATH . 'core/doctorin/vendor/autoload.php';
		require $dir;

		$config = new \Doctrine\DBAL\Configuration();
		$connectionParams = array(
			'dbname'   => $this->db->database,
			'user'     => $this->db->username,
			'password' => $this->db->password,
			'host'     => $this->db->hostname,
			'driver'   => 'mysqli',
		);
		$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
		$conn->query("SET SQL_MODE = ''");

	 	$tables= array();
		$db = $this->db->query("SHOW TABLES")->result_array();

		if (isset($_POST['records'])) {
			$records = json_decode($_POST['records'], true);
		}

		$database_structure = '';
		foreach ($db as $key => $value) {
			
			$tb_name = $value['Tables_in_'. $this->db->database];
			$schemaManager = $conn->getSchemaManager();
			$t = $schemaManager->listTableDetails( $tb_name);

			$platform = $schemaManager->getDatabasePlatform();
			$platform->registerDoctrineTypeMapping('enum', 'string');

			$s = $platform->getCreateTableSQL($t);
			if(isset($s[0])){ $database_structure .= $s[0] . PHP_EOL; }
		}

		return $database_structure;
	}
}