<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Mmc extends BaseController
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

	public function index()
	{

		$data['view'] = 'admin/mmc/mmc_list';
		return view('layout', $data);
	}

	public function datatable_json()
	{

		$records = $this->mmcModel->get_all_mmcs();
		$data = array();
	
		foreach ($records['data'] as $row) {
			$data[] = array(
				'<input type="checkbox" class="mmc_check" name="mmc_id[]" value="' . $row['mmc_id'] . '"/>',
				$row['mmc_id'],
				$row['mmc_uuid'],
				$row['video_name'],
				$row['image_name'],
				$row['mmc_plate_number'],
				$row['mmc_plate_region'],
				$row['mmc_plate_image_coordinates'],
				$row['mmc_plate_score'],
				$row['mmc_vehicle_type_body'],
				$row['mmc_vehicle_make'],
				$row['mmc_vehicle_model'],
				$row['mmc_vehicle_color'],
				$row['mmc_vehicle_direction'],
				$row['video_creation_date'],
				'<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/mmc/edit/' . $row['mmc_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/mmc/del/' . $row['mmc_id']) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>',
			);
		}
		$records['data'] = $data;

		echo json_encode($records);
	}

	public function add()
	{

		if ($this->request->getPost('submit')) {
			$validation = \Config\Services::validation();

			$validation->setRule('video_name', 'video_name', 'trim|required');
			$validation->setRule('image_name', 'image_name', 'trim|required');
			$validation->setRule('mmc_plate_number', 'mmc_plate_number', 'trim|required');
			$validation->setRule('mmc_plate_region', 'mmc_plate_region', 'trim');
			$validation->setRule('mmc_plate_image_coordinates', 'mmc_plate_image_coordinates', 'trim');
			$validation->setRule('mmc_plate_score', 'mmc_plate_score', 'trim');
			$validation->setRule('mmc_vehicle_type_body', 'mmc_vehicle_type_body', 'trim');
			$validation->setRule('mmc_vehicle_make', 'mmc_vehicle_make', 'trim');
			$validation->setRule('mmc_vehicle_model', 'mmc_vehicle_model', 'trim');
			$validation->setRule('mmc_vehicle_color', 'mmc_vehicle_color', 'trim');
			$validation->setRule('mmc_vehicle_direction', 'mmc_vehicle_direction', 'trim');
			$validation->setRule('video_creation_date', 'video_creation_date', 'trim');

			if ($validation->withRequest($this->request)->run()) {
				$data = array(
					'video_name' => $this->request->getPost('video_name'),
					'image_name' => $this->request->getPost('image_name'),
					'mmc_plate_number' => $this->request->getPost('mmc_plate_number'),
					'mmc_plate_region' => $this->request->getPost('mmc_plate_region'),
					'mmc_plate_image_coordinates' => $this->request->getPost('mmc_plate_image_coordinates'),
					'mmc_plate_score' => $this->request->getPost('mmc_plate_score'),
					'mmc_vehicle_type_body' => $this->request->getPost('mmc_vehicle_type_body'),
					'mmc_vehicle_make' => $this->request->getPost('mmc_vehicle_make'),
					'mmc_vehicle_model' => $this->request->getPost('mmc_vehicle_model'),
					'mmc_vehicle_color' => $this->request->getPost('mmc_vehicle_color'),
					'mmc_vehicle_direction' => $this->request->getPost('mmc_vehicle_direction'),
					'video_creation_date' => $this->request->getPost('video_creation_date')
				);
				//		$data = $this->security->xss_clean($data);
				$result = $this->mmcModel->add_mmc($data);
				if ($result) {
					// Add User Activity
					//	$this->activity_model->add(1);
					$this->session->setFlashdata('msg', 'MMC has been added successfully!');
					return redirect()->to(base_url('admin/mmc'));
				}
			} else {
				$data['view'] = 'admin/mmc/mmc_add';
				return view('layout', $data);
			}
		} else {
			$data['view'] = 'admin/mmc/mmc_add';
			return view('layout', $data);
		}
	}

	public function edit($id = 0)
	{

		if ($this->request->getPost('submit')) {

			$validation = \Config\Services::validation();

			$validation->setRule('video_name', 'video_name', 'trim|required');
			$validation->setRule('image_name', 'image_name', 'trim|required');
			$validation->setRule('mmc_plate_number', 'mmc_plate_number', 'trim|required');
			$validation->setRule('mmc_plate_region', 'mmc_plate_region', 'trim');
			$validation->setRule('mmc_plate_image_coordinates', 'mmc_plate_image_coordinates', 'trim');
			$validation->setRule('mmc_plate_score', 'mmc_plate_score', 'trim');
			$validation->setRule('mmc_vehicle_type_body', 'mmc_vehicle_type_body', 'trim');
			$validation->setRule('mmc_vehicle_make', 'mmc_vehicle_make', 'trim');
			$validation->setRule('mmc_vehicle_model', 'mmc_vehicle_model', 'trim');
			$validation->setRule('mmc_vehicle_color', 'mmc_vehicle_color', 'trim');
			$validation->setRule('mmc_vehicle_direction', 'mmc_vehicle_direction', 'trim');
			$validation->setRule('video_creation_date', 'video_creation_date', 'trim');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['mmc_details'] = $this->mmcModel->get_mmc_by_id($id);

				$data['view'] = 'admin/mmc/mmc_edit';
				return view('layout', $data);
			} else {
				$data = array(
					'video_name' => $this->request->getPost('video_name'),
					'image_name' => $this->request->getPost('image_name'),
					'mmc_plate_number' => $this->request->getPost('mmc_plate_number'),
					'mmc_plate_region' => $this->request->getPost('mmc_plate_region'),
					'mmc_plate_image_coordinates' => $this->request->getPost('mmc_plate_image_coordinates'),
					'mmc_plate_score' => $this->request->getPost('mmc_plate_score'),
					'mmc_vehicle_type_body' => $this->request->getPost('mmc_vehicle_type_body'),
					'mmc_vehicle_make' => $this->request->getPost('mmc_vehicle_make'),
					'mmc_vehicle_model' => $this->request->getPost('mmc_vehicle_model'),
					'mmc_vehicle_color' => $this->request->getPost('mmc_vehicle_color'),
					'mmc_vehicle_direction' => $this->request->getPost('mmc_vehicle_direction'),
					'video_creation_date' => $this->request->getPost('video_creation_date')
				);
				//$data = $this->security->xss_clean($data);
				$result = $this->mmcModel->edit_mmc($data, $id);
				if ($result) {
					// Add User Activity
					//	$this->activity_model->add(1);
					$this->session->setFlashdata('msg', 'MMC has been added successfully!');
					return redirect()->to(base_url('admin/mmc'));
				}
			}
		} else {
			$data['mmc_details'] = $this->mmcModel->get_mmc_by_id($id);
			$data['view'] = 'admin/mmc/mmc_edit';
			return view('layout', $data);
		}
	}

	public function del($id = 0)
	{

		$this->mmcModel->delete($id);

		// Add User Activity
		//$this->activity_model->add(3);
		$this->session->setFlashdata('msg', 'MMC has been deleted successfully!');
		return redirect()->to(base_url('admin/mmc'));
	}

	public function import()
	{

		$data['view'] = 'admin/mmc/import';
		return view('layout', $data);
	}

	public function importcsv()
	{

		$handle = fopen($_FILES["file"]["tmp_name"], 'r');

		if ($handle) {
			$i = 0;
			$header = fgetcsv($handle);

			while (($v1 = fgetcsv($handle, 1000, ",")) !== FALSE) {

				// Skip header
				if ($i == 0) {
					$i++;
					continue;
				}

			//	index,uuid,lambda_uuid,Video Name,Image Name,Plate,Plate Region,Plate Coordinates,Score Plate,Vehicle Type,Make,Model,Color,Vehicle Orientation,Date,Time,Time Stamp


				// Initialize $importData_arr Array


				$v = array_combine($header, $v1);;

				if (!empty($v['uuid'])) {
					$mmc_details = $this->mmcModel->get_mmc_by_uid($v['uuid']);

					$data = array(
						'mmc_uuid' => trim($v['uuid']),
						'video_name' => trim($v['Video Name']),
						'image_name' => trim($v['Image Name']),
						'mmc_plate_number' => trim($v['Plate']),
						'mmc_plate_region' => trim($v['Plate Region']),
						'mmc_plate_image_coordinates' => json_encode(json_decode(str_replace("'",'"',trim($v['Plate Coordinates'])),true)),
						'mmc_plate_score' => trim($v['Score Plate']),
						'mmc_vehicle_type_body' => trim($v['Vehicle Type']),
						'mmc_vehicle_make' => trim($v['Make']),
						'mmc_vehicle_model' => trim($v['Model']),
						'mmc_vehicle_color' => trim($v['Color']),
						'mmc_vehicle_direction' => trim($v['Vehicle Orientation']),
						'video_creation_date' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-',trim($v['Time Stamp']))))
					);


					//	$data = $this->security->xss_clean($data);

					if (isset($mmc_details['mmc_id']) && is_numeric($mmc_details['mmc_id']) == 1){
						$this->mmcModel->edit_mmc($data, $mmc_details['mmc_id']);
					}
					else{
						$this->mmcModel->add_mmc($data);
					}
				}
			}

			$this->session->setFlashdata('msg', 'MMC has been imported successfully!');
			//return redirect()->to(base_url('admin/mmc'));
		} else {
			$data['view'] = 'admin/mmc/import';
			return view('layout', $data);
		}

		/*
		if ( $this->request->getPost('submit') ) {
			$handle = fopen($_FILES["mmc_xlsx"]["tmp_name"], 'r');
			$mmc_head = fgetcsv($handle, 1000, ",");

			while ( ($v = fgetcsv($handle, 1000, ",")) !== FALSE ) {
				if ( !empty($v[0]) && !empty($v[2]) ) {
					$mmc_details = $this->mmcModel->get_mmcs_by_pv(trim($v[2]), trim($v[0]));

					$data = array(
						'video_name' => trim($v[0]),
						'image_name' => trim($v[1]),
						'plate' => trim($v[2]),
						'plate_region' => trim($v[3]),
						'plate_coordinates' => trim($v[4]),
						'mmc_plate_score' => trim($v[5]),
						'mmc_vehicle_type_body' => trim($v[6]),
						'make' => trim($v[7]),
						'model' => trim($v[8]),
						'color' => trim($v[9]),
						'vehicle_orientation' => trim($v[10]),
						'date' => trim($v[11]),
						'time' =>  trim($v[12]),
						'time_stamp' => trim($v[13])
					);

					$data = $this->security->xss_clean($data);
					
					if ( is_numeric($mmc_details['id']) == 1 ) {
						$this->mmcModel->edit_mmc($data, $mmc_details['id']);
					} else {
						$this->mmcModel->add_mmc($data);
					}
				}
				   
			}
			$this->session->setFlashdata('msg', 'MMC has been imported successfully!');
			return redirect()->to(base_url('admin/mmc'));
			exit;
		}
		
		$data['view'] = 'admin/mmc/import';
		return view('layout', $data);
		*/
	}

	public function exportcsv()
	{

		if (!empty($_REQUEST['plate_id'])) {
			$plate_ids = $_REQUEST['plate_id'];
			$records = $this->db->where_in('id', $plate_ids)->get('ci_plates')->result_array();
			if (count($records) > 0) {
				$delimiter = ",";
				$filename = "Plates Report.csv";

				// Create a file pointer 
				$f = fopen('php://memory', 'w');
		
				
				// Set column headers 
				$fields = array('Id', 'Plate', 'Plate type', 'Name', 'Address', 'City', 'State', 'Zip code', 'VIN', 'Body', 'Year', 'Make', 'Color', 'DMV Date checked', 'DMV Status', 'Expiration Date', 'Sex', 'Birth Date', 'County', 'MID Number', 'screenshot');
				fputcsv($f, $fields, $delimiter);

				foreach ($records as $val) {
					$lineData = array($val['mmc_id'], $val['mmc_plate_number'], $val['mmc_vehicle_type_body'], $val['name'], $val['address'], $val['city'], $val['state'], $val['zip_code'], $val['vin'], $val['body'], $val['year'], $val['make'], $val['color'], $val['dmv_date_checked'], $val['dmv_status'], $val['exp_date'], $val['sex'], $val['birth_date'], $val['country'], $val['mid_number'], $val['screenshot']);
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

	public function exportallcsv()
	{

		$records = $this->mmcModel->get_all_simple_mmcs();

		if (count($records) > 0) {
			$delimiter = ",";
			$filename = "MMC Report.csv";

			// Create a file pointer 
			$f = fopen('php://memory', 'w');
		
			// Set column headers 
			$fields = array('Id','uuid ',  'Video Name', 'Image Name', 'Plate', 'Plate Region', 'Plate Coordinates', 'Score Plate', 'Vehicle Type', 'Make', 'Model', 'Color', 'Vehicle Orientation', 'Date');
			fputcsv($f, $fields, $delimiter);

			foreach ($records as $val) {
				$lineData = array($val['mmc_uuid'],$val['mmc_id'], $val['video_name'], $val['image_name'], $val['mmc_plate_number'], $val['mmc_plate_region'], $val['mmc_plate_image_coordinates'],$val['mmc_plate_score'], $val['mmc_vehicle_type_body'], $val['mmc_vehicle_make'], $val['mmc_vehicle_model'], $val['mmc_vehicle_color'], $val['mmc_vehicle_make'], $val['mmc_vehicle_direction'], $val['video_creation_date']);
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

	public function plate_search($plate)
	{

		$json_resp = array();
		$json_resp['plate_details'] = $this->mmcModel->get_plates_by_plate($plate);
		echo json_encode($json_resp);
		exit;
	}
}
