<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Profile extends BaseController
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
		if($this->request->getPost('submit')){
			$validation = \Config\Services::validation();
			$data = array(
				'username' =>$this->request->getPost('username'),
				'firstname' =>$this->request->getPost('firstname'),
				'lastname' =>$this->request->getPost('lastname'),
				'email' =>$this->request->getPost('email'),
				'mobile_no' =>$this->request->getPost('mobile_no'),
				'updated_at' => date('Y-m-d : h:m:s'),
			);
		//	$data = $this->security->xss_clean($data);
			$result = $this->userModel->update_user($data);
			if($result){

				// Add User Activity
				//$this->activity_model->add(6);

				$this->session->setFlashdata('msg', 'Profile has been Updated Successfully!');
				return redirect()->to(base_url('admin/profile'), 'refresh');
			}
		}
		else{
			$data['user'] = $this->userModel->get_user_detail();
			$data['title'] = 'User Profile';
			$data['view'] = 'admin/profile';
			return view('layout', $data);
		}
	}

	//-------------------------------------------------------------------------
	public function change_pwd(){
		$id = $this->session->get('admin_id');
		if($this->request->getPost('submit')){
			$validation = \Config\Services::validation();
			$validation->setRule('password', 'Password', 'trim|required');
			$validation->setRule('confirm_pwd', 'Confirm Password', 'trim|required|matches[password]');
			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['user'] = $this->userModel->get_user_detail();
				$data['view'] = 'admin/profile';
				return view('layout', $data);
			}
			else{
				$data = array(
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
				);
			//	$data = $this->security->xss_clean($data);
				$result = $this->userModel->change_pwd($data, $id);
				if($result){

					// Add User Activity
					//$this->activity_model->add(7);

					$this->session->setFlashdata('msg', 'Password has been changed successfully!');
					return redirect()->to(base_url('admin/profile'));
				}
			}
		}
		else{
			$data['user'] = $this->userModel->get_user_detail();
			$data['title'] = 'Change Password';
			$data['view'] = 'admin/profile';
			return view('layout', $data);
		}
	}
}