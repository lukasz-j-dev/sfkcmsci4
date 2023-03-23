<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Plates extends BaseController
{
	protected $violationModel;
	protected $plateModel;
	protected $mmcModel;
	protected $villageModel;
	protected $session;
	protected $cameraModel;
	protected $userModel;
	protected $request;
	public function initController(
		RequestInterface $request,
		ResponseInterface $response,
		LoggerInterface $logger
	) {
		parent::initController($request, $response, $logger);
		$this->violationModel = model('ViolationModel');
		$this->plateModel = model('PlateModel');
		$this->mmcModel = model('MmcModel');
		$this->villageModel = model('VillageModel');
		$this->cameraModel = model('CameraModel');
		$this->userModel = model('UserModel');

		$this->session = session();
		$this->request = $request;
	}

		//-----------------------------------------------------------------------

		public function index(){

			$data['view'] = 'admin/plates/plates_list';
			return view('layout', $data);
	
		}
		
		public function datatable_json(){				   
							   
			$records = $this->plateModel->get_all_plates();
			$data = array();
	
			foreach ($records['data']  as $row) 
			{  
				$data[]= array(
					'<input type="checkbox" class="plate_check" name="plate_id[]" value="' . $row['plate_number'] . '"/>',
					$row['plate_id'],
					$row['plate_number'],
					$row['dmv_plate_type'],
					$row['dmv_contact_city'],
					$row['dmv_contact_state'],
					$row['dmv_contact_zipcode'],
					$row['dmv_contact_name'],
					$row['dmv_vehicle_body'],
					$row['dmv_vehicle_year'],
					$row['dmv_vehicle_make'],
					$row['dmv_vehicle_body'],
					$row['dmv_vehicle_color'],
					$row['dmv_status'],
					$row['dmv_last_checked_date'],
					'<a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url('admin/plates/edit/'.$row['plate_id']).'"> <i class="material-icons">edit</i></a>
					<a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url('admin/plates/del/'.$row['plate_id']).'" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
					',
				);
			}
	
			$records['data']=$data;
			echo json_encode($records);						   
		}
		
		public function add(){
	
			if($this->request->getPost('submit')){
				$validation = \Config\Services::validation();

				$validation->setRule('plate_number', 'plate_number', 'trim|required');
				$validation->setRule('dmv_plate_type', 'dmv_plate_type', 'trim');
				$validation->setRule('dmv_contact_name', 'dmv_contact_name', 'trim');
				$validation->setRule('dmv_contact_address', 'dmv_contact_address', 'trim');
				$validation->setRule('dmv_contact_city', 'dmv_contact_city', 'trim');
				$validation->setRule('dmv_contact_state', 'dmv_contact_state', 'trim');
				$validation->setRule('dmv_contact_zipcode', 'dmv_contact_zipcode', 'trim');
				$validation->setRule('dmv_vehicle_vin', 'dmv_vehicle_vin', 'trim');
				$validation->setRule('dmv_vehicle_body', 'dmv_vehicle_body', 'trim');
				$validation->setRule('dmv_vehicle_year', 'dmv_vehicle_year', 'trim');
				$validation->setRule('dmv_vehicle_make', 'dmv_vehicle_make', 'trim');
				$validation->setRule('dmv_vehicle_color', 'dmv_vehicle_color', 'trim');
				$validation->setRule('dmv_last_checked_date', 'dmv_last_checked_date', 'trim');
				$validation->setRule('dmv_status', 'dmv_status', 'trim');
				$validation->setRule('dmv_expiration_date', 'dmv_expiration_date', 'trim');
				$validation->setRule('dmv_contact_sex', 'dmv_contact_sex', 'trim');
				$validation->setRule('dmv_contact_birth_date', 'dmv_contact_birth_date', 'trim');
				$validation->setRule('dmv_contact_county', 'dmv_contact_county', 'trim');
				$validation->setRule('dmv_mid_number', 'dmv_mid_number', 'trim');
				if ($validation->withRequest($this->request)->run() == FALSE) {
	
					$data['view'] = 'admin/plates/plate_add';
	
					return view('layout', $data);
	
				}else{
					$plate = $this->request->getPost('plate_number');
					if($this->plateModel->plate_exist($plate)){
						$this->session->setFlashdata('msg1', 'Plate Number Exist');
						return redirect()->to(base_url('admin/plates'));
						exit;
					}
					$data = array(
	
						'plate_number' => $plate,
	
						'dmv_plate_type' => $this->request->getPost('dmv_plate_type'),
	
						'dmv_contact_name' => $this->request->getPost('dmv_contact_name'),
	
						'dmv_contact_address' => $this->request->getPost('dmv_contact_address'),
	
						'dmv_contact_city' => $this->request->getPost('dmv_contact_city'),
	
						'dmv_contact_state' => $this->request->getPost('dmv_contact_state'),
						
						'dmv_contact_zipcode' => $this->request->getPost('dmv_contact_zipcode'),
						'dmv_vehicle_vin' => $this->request->getPost('dmv_vehicle_vin'),
						'dmv_vehicle_body' => $this->request->getPost('dmv_vehicle_body'),
						'dmv_vehicle_year' => $this->request->getPost('dmv_vehicle_year'),
						'dmv_vehicle_make' => $this->request->getPost('dmv_vehicle_make'),
						'dmv_vehicle_color' => $this->request->getPost('dmv_vehicle_color'),
						
						'dmv_last_checked_date' =>  $this->request->getPost('dmv_last_checked_date'),
	
						'dmv_status' => $this->request->getPost('dmv_status'),
						'dmv_expiration_date' => $this->request->getPost('dmv_expiration_date'),
						'dmv_contact_sex' => $this->request->getPost('dmv_contact_sex'),
						'dmv_contact_birth_date' => $this->request->getPost('dmv_contact_birth_date'),
						'dmv_contact_county' => $this->request->getPost('dmv_contact_county'),
						'dmv_mid_number' => $this->request->getPost('dmv_mid_number'),
						'plate_added_date' => date('Y-m-d H:i:s'),
	
					);
					//$data = $this->security->xss_clean($data);
	
					$result = $this->plateModel->add_plate($data);
					if($result){
						// Add User Activity
	
						//$this->activity_model->add(1);
	
						$this->session->setFlashdata('msg', 'Plate has been added successfully!');
	
						return redirect()->to(base_url('admin/plates'));
	
					}
				}
	
			}else{
				$data['view'] = 'admin/plates/plate_add';
	
				return view('layout', $data);
	
			}
	
		}
		public function add_ajax(){
			$json_resp = array();
			if($this->request->getPost('plate_number')){
				$plate = $this->request->getPost('plate_number');
				if($this->plateModel->plate_exist($plate)){
					$json_resp['exist'] = true;
				}else{
					$json_resp['exist'] = false;
					$data = array(
						'plate_number' => $plate,
						'plate_added_date' => date('Y-m-d H:i:s'),
					);
					//$data = $this->security->xss_clean($data);
					$result = $this->plateModel->add_plate($data);
					if($result) $json_resp['resp'] = true;
				}
				$json_resp['success'] = true;
			}else $json_resp['success'] = false;
			echo json_encode($json_resp);
			exit;
		}
		public function edit($id = 0){
			if($this->request->getPost('submit')){
				$validation = \Config\Services::validation();

				$validation->setRule('dmv_plate_type', 'dmv_plate_type', 'trim');
				$validation->setRule('dmv_contact_name', 'dmv_contact_name', 'trim');
				$validation->setRule('dmv_contact_address', 'dmv_contact_address', 'trim');
				$validation->setRule('dmv_contact_city', 'dmv_contact_city', 'trim');
				$validation->setRule('dmv_contact_state', 'dmv_contact_state', 'trim');
				$validation->setRule('dmv_contact_zipcode', 'dmv_contact_zipcode', 'trim');
				$validation->setRule('dmv_vehicle_vin', 'dmv_vehicle_vin', 'trim');
				$validation->setRule('dmv_vehicle_body', 'dmv_vehicle_body', 'trim');
				$validation->setRule('dmv_vehicle_year', 'dmv_vehicle_year', 'trim');
				$validation->setRule('dmv_vehicle_make', 'dmv_vehicle_make', 'trim');
				$validation->setRule('dmv_vehicle_color', 'dmv_vehicle_color', 'trim');
				$validation->setRule('dmv_last_checked_date', 'dmv_last_checked_date', 'trim');
				$validation->setRule('dmv_status', 'dmv_status', 'trim');
				$validation->setRule('dmv_expiration_date', 'dmv_expiration_date', 'trim');
				$validation->setRule('dmv_contact_sex', 'dmv_contact_sex', 'trim');
				$validation->setRule('dmv_contact_birth_date', 'dmv_contact_birth_date', 'trim');
				$validation->setRule('dmv_contact_county', 'dmv_contact_county', 'trim');
				$validation->setRule('dmv_mid_number', 'dmv_mid_number', 'trim');
				if ($validation->withRequest($this->request)->run() == FALSE) {
	
					$data['view'] = 'admin/plates/plate_edit';
	
					return view('layout', $data);
	
				}else{
					$data = array(
	
						'dmv_plate_type' => $this->request->getPost('dmv_plate_type'),
	
						'dmv_contact_name' => $this->request->getPost('dmv_contact_name'),
	
						'dmv_contact_address' => $this->request->getPost('dmv_contact_address'),
	
						'dmv_contact_city' => $this->request->getPost('dmv_contact_city'),
	
						'dmv_contact_state' => $this->request->getPost('dmv_contact_state'),
						
						'dmv_contact_zipcode' => $this->request->getPost('dmv_contact_zipcode'),
						'dmv_vehicle_vin' => $this->request->getPost('dmv_vehicle_vin'),
						'dmv_vehicle_body' => $this->request->getPost('dmv_vehicle_body'),
						'dmv_vehicle_year' => $this->request->getPost('dmv_vehicle_year'),
						'dmv_vehicle_make' => $this->request->getPost('dmv_vehicle_make'),
						'dmv_vehicle_color' => $this->request->getPost('dmv_vehicle_color'),
							'dmv_last_checked_date' => $this->request->getPost('dmv_last_checked_date'),
						
						'dmv_last_checked_date' =>  $this->request->getPost('dmv_last_checked_date'),
	
						'dmv_status' => $this->request->getPost('dmv_status'),
						'dmv_expiration_date' => $this->request->getPost('dmv_expiration_date'),
						'dmv_contact_sex' => $this->request->getPost('dmv_contact_sex'),
						'dmv_contact_birth_date' => $this->request->getPost('dmv_contact_birth_date'),
						'dmv_contact_county' => $this->request->getPost('dmv_contact_county'),
						'dmv_mid_number' => $this->request->getPost('dmv_mid_number'),
	
					);
				//	$data = $this->security->xss_clean($data);
	
					$result = $this->plateModel->edit_plate($data, $id);
					if($result){
						// Add User Activity
	
					//	$this->activity_model->add(1);
	
						$this->session->setFlashdata('msg', 'Plate has been added successfully!');
	
						return redirect()->to(base_url('admin/plates'));
	
					}
				}
			}
			else{
				$data['plate_details'] = $this->plateModel->get_plate_by_id($id);
	
				$data['view'] = 'admin/plates/plate_edit';
				return view('layout', $data);
			}
		}
		public function del($id = 0){
			$this->plateModel->delete( $id);
	
			// Add User Activity
		//	$this->activity_model->add(3);
	
			$this->session->setFlashdata('msg', 'Plates has been deleted successfully!');
			return redirect()->to(base_url('admin/plates'));
		}
		public function import(){
			$data['view'] = 'admin/plates/import';
			return view('layout', $data);
		}
		public function importcsv(){
			if($this->request->getPost('submit')){
				$handle = fopen($_FILES["plate_xlsx"]["tmp_name"], 'r');
				$plate_head = fgetcsv($handle, 1000, ",");
				while (($v = fgetcsv($handle, 1000, ",")) !== FALSE){
					if(!empty($v[array_search("Plate", $plate_head)])){
						$plate_details = $this->plateModel->get_plates_by_plate($v[array_search("Plate", $plate_head)]);
						if(!empty($plate_details['id'])){
							$up_data = array(
	
								'plate_number' => $v[array_search("Plate", $plate_head)],
	
								'dmv_plate_type' => $v[array_search("Plate type", $plate_head)],
	
								'dmv_contact_name' => $v[array_search("Name", $plate_head)],
	
								'dmv_contact_address' => $v[array_search("Address", $plate_head)],
								
								'dmv_contact_city' => $v[array_search("City", $plate_head)],
								'dmv_contact_state' => $v[array_search("State", $plate_head)],
								'dmv_contact_zipcode' => $v[array_search("Zip code", $plate_head)],
								'dmv_vehicle_vin' => $v[array_search("VIN", $plate_head)],
								'dmv_vehicle_body' => $v[array_search("Body", $plate_head)],
								'dmv_vehicle_year' => $v[array_search("Year", $plate_head)],
								
								'dmv_vehicle_make' =>  $v[array_search("Make", $plate_head)],
	
								'dmv_vehicle_color' => $v[array_search("Color", $plate_head)],
								'dmv_status' => $v[array_search("DMV Status", $plate_head)],
								'dmv_contact_sex' => $v[array_search("Sex", $plate_head)],
								'dmv_contact_county' => $v[array_search("County", $plate_head)],
								'dmv_mid_number' => $v[array_search("MID Number", $plate_head)],
								'plate_added_date' => date('Y-m-d H:i:s'),
	
							);
							$dmv_date = $v[array_search("DMV Date checked", $plate_head)];
							$birth_date = $v[array_search("Birth Date", $plate_head)];
							$exp_date = $v[array_search("Expiration Date", $plate_head)];
							$up_data['dmv_last_checked_date'] = $dmv_date;
							$up_data['dmv_contact_birth_date'] = $birth_date;
							$up_data['dmv_expiration_date'] = $exp_date;
							//$up_data = $this->security->xss_clean($up_data);
							//print_r($up_data);
							$this->plateModel->edit_plate($up_data, $plate_details['id']);
						}else{
							$ins_data = array(
	
								'plate_number' => $v[array_search("Plate", $plate_head)],
	
								'dmv_plate_type' => $v[array_search("Plate type", $plate_head)],
	
								'dmv_contact_name' => $v[array_search("Name", $plate_head)],
	
								'dmv_contact_address' => $v[array_search("Address", $plate_head)],
								
								'dmv_contact_city' => $v[array_search("City", $plate_head)],
								'dmv_contact_state' => $v[array_search("State", $plate_head)],
								'dmv_contact_zipcode' => $v[array_search("Zip code", $plate_head)],
								'dmv_vehicle_vin' => $v[array_search("VIN", $plate_head)],
								'dmv_vehicle_body' => $v[array_search("Body", $plate_head)],
								'dmv_vehicle_year' => $v[array_search("Year", $plate_head)],
								
								'dmv_vehicle_make' =>  $v[array_search("Make", $plate_head)],
	
								'dmv_vehicle_color' => $v[array_search("Color", $plate_head)],
								'dmv_status' => $v[array_search("DMV Status", $plate_head)],
								'dmv_contact_sex' => $v[array_search("Sex", $plate_head)],
								'dmv_contact_county' => $v[array_search("County", $plate_head)],
								'dmv_mid_number' => $v[array_search("MID Number", $plate_head)],
								'plate_added_date' => date('Y-m-d H:i:s'),
	
							);
							$dmv_date = $v[array_search("DMV Date checked", $plate_head)];
							$birth_date = $v[array_search("Birth Date", $plate_head)];
							$exp_date = $v[array_search("Expiration Date", $plate_head)];
							$ins_data['dmv_last_checked_date'] = $dmv_date;
							$ins_data['dmv_contact_birth_date'] = $birth_date;
							$ins_data['dmv_expiration_date'] = $exp_date;
						//	$ins_data = $this->security->xss_clean($ins_data);
							if(!$this->plateModel->plate_exist($v[array_search("Plate", $plate_head)])) $result = $this->plateModel->add_plate($ins_data);
						}
					}
				}
				$this->session->setFlashdata('msg', 'Plates has been imported successfully!');
				return redirect()->to(base_url('admin/plates'));
			}
			$data['view'] = 'admin/plates/import';
			return view('layout', $data);
		}
		public function exportcsv(){
			if(!empty($_REQUEST['plate_id'])){
				$plate_ids = $_REQUEST['plate_id'];
				$records = $this->db->where_in('id', $plate_ids)->get('ci_plates')->result_array();
				print_r($records);
				if(count($records) > 0){ 
					$delimiter = ","; 
					$filename = "Plates Report.csv"; 
					 
					// Create a file pointer 
					$f = fopen('php://memory', 'w'); 
					 
					// Set column headers 
					$fields = array('Id', 'plate_number', 'Plate type', 'dmv_contact_name', 'dmv_contact_address', 'dmv_contact_city', 'dmv_contact_state', 'Zip code', 'dmv_vehicle_vin', 'dmv_vehicle_body', 'dmv_vehicle_year', 'dmv_vehicle_make', 'dmv_vehicle_color', 'DMV Date checked', 'DMV Status', 'Expiration Date', 'dmv_contact_sex', 'Birth Date', 'County', 'MID Number'); 
					fputcsv($f, $fields, $delimiter); 
					 
					foreach ($records as $val){
						$lineData = array($val['id'], $val['plate_number'], $val['dmv_plate_type'], $val['dmv_contact_name'], $val['dmv_contact_address'], $val['dmv_contact_city'], $val['dmv_contact_state'], $val['dmv_contact_zipcode'], $val['dmv_vehicle_vin'], $val['dmv_vehicle_body'], $val['dmv_vehicle_year'], $val['dmv_vehicle_make'], $val['dmv_vehicle_color'], $val['dmv_last_checked_date'], $val['dmv_status'], $val['dmv_expiration_date'], $val['dmv_contact_sex'], $val['dmv_contact_birth_date'], $val['dmv_contact_county'], $val['dmv_mid_number']); 
						fputcsv($f, $lineData, $delimiter); 
					} 
					 
					// Move back to beginning of file 
					fseek($f, 0); 
					 
					// Set headers to download file rather than displayed 
					header('Content-Type: text/csv'); 
					header('Content-Disposition: attachment; filename="' . $filename . '";'); 
					 
					//output all remaining data on a file pointer 
					fpassthru($f); 
				}
			}
		}
		public function exportallcsv(){
			$records = $this->plateModel->get_all_simple_plates();
			if(count($records) > 0){ 
				$delimiter = ","; 
				$filename = "Plates Report.csv"; 
				 
				// Create a file pointer 
				$f = fopen('php://memory', 'w'); 
			
				
				// Set column headers 
				$fields = array('Id', 'plate_number', 'Plate type', 'dmv_contact_name', 'dmv_contact_address', 'dmv_contact_city', 'dmv_contact_state', 'Zip code', 'dmv_vehicle_vin', 'dmv_vehicle_body', 'dmv_vehicle_year', 'dmv_vehicle_make', 'dmv_vehicle_color', 'DMV Date checked', 'DMV Status', 'Expiration Date', 'dmv_contact_sex', 'Birth Date', 'County', 'MID Number'); 
				fputcsv($f, $fields, $delimiter); 
				 
				foreach ($records as $val){
					$lineData = array($val['plate_id'], $val['plate_number'], $val['dmv_plate_type'], $val['dmv_contact_name'], $val['dmv_contact_address'], $val['dmv_contact_city'], $val['dmv_contact_state'], $val['dmv_contact_zipcode'], $val['dmv_vehicle_vin'], $val['dmv_vehicle_body'], $val['dmv_vehicle_year'], $val['dmv_vehicle_make'], $val['dmv_vehicle_color'], $val['dmv_last_checked_date'], $val['dmv_status'], $val['dmv_expiration_date'], $val['dmv_contact_sex'], $val['dmv_contact_birth_date'], $val['dmv_contact_county'], $val['dmv_mid_number']); 
					fputcsv($f, $lineData, $delimiter); 
				} 
				 
				// Move back to beginning of file 
				fseek($f, 0); 
				 
				// Set headers to download file rather than displayed 
				header('Content-Type: text/csv'); 
				header('Content-Disposition: attachment; filename="' . $filename . '";'); 
				 
				//output all remaining data on a file pointer 
				fpassthru($f); 
			}
		}
		public function plate_search($plate){
			$json_resp = array();
			$json_resp['plate_details'] = $this->plateModel->get_plates_by_plate($plate);
			echo json_encode($json_resp);
			exit;
		}
		
		
		// Shang
		public function dmv() {
			$userinfo = $this->plateModel->get_platforms('secap.dmv.ny.gov');
			// echo json_encode($records['username']);
	
			$username = $userinfo['username'];
			$password = $userinfo['password'];
	
			$scrape_url = 'https://secap.dmv.ny.gov/unprotected/login.asp?TYPE=33554433&REALMOID=06-93023bcb-6c25-4aa3-a201-9f93831e5bb4&GUID=&SMAUTHREASON=0&METHOD=GET&SMAGENTNAME=-SM-K4n8LqMu7Cigm4zPM4qbXjq%2f61Tg9RG2zD8XN2Fv5DTpKL1rfVBO3wWEMRU0MACS&TARGET=-SM-https%3a%2f%2fsecap%2edmv%2eny%2egov%2fvpass%2fvpass_initial%2ecfm';
			$agent = 'Mozilla/5.0 (Ubuntu; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36';
	
			$records = $this->plateModel->get_plates();
			if ( $username && $password && count($records) ) {
				foreach ($records as $record) {
					$plate_id = $record['plate_number'];
					$exp_date = $record['dmv_expiration_date'];
					$today = date('m/d/Y');
	
					if ( empty($exp_date) || (strtotime($today) > strtotime($exp_date)) ) {
					// if ( empty($exp_date) ) {
						$client  = new Client(HttpClient::create(['timeout' => 60]));
						$client->setServerParameter('user-agent', $agent);
						$crawler = $client->request('GET', $scrape_url);
						$login_form = $crawler->selectButton('Login')->form();
						$crawler = $client->submit($login_form, [
							'USER'      => $username,
							'PASSWORD'  => $password
						]);
		
						rand(2, 5);
		
						// Find DMV Menu (VPass)
						if ( $crawler->filter("form[name='vpass_menu'] .left > a:nth-child(3)")->count() ) {
							$registration_inquiry_link = $crawler->selectLink(trim('PREED Registration Inquiry'))->link();
							$crawler = $client->click($registration_inquiry_link);
		
							rand(2, 5);
		
							// Electronic Terms of Service
							if ( $crawler->filter("form[name='emoupage1'] input[name='accept_agreement']")->count() ) {
								$form = $crawler->selectButton('Submit')->form();
								$form['accept_agreement']->select('Y');
								$crawler = $client->submit($form);
		
								rand(2, 5);
		
								// Agree form
								if ( $crawler->filter("#DPPAY")->count() ) {
									$form = $crawler->selectButton('I Agree')->form();
									$crawler = $client->submit($form);
		
									rand(2, 5);
		
									// Search plate information
									if ( $crawler->filter("input[name='plateIn']")->count() ) {
										$search_form = $crawler->selectButton('Search')->form();
										$crawler = $client->submit($search_form, [
											'plateIn' => $plate_id
										]);
		
										rand(1, 2);
										
										if ( $crawler->filter("#pgDetailArea .sectionDetails") ) {
	
											$arr_status = $crawler->filter('#pgDetailArea .sectionDetails .statusLine .flagText')->each(function ($node) use ($client) {
												return $node->text();
											});
											$status = isset($arr_status[0]) ? trim($arr_status[0]) : '';
		
											$arr_table0 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
												return $node->text();
											});
											$plate_type      = isset($arr_table0[0]) ? explode(' - ', trim($arr_table0[0]))[1] : '';
											// $effective_date  = isset($arr_table0[1]) ? $arr_table0[1] : '';
											$expiration_date = isset($arr_table0[2]) ? trim($arr_table0[2]) : '';
		
											$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
												return $node->text();
											});
											$name  = isset($arr_table1[0]) ? addslashes($arr_table1[0]) : '';
											$sex   = isset($arr_table1[1]) ? trim($arr_table1[1]) : '';
											$birth = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
		
											$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
												return $node->text();
											});
											$address = isset($arr_table1[0]) ? addslashes(trim($arr_table1[0])) : '';
											$county  = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
		
											$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(3)->filter('.dataValue')->each(function ($node) use ($client) {
												return $node->text();
											});
											$arr_csz = isset($arr_table1[0]) ? trim($arr_table1[0]) : '';
											$city = ''; $state = ''; $zipcode = 0;
											if ( $arr_csz != '' ) {
												$temp    = explode(', ', $arr_csz);
												$city    = trim($temp[0]);
												$state   = explode(' ', trim($temp[1]))[0];
												$zipcode = explode(' ', trim($temp[1]))[1];
											}
											$mid_number = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
		
											$arr_table2 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
												return $node->text();
											});
											$vin   = isset($arr_table2[0]) ? trim($arr_table2[0]) : '';
											$body  = isset($arr_table2[1]) ? trim($arr_table2[1]) : '';
											$year  = isset($arr_table2[2]) ? trim($arr_table2[2]) : 0;
											$make  = isset($arr_table2[3]) ? trim($arr_table2[3]) : '';
											$color = isset($arr_table2[4]) ? trim($arr_table2[4]) : '';
		
											// $dmv_last_checked_date = date('m/d/Y');
	
											$dvm_data = array(
												'status'          => $status,
												'dmv_plate_type'      => $plate_type,
												'expiration_date' => $expiration_date,
												'dmv_contact_name'            => $name,
												'dmv_contact_sex'             => $sex,
												'birth'           => $birth,
												'dmv_contact_address'         => $address,
												'county'          => $county,
												'dmv_contact_city'            => $city,
												'dmv_contact_state'           => $state,
												'zipcode'         => $zipcode,
												'dmv_mid_number'      => $mid_number,
												'dmv_vehicle_vin'             => $vin,
												'dmv_vehicle_body'            => $body,
												'dmv_vehicle_year'            => $year,
												'dmv_vehicle_make'            => $make,
												'dmv_vehicle_color'           => $color
											);
											// print_r($dvm_data);
											// die;
		
											// Update fetched data to the database
											echo $this->update_dvm( $dvm_data, $plate_id );
										}
									}
								}
							}
						}
						else if ( $crawler->filter("form[name='emoupage1'] input[name='accept_agreement']")->count() ) {	// Electronic Terms of Service
							$form = $crawler->selectButton('Submit')->form();
							$form['accept_agreement']->select('Y');
							$crawler = $client->submit($form);
	
							rand(2, 5);
	
							// Agree form
							if ( $crawler->filter("#DPPAY")->count() ) {
								$form = $crawler->selectButton('I Agree')->form();
								$crawler = $client->submit($form);
	
								rand(2, 5);
	
								// Search plate information
								if ( $crawler->filter("input[name='plateIn']")->count() ) {
									$search_form = $crawler->selectButton('Search')->form();
									$crawler = $client->submit($search_form, [
										'plateIn' => $plate_id
									]);
	
									rand(1, 2);
									
									if ( $crawler->filter("#pgDetailArea .sectionDetails") ) {
	
										$arr_status = $crawler->filter('#pgDetailArea .sectionDetails .statusLine .flagText')->each(function ($node) use ($client) {
											return $node->text();
										});
										$status = isset($arr_status[0]) ? trim($arr_status[0]) : '';
	
										$arr_table0 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
											return $node->text();
										});
										$plate_type      = isset($arr_table0[0]) ? explode(' - ', trim($arr_table0[0]))[1] : '';
										// $effective_date  = isset($arr_table0[1]) ? $arr_table0[1] : '';
										$expiration_date = isset($arr_table0[2]) ? trim($arr_table0[2]) : '';
	
										$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
											return $node->text();
										});
										$name  = isset($arr_table1[0]) ? addslashes($arr_table1[0]) : '';
										$sex   = isset($arr_table1[1]) ? trim($arr_table1[1]) : '';
										$birth = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
										$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
											return $node->text();
										});
										$address = isset($arr_table1[0]) ? addslashes(trim($arr_table1[0])) : '';
										$county  = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
										$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(3)->filter('.dataValue')->each(function ($node) use ($client) {
											return $node->text();
										});
										$arr_csz = isset($arr_table1[0]) ? trim($arr_table1[0]) : '';
										$city = ''; $state = ''; $zipcode = 0;
										if ( $arr_csz != '' ) {
											$temp    = explode(', ', $arr_csz);
											$city    = trim($temp[0]);
											$state   = explode(' ', trim($temp[1]))[0];
											$zipcode = explode(' ', trim($temp[1]))[1];
										}
										$mid_number = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
										$arr_table2 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
											return $node->text();
										});
										$vin   = isset($arr_table2[0]) ? trim($arr_table2[0]) : '';
										$body  = isset($arr_table2[1]) ? trim($arr_table2[1]) : '';
										$year  = isset($arr_table2[2]) ? trim($arr_table2[2]) : 0;
										$make  = isset($arr_table2[3]) ? trim($arr_table2[3]) : '';
										$color = isset($arr_table2[4]) ? trim($arr_table2[4]) : '';
	
										// $dmv_last_checked_date = date('m/d/Y');
	
										$dvm_data = array(
											'status'          => $status,
											'dmv_plate_type'      => $plate_type,
											'expiration_date' => $expiration_date,
											'dmv_contact_name'            => $name,
											'dmv_contact_sex'             => $sex,
											'birth'           => $birth,
											'dmv_contact_address'         => $address,
											'county'          => $county,
											'dmv_contact_city'            => $city,
											'dmv_contact_state'           => $state,
											'zipcode'         => $zipcode,
											'dmv_mid_number'      => $mid_number,
											'dmv_vehicle_vin'             => $vin,
											'dmv_vehicle_body'            => $body,
											'dmv_vehicle_year'            => $year,
											'dmv_vehicle_make'            => $make,
											'dmv_vehicle_color'           => $color
										);
										// print_r($dvm_data);
										// die;
	
										// Update fetched data to the database
										echo $this->update_dvm( $dvm_data, $plate_id );
									}
								}
							}
						}
						else if ( $crawler->filter("#DPPAY")->count() ) {	// Agree form
							$form = $crawler->selectButton('I Agree')->form();
							$crawler = $client->submit($form);
	
							rand(2, 5);
	
							// Search plate information
							if ( $crawler->filter("input[name='plateIn']")->count() ) {
								$search_form = $crawler->selectButton('Search')->form();
								$crawler = $client->submit($search_form, [
									'plateIn' => $plate_id
								]);
	
								rand(1, 2);
								
								if ( $crawler->filter("#pgDetailArea .sectionDetails") ) {
	
									$arr_status = $crawler->filter('#pgDetailArea .sectionDetails .statusLine .flagText')->each(function ($node) use ($client) {
										return $node->text();
									});
									$status = isset($arr_status[0]) ? trim($arr_status[0]) : '';
	
									$arr_table0 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
										return $node->text();
									});
									$plate_type      = isset($arr_table0[0]) ? explode(' - ', trim($arr_table0[0]))[1] : '';
									// $effective_date  = isset($arr_table0[1]) ? $arr_table0[1] : '';
									$expiration_date = isset($arr_table0[2]) ? trim($arr_table0[2]) : '';
	
									$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
										return $node->text();
									});
									$name  = isset($arr_table1[0]) ? addslashes($arr_table1[0]) : '';
									$sex   = isset($arr_table1[1]) ? trim($arr_table1[1]) : '';
									$birth = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
									$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
										return $node->text();
									});
									$address = isset($arr_table1[0]) ? addslashes(trim($arr_table1[0])) : '';
									$county  = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
									$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(3)->filter('.dataValue')->each(function ($node) use ($client) {
										return $node->text();
									});
									$arr_csz = isset($arr_table1[0]) ? trim($arr_table1[0]) : '';
									$city = ''; $state = ''; $zipcode = 0;
									if ( $arr_csz != '' ) {
										$temp    = explode(', ', $arr_csz);
										$city    = trim($temp[0]);
										$state   = explode(' ', trim($temp[1]))[0];
										$zipcode = explode(' ', trim($temp[1]))[1];
									}
									$mid_number = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
									$arr_table2 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
										return $node->text();
									});
									$vin   = isset($arr_table2[0]) ? trim($arr_table2[0]) : '';
									$body  = isset($arr_table2[1]) ? trim($arr_table2[1]) : '';
									$year  = isset($arr_table2[2]) ? trim($arr_table2[2]) : 0;
									$make  = isset($arr_table2[3]) ? trim($arr_table2[3]) : '';
									$color = isset($arr_table2[4]) ? trim($arr_table2[4]) : '';
	
									// $dmv_last_checked_date = date('m/d/Y');
	
									$dvm_data = array(
										'status'          => $status,
										'dmv_plate_type'      => $plate_type,
										'expiration_date' => $expiration_date,
										'dmv_contact_name'            => $name,
										'dmv_contact_sex'             => $sex,
										'birth'           => $birth,
										'dmv_contact_address'         => $address,
										'county'          => $county,
										'dmv_contact_city'            => $city,
										'dmv_contact_state'           => $state,
										'zipcode'         => $zipcode,
										'dmv_mid_number'      => $mid_number,
										'dmv_vehicle_vin'             => $vin,
										'dmv_vehicle_body'            => $body,
										'dmv_vehicle_year'            => $year,
										'dmv_vehicle_make'            => $make,
										'dmv_vehicle_color'           => $color
									);
									// print_r($dvm_data);
									// die;
	
									// Update fetched data to the database
									echo $this->update_dvm( $dvm_data, $plate_id );
								}
							}
						}
						else if ( $crawler->filter("input[name='plateIn']")->count() ) {		// Search plate information
							$search_form = $crawler->selectButton('Search')->form();
							$crawler = $client->submit($search_form, [
								'plateIn' => $plate_id
							]);
	
							rand(1, 2);
							
							if ( $crawler->filter("#pgDetailArea .sectionDetails") ) {
	
								$arr_status = $crawler->filter('#pgDetailArea .sectionDetails .statusLine .flagText')->each(function ($node) use ($client) {
									return $node->text();
								});
								$status = isset($arr_status[0]) ? trim($arr_status[0]) : '';
	
								$arr_table0 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
									return $node->text();
								});
								$plate_type      = isset($arr_table0[0]) ? explode(' - ', trim($arr_table0[0]))[1] : '';
								// $effective_date  = isset($arr_table0[1]) ? $arr_table0[1] : '';
								$expiration_date = isset($arr_table0[2]) ? trim($arr_table0[2]) : '';
	
								$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
									return $node->text();
								});
								$name  = isset($arr_table1[0]) ? addslashes($arr_table1[0]) : '';
								$sex   = isset($arr_table1[1]) ? trim($arr_table1[1]) : '';
								$birth = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
								$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
									return $node->text();
								});
								$address = isset($arr_table1[0]) ? addslashes(trim($arr_table1[0])) : '';
								$county  = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
								$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(3)->filter('.dataValue')->each(function ($node) use ($client) {
									return $node->text();
								});
								$arr_csz = isset($arr_table1[0]) ? trim($arr_table1[0]) : '';
								$city = ''; $state = ''; $zipcode = 0;
								if ( $arr_csz != '' ) {
									$temp    = explode(', ', $arr_csz);
									$city    = trim($temp[0]);
									$state   = explode(' ', trim($temp[1]))[0];
									$zipcode = explode(' ', trim($temp[1]))[1];
								}
								$mid_number = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
								$arr_table2 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
									return $node->text();
								});
								$vin   = isset($arr_table2[0]) ? trim($arr_table2[0]) : '';
								$body  = isset($arr_table2[1]) ? trim($arr_table2[1]) : '';
								$year  = isset($arr_table2[2]) ? trim($arr_table2[2]) : 0;
								$make  = isset($arr_table2[3]) ? trim($arr_table2[3]) : '';
								$color = isset($arr_table2[4]) ? trim($arr_table2[4]) : '';
	
								// $dmv_last_checked_date = date('m/d/Y');
	
								$dvm_data = array(
									'status'          => $status,
									'dmv_plate_type'      => $plate_type,
									'expiration_date' => $expiration_date,
									'dmv_contact_name'            => $name,
									'dmv_contact_sex'             => $sex,
									'birth'           => $birth,
									'dmv_contact_address'         => $address,
									'county'          => $county,
									'dmv_contact_city'            => $city,
									'dmv_contact_state'           => $state,
									'zipcode'         => $zipcode,
									'dmv_mid_number'      => $mid_number,
									'dmv_vehicle_vin'             => $vin,
									'dmv_vehicle_body'            => $body,
									'dmv_vehicle_year'            => $year,
									'dmv_vehicle_make'            => $make,
									'dmv_vehicle_color'           => $color
								);
								// print_r($dvm_data);
								// die;
	
								// Update fetched data to the database
								echo $this->update_dvm( $dvm_data, $plate_id );
							}
						}
						else if ( $crawler->filter("#pgDetailArea .sectionDetails") ) {
							$arr_status = $crawler->filter('#pgDetailArea .sectionDetails .statusLine .flagText')->each(function ($node) use ($client) {
								return $node->text();
							});
							$status = trim($arr_status[0]);
	
							$arr_table0 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
								return $node->text();
							});
							$plate_type      = isset($arr_table0[0]) ? explode(' - ', trim($arr_table0[0]))[1] : '';
							// $effective_date  = isset($arr_table0[1]) ? $arr_table0[1] : '';
							$expiration_date = isset($arr_table0[2]) ? trim($arr_table0[2]) : '';
	
							$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(0)->filter('.dataValue')->each(function ($node) use ($client) {
								return $node->text();
							});
							$name  = isset($arr_table1[0]) ? addslashes($arr_table1[0]) : '';
							$sex   = isset($arr_table1[1]) ? trim($arr_table1[1]) : '';
							$birth = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
							$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
								return $node->text();
							});
							$address = isset($arr_table1[0]) ? addslashes(trim($arr_table1[0])) : '';
							$county  = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
							$arr_table1 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(1)->filter('tr')->eq(3)->filter('.dataValue')->each(function ($node) use ($client) {
								return $node->text();
							});
							$arr_csz = isset($arr_table1[0]) ? trim($arr_table1[0]) : '';
							$city = ''; $state = ''; $zipcode = '';
							if ( $arr_csz != '' ) {
								$temp    = explode(', ', $arr_csz);
								$city    = trim($temp[0]);
								$state   = explode(' ', trim($temp[1]))[0];
								$zipcode = explode(' ', trim($temp[1]))[1];
							}
							$mid_number = isset($arr_table1[2]) ? trim($arr_table1[2]) : '';
	
							$arr_table2 = $crawler->filter('#pgDetailArea .sectionDetails > table')->eq(2)->filter('.dataValue')->each(function ($node) use ($client) {
								return $node->text();
							});
							$vin   = isset($arr_table2[0]) ? trim($arr_table2[0]) : '';
							$body  = isset($arr_table2[1]) ? trim($arr_table2[1]) : '';
							$year  = isset($arr_table2[2]) ? trim($arr_table2[2]) : '';
							$make  = isset($arr_table2[3]) ? trim($arr_table2[3]) : '';
							$color = isset($arr_table2[4]) ? trim($arr_table2[4]) : '';
	
							// $dmv_last_checked_date = date('m/d/Y');
	
							$dvm_data = array(
								'status'          => $status,
								'dmv_plate_type'      => $plate_type,
								'expiration_date' => $expiration_date,
								'dmv_contact_name'            => $name,
								'dmv_contact_sex'             => $sex,
								'birth'           => $birth,
								'dmv_contact_address'         => $address,
								'county'          => $county,
								'dmv_contact_city'            => $city,
								'dmv_contact_state'           => $state,
								'zipcode'         => $zipcode,
								'dmv_mid_number'      => $mid_number,
								'dmv_vehicle_vin'             => $vin,
								'dmv_vehicle_body'            => $body,
								'dmv_vehicle_year'            => $year,
								'dmv_vehicle_make'            => $make,
								'dmv_vehicle_color'           => $color
							);
							// print_r($dvm_data);
							// die;
	
							// Update fetched data to the database
							echo $this->update_dvm( $dvm_data, $plate_id );
						}
	
						$client  = null;
						$crawler = null;
					}
		
					rand(5, 8);
				}
				echo 'All information fetched';
			}
			else echo 'There is no data in your database';
		}
		
		public function bulk_update_dmv_status() {
			$datas = json_decode($this->request->getPost('data'));
			// print_r($datas);
			if ( is_array($datas) && count( $datas ) ) {
				foreach ( $datas as $data ) {
					if ( !empty($data->plate) ) {
						$record = $this->plateModel->get_plates_by_plate( $data->plate );
	
						// If plate exists already, but dmv_status is empty or blank
						if ( is_array($record) && isset($record['id']) ) {
							if ( is_null($record['dmv_status']) )
								$this->dmv_api( $data->plate, 0, $record['id'] );
						}
						else {	// If plate not exist, then add plate and check dmv_status
							$this->plateModel->add_plate( array('plate_number' => $data->plate) );
							$this->dmv_api( $data->plate );
						}
					}
				}
				
				echo 'success';
			}
			else 'err';
		}
	
		public function dmv_api( $plate = '', $violation_id = 0 ) {
			$userinfo = $this->plateModel->get_platforms('secap.dmv.ny.gov');
			$username = $userinfo['username'];
			$password = $userinfo['password'];
	
			$soapUrl = "https://wsc.dmv.ny.gov/sst/runtime.asvc/com.actional.intermediary.preed?WSDL"; // asmx URL of WSDL
			$records = null;
	
			$is_exist = $this->plateModel->plate_exist($plate);
			if ( $is_exist ) {
				$records = $this->plateModel->get_plates($plate);
			}
			else {
				if ( $plate && $violation_id ) {
					$this->plateModel->add_plate(array('plate_number' => $plate));
					$this->violationModel->edit_violation(array('plate_number' => $plate), $violation_id);
					$records = $this->plateModel->get_plates($plate);
				}
			}
	
			/*
			$records = $this->plateModel->get_plates($plate);
			// This is for DMV lookup in violation edit view.
			if ( !count($records) && $plate && $violation_id ) {
				$this->plateModel->add_plate(array('plate_number' => $plate));
				$this->violationModel->edit_violation(array('plate_number' => $plate), $violation_id);
				$records = $this->plateModel->get_plates($plate);
			}
			*/

		
	
			if ( $username && $password && count($records) ) {
	 			
				foreach ($records as $record) {
					$plate_id = $record['plate_number'];
					$exp_date = $record['dmv_expiration_date'];
					$today = date('m/d/Y');
	
					if ( !empty($plate) || empty($exp_date) || (strtotime($today) > strtotime($exp_date)) ) {
					// if ( empty($exp_date) ) {
						// xml post structure
						$input_xml='
						<?xml version="1.0" encoding="utf-8"?>
						<soap:Envelope
							xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
							xmlns:xsd="http://www.w3.org/2001/XMLSchema"
							xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
							<soap:Body>
								<DmvPreedTransaction
									xmlns="http://tempuri.org/Preedweb/Preed">
									<TransXmlStruct>
										<user>'.$username.'</user>
										<password>'.$password.'</password>
										<IpAddress>205.209.120.250</IpAddress>
										<PlateNumber>'.$plate_id.'</PlateNumber>
										<TransactionCode>RCUR</TransactionCode>
									</TransXmlStruct>
								</DmvPreedTransaction>
							</soap:Body>
						</soap:Envelope>';
					// 	print_r($input_xml);
	
						$headers = array(
							"Content-type: text/xml;charset=\"utf-8\"",
							"Accept: text/xml",
							"Cache-Control: no-cache",
							"Pragma: no-cache",
							"SOAPAction: http://tempuri.org/Preedweb/Preed/DmvPreedTransaction", 
							"Content-length: " . strlen($input_xml),
						); //SOAPAction: your op URL
					// 	print_r($headers);
	
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $soapUrl);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml); // the SOAP request
						curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password); // username and password - declared at the top of the doc
						curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
						curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
						$response = curl_exec($ch); 
				
						curl_close($ch);
	
						// converting
						$response1 = str_replace("<soap:Body>" , "", $response);
						$response2 = str_replace("</soap:Body>", "", $response1);
	
						// convertingc to XML
						$parser = simplexml_load_string($response2);
						$array_data = json_decode(json_encode($parser), true);

					 	// print_r($array_data);
					 	// die;
	
						if ( is_array($array_data) && count($array_data) && $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['CompletionCde'] == 'SUCCESS' ) {
							// Status
							$status = '';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Suspended'] == 'Y' )
								$status .= 'SUSPENDED, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Revoked'] == 'Y' )
								$status .= 'REVOKED, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Scofflaw'] == 'Y' )
								$status .= 'SCOFFLAW, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Stolen'] == 'Y' )
								$status .= 'STOLEN, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Surrendered'] == 'Y' )
								$status .= 'SURRENDERED, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Reg_Expired'] == 'Y' )
								$status .= 'EXPIRED, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Renewal_Denied'] == 'Y' )
								$status .= 'RENEWAL, DENIDED, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_History'] == 'Y' )
								$status .= 'HISTORY, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_Expired'] == 'Y' )
								$status .= 'EXPIRED, ';
							if ( $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Status_replaced'] == 'Y' )
								$status .= 'REPLACED, ';
	
							if ( $status == '' )
								$status = 'VALID';
							else
								$status = substr( $status, 0, strlen($status) - 2 );
	
							// Plate Type
							$plate_type = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Plate_Class_Out']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Plate_Class_Out']) )
								$plate_type = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Plate_Class_Out'];
	
							// Expiration Date
							$expiration_date = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['expDate']) && 
								 is_numeric($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['expDate']) )
								$expiration_date = $this->convert_date_format($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['expDate']);
	
							// Name
							$name = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_name']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_name']) )
								$name = addslashes($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_name']);
	
							// Sex
							$sex = 'N/A';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_sex']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_sex']) )
								$sex = ($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_sex'] == 'F')?'FEMALE':'MALE';
	
							// Birthday
							$birth = 'N/A';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['DOB']) && 
								 is_numeric($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['DOB']) )
								$birth = $this->convert_date_format($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['DOB']);
	
							// address
							$address = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['street']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['street']) )
								$address = addslashes($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['street']);
	
							// City
							$city = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_city']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_city']) )
								$city = addslashes($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_city']);
	
							// State
							$state = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_state']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_state']) )
								$state = addslashes($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_contact_state']);
	
							// Zip
							$zipcode = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Zip']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Zip']) )
								$zipcode = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Zip'];
	
							// County
							$county = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['county']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['county']) )
								$county = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['county'];
	
							// Mid number
							$mid_number = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['MIDNum']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['MIDNum']) )
								$mid_number = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['MIDNum'];
	
							// Vin
							$vin = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_vehicle_vin']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_vehicle_vin']) )
								$vin = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['dmv_vehicle_vin'];
	
							// Body
							$body = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehBdyTypCde']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehBdyTypCde']) )
								$body = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehBdyTypCde'];
	
							// Year
							$year = 0;
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehYear']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehYear']) )
								$year = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehYear'];
	
							// Make
							$make = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehMake']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehMake']) )
								$make = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehMake'];
	
							// Color
							$color = '';
							if ( !empty($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehColor']) && !is_array($array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehColor']) )
								$color = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['VehColor'];
	
							$dvm_data = array(
								'status'          => $status,
								'dmv_plate_type'      => $plate_type,
								'expiration_date' => $expiration_date,
								'dmv_contact_name'            => $name,
								'dmv_contact_sex'             => $sex,
								'dmv_contact_birth_date'           => $birth,
								'dmv_contact_address'         => $address,
								'dmv_contact_county'          => $county,
								'dmv_contact_city'            => $city,
								'dmv_contact_state'           => $state,
								'dmv_contact_zipcode'         => $zipcode,
								'dmv_mid_number'      => $mid_number,
								'dmv_vehicle_vin'             => $vin,
								'dmv_vehicle_body'            => $body,
								'dmv_vehicle_year'            => $year,
								'dmv_vehicle_make'            => $make,
								'dmv_vehicle_color'           => $color
							);
					 	//	print_r($dvm_data);
					// 		echo '<br>';
	
							// Update fetched data to the database
							$this->update_dvm( $dvm_data, $plate_id );
						}
						else {
							$error = $array_data['DmvPreedTransactionResponse']['DmvPreedTransactionResult']['Errmsg'];
							$query = "
								update cms_plate
								set
								
								dmv_status = '$error',
								dmv_last_checked_date = '".date('m/d/Y')."'
					
								where plate_number = '$plate_id'
							";
							$this->plateModel->update_dmv( $query );
						}
					}
					// sleep(0.5);
				}
			}
		}
	
		public function update_dvm( $dvm_data, $plate_id ) {
			$query = "
				update cms_plate
				set
				
				dmv_plate_type = '".$dvm_data['dmv_plate_type']."',
				dmv_expiration_date = '".$dvm_data['expiration_date']."',
				dmv_contact_name = '".$dvm_data['dmv_contact_name']."',
				dmv_contact_address = '".$dvm_data['dmv_contact_address']."',
				dmv_contact_city = '".$dvm_data['dmv_contact_city']."',
				dmv_contact_state = '".$dvm_data['dmv_contact_state']."',
				dmv_contact_zipcode = '".$dvm_data['dmv_contact_zipcode']."',
				dmv_vehicle_vin = '".$dvm_data['dmv_vehicle_vin']."',
				dmv_vehicle_body = '".$dvm_data['dmv_vehicle_body']."',
				dmv_vehicle_year = '".$dvm_data['dmv_vehicle_year']."',
				dmv_vehicle_make = '".$dvm_data['dmv_vehicle_make']."',
				dmv_vehicle_color = '".$dvm_data['dmv_vehicle_color']."',
				dmv_status = '".$dvm_data['status']."',
				dmv_contact_sex = '".$dvm_data['dmv_contact_sex']."',
				dmv_contact_birth_date = '".$dvm_data['dmv_contact_birth_date']."',
				dmv_contact_county = '".$dvm_data['dmv_contact_county']."',
				dmv_mid_number = '".$dvm_data['dmv_mid_number']."',
				dmv_last_checked_date = '".date('Y-m-d H:i:s')."'
	
				where plate_number = '$plate_id'
			";
	// 		echo $query.'<br>';
	// 		return $query;
			$this->plateModel->update_dmv( $query );
			return $plate_id . ' is updated.';
		}
		
		public function convert_date_format( $str ) {
			$temp_year  = substr($str, 0, 4);
			$temp_month = substr($str, 4, 2);
			$temp_day   = substr($str, -2);
			return $temp_month . '/' . $temp_day . '/' . $temp_year;
		}
		
		
		public function is_exist(){
			$json_resp = array();
			$plate = $this->request->getPost('plate_number');
			if(!empty($plate)){
				$json_resp['records'] = $this->plateModel->plate_exist($plate);
				$json_resp['success'] = true;
			}else $json_resp['success'] = false;
			echo json_encode($json_resp);
			exit;
		}
}
