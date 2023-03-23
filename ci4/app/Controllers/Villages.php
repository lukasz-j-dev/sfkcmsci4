<?php

namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
class Villages extends BaseController
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
	public function index(){

		$data['view'] = 'admin/villages/village_list';

		return view('layout', $data);

	}

	public function datatable_json(){				   					   

		$records = $this->villageModel->get_all_villages();
		$data = array();

		foreach ($records['data']  as $row) 
		{  	
			$status_arr = array('', 'Active', 'Inactive', 'Archived');
			$status = !empty($row['municipality_status']) ? $status_arr[$row['municipality_status']] : '';
			$data[] = array(
				$row['municipality_id'],
				$row['municipality_name'],
				$row['violation_fine_amount'],
				$row['municipality_address1'],
				$row['municipality_address2'],
				$row['municipality_city'],
				$row['municipality_zipcode'],
				$row['municipality_state'],
				$row['violation_fine_amount'],
				$status,
				$row['payable_to_name'],
				'<a title="View" class="view btn btn-sm btn-info" href="'.base_url('admin/villages/edit/'.$row['municipality_id']).'"> <i class="material-icons">visibility</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url('admin/villages/edit/'.$row['municipality_id']).'"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url('admin/villages/del/'.$row['municipality_id']).'" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				'
			);
		}

		$records['data']=$data;
		echo json_encode($records);						   
	}

	public function add(){

		if ($this->request->getPost('submit')) {
			$validation = \Config\Services::validation();

			$validation->setRule('municipality_name', 'municipality_name', 'trim|required');
			$validation->setRule('violation_fine_amount', 'violation_fine_amount', 'trim|required');
			$validation->setRule('payable_to_name', 'payable_to_name', 'trim|required');
			$validation->setRule('municipality_email_violation_pdf', 'municipality_email_violation_pdf', 'trim');
			$validation->setRule('municipality_address1', 'municipality_address1', 'trim');
			$validation->setRule('nmunicipality_address2otes', 'municipality_address2', 'trim');
			$validation->setRule('municipality_state', 'municipality_state', 'trim');
			$validation->setRule('municipality_city', 'municipality_city', 'trim');
			$validation->setRule('municipality_zipcode', 'municipality_zipcode', 'trim');
			$validation->setRule('stripe_pub_key', 'stripe_pub_key', 'trim');
			$validation->setRule('stripe_sec_key', 'stripe_sec_key', 'trim');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['view'] = 'admin/villages/village_add';
				return view('layout', $data);
			} else {
				$data = array(
					'municipality_name' =>$this->request->getPost('municipality_name'),
					'violation_fine_amount' =>$this->request->getPost('violation_fine_amount'),
					'payable_to_name' =>$this->request->getPost('payable_to_name'),
					'municipality_state' =>$this->request->getPost('municipality_state'),
					'municipality_address1' =>$this->request->getPost('municipality_address1'),
					'municipality_address2' =>$this->request->getPost('municipality_address2'),
					'municipality_city' =>$this->request->getPost('municipality_city'),
					'municipality_zipcode' =>$this->request->getPost('municipality_zipcode'),
					'created_date' =>$this->request->getPost('created_date'),
					'municipality_status' =>$this->request->getPost('municipality_status'),
					'stripe_pub_key' =>$this->request->getPost('stripe_pub_key'),
					'stripe_sec_key' =>$this->request->getPost('stripe_sec_key')
				);

				/* $config['upload_path'] = './public/images/villages';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('village_logo')) {
					$this->upload->display_errors();
				} else {
					$upload_data = $this->upload->data();
					$data['village_logo'] = $upload_data['file_name'];
				} */

				// $images = Slim::getImages('village_logo');
				// if ($images != false) {
				// 	foreach ($images as $image) {
				// 		if (isset($image['output']['data'])) {
				// 			$img_name = $image['output']['name'];
				// 			$img_data = $image['output']['data'];
				// 			$input = Slim::saveFile($img_data, $img_name, './public/images/villages');
				// 			$data['village_logo'] = $input['name'];
				// 		}
				// 	}
				// }

				$added_by = $this->session->get('admin_id');
				$data['added_by'] = $added_by;
				$data['date_modified'] =date('Y-m-d');

				//$data = $this->security->xss_clean($data);
				$result = $this->villageModel->add_village($data);
				if($result){
					// Add User Activity
					//$this->activity_model->add(1);
					$this->session->setFlashdata('msg', 'Village has been added successfully!');
					return redirect()->to(base_url('admin/villages'));
				}
			}
		} else {
			$data['view'] = 'admin/villages/village_add';
			return view('layout', $data);
		}
	}

	public function edit($id = 0) {

		if ($this->request->getPost('submit')) {
			$validation = \Config\Services::validation();

			$validation->setRule('municipality_name', 'municipality_name', 'trim|required');
			$validation->setRule('violation_fine_amount', 'violation_fine_amount', 'trim|required');
			$validation->setRule('payable_to_name', 'payable_to_name', 'trim|required');
			$validation->setRule('municipality_email_violation_pdf', 'municipality_email_violation_pdf', 'trim');
			$validation->setRule('municipality_address1', 'municipality_address1', 'trim');
			$validation->setRule('nmunicipality_address2otes', 'municipality_address2', 'trim');
			$validation->setRule('municipality_state', 'municipality_state', 'trim');
			$validation->setRule('municipality_city', 'municipality_city', 'trim');
			$validation->setRule('municipality_zipcode', 'municipality_zipcode', 'trim');
			$validation->setRule('stripe_pub_key', 'stripe_pub_key', 'trim');
			$validation->setRule('stripe_sec_key', 'stripe_sec_key', 'trim');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['view'] = 'admin/villages/village_edit';
				return view('layout', $data);
			} else {
				$data = array(
					'municipality_name' =>$this->request->getPost('municipality_name'),
					'violation_fine_amount' =>$this->request->getPost('violation_fine_amount'),
					'payable_to_name' =>$this->request->getPost('payable_to_name'),
					'municipality_state' =>$this->request->getPost('municipality_state'),
					'municipality_address1' =>$this->request->getPost('municipality_address1'),
					'municipality_address2' =>$this->request->getPost('municipality_address2'),
					'municipality_city' =>$this->request->getPost('municipality_city'),
					'municipality_zipcode' =>$this->request->getPost('municipality_zipcode'),
					'created_date' =>$this->request->getPost('created_date'),
					'municipality_status' =>$this->request->getPost('municipality_status'),
					'stripe_pub_key' =>$this->request->getPost('stripe_pub_key'),
					'stripe_sec_key' =>$this->request->getPost('stripe_sec_key')
				);
				
				/* $config['upload_path'] = './public/images/villages';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('village_logo')) {
					$this->upload->display_errors();
				} else {
					$upload_data = $this->upload->data();
					$data['village_logo'] = $upload_data['file_name'];
				} */

				// $images = Slim::getImages('village_logo');
				// if ($images != false) {
				// 	foreach ($images as $image) {
				// 		if (isset($image['output']['data'])) {
				// 			$img_name = $image['output']['name'];
				// 			$img_data = $image['output']['data'];
				// 			$input = Slim::saveFile($img_data, $img_name, './public/images/villages');
				// 			$data['village_logo'] = $input['name'];
				// 		}
				// 	}
				// }

				$data['date_modified'] = date('Y-m-d');

				//$data = $this->security->xss_clean($data);
				$result = $this->villageModel->edit_village($data, $id);
				if($result){
					//$this->activity_model->add(2);
					$this->session->setFlashdata('msg', 'Village has been updated successfully!');
					return redirect()->to(base_url('admin/villages'));
				}
			}
		}
		else{
			$data['village'] = $this->villageModel->get_village_by_id($id);
			$user_details = $this->userModel->get_user_by_id($data['village']['created_by_id']);
			$data['village']['added_by_details'] ='';
			if(!empty($user_details))
			$data['village']['added_by_details'] = $user_details['firstname'] . ' ' . $user_details['lastname'];
			$data['view'] = 'admin/villages/village_edit';
			return view('layout', $data);
		}
	}

	public function del($id = 0){
		$this->villageModel->delete( $id);
		$this->session->setFlashdata('msg', 'Village has been deleted successfully!');
		return redirect()->to(base_url('admin/villages'));
	}

	public function get_village_due($id = 0){
		$violation = $this->villageModel->get_village_by_id($id);
		echo json_encode(array('violation' => $violation));
		exit;
	}
}