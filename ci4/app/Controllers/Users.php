<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Users extends BaseController
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
		$data['view'] = 'admin/users/user_list';
		return view('layout', $data);
	}
	//-----------------------------------------------------------------------
	public function datatable_json(){				   					   
		$records = $this->userModel->get_all_users();
		$data = array();
		$i = 0;
		foreach ($records['data']  as $row) 
		{  
			$status = ($row['is_active'] == 0)? 'inactive': 'active'.'<span>';
			$disabled = ($row['is_admin'] == 1)? 'disabled': ''.'<span>';
			$data[]= array(
				++$i,
				$row['username'],
				$row['email'],
				date('F j, Y',strtotime($row['created_at'])),
				'<span class="btn bg-teal  waves-effect" title="status">'.$this->getGroupyName($row['role']).'<span>',	// get Group name by ID (getGroupyName() is a helper function)
				'<span class="btn bg-blue  waves-effect" title="status">'.$status.'<span>',			
				
				'<a title="View" class="view btn btn-sm btn-info" href="'.base_url('admin/users/edit/'.$row['id']).'"> <i class="material-icons">visibility</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url('admin/users/edit/'.$row['id']).'"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger '.$disabled.'" data-href="'.base_url('admin/users/del/'.$row['id']).'" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				',
				
			);
		}
		$records['data']=$data;
		echo json_encode($records);						   
	}
	 public function getGroupyName($role)
	{
		return $role;
	}
	//-----------------------------------------------------------------------
	public function add(){
		$data['user_groups'] = $this->userModel->get_user_groups();
		if($this->request->getPost('submit')){
			$validation = \Config\Services::validation();
			$validation->setRule('username', 'Username', 'trim|min_length[3]|required');
			$validation->setRule('firstname', 'Firstname', 'trim|required');
			$validation->setRule('lastname', 'Lastname', 'trim|required');
			$validation->setRule('email', 'Email', 'trim|valid_email|is_unique[ci_users.email]|required');
			$validation->setRule('mobile_no', 'Number', 'trim|required');
			$validation->setRule('password', 'Password', 'trim|required');
			$validation->setRule('address', 'Address', 'trim');
			$validation->setRule('group', 'Group', 'trim|required');
			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['view'] = 'admin/users/user_add';
				return view('layout', $data);
			}
			else{
				$data = array(
					'username' =>$this->request->getPost('username'),
					'firstname' =>$this->request->getPost('firstname'),
					'lastname' =>$this->request->getPost('lastname'),
					'email' =>$this->request->getPost('email'),
					'mobile_no' =>$this->request->getPost('mobile_no'),
					'address' =>$this->request->getPost('address'),
					'password' =>  password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
					'role' =>$this->request->getPost('group'),
					'is_verify' => 1,
					'created_at' => date('Y-m-d : h:m:s'),
					'updated_at' => date('Y-m-d : h:m:s'),
				);
			//	$data = $this->security->xss_clean($data);
				$result = $this->userModel->add_user($data);
				if($result){
					// Add User Activity
					//$this->activity_model->add(1);

					$this->session->setFlashdata('msg', 'User has been added successfully!');
					return redirect()->to(base_url('admin/users'));
				}
			}
		}
		else{
			$data['view'] = 'admin/users/user_add';
			return view('layout', $data);
		}
		
	}
	//-----------------------------------------------------------------------
	public function edit($id = 0){
		if($this->request->getPost('submit')){
			$validation = \Config\Services::validation();
			$validation->setRule('username', 'Username', 'trim|required');
			$validation->setRule('firstname', 'Username', 'trim|required');
			$validation->setRule('lastname', 'Lastname', 'trim|required');
			$validation->setRule('email', 'Email', 'trim|valid_email|required');
			$validation->setRule('mobile_no', 'Number', 'trim|required');
			$validation->setRule('status', 'Status', 'trim|required');
			$validation->setRule('address', 'Address', 'trim');
			$validation->setRule('group', 'Group', 'trim|required');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['user'] = $this->userModel->get_user_by_id($id);
				$data['user_groups'] = $this->userModel->get_user_groups();
				$data['view'] = 'admin/users/user_edit';
				return view('layout', $data);
			}
			else{
				$data = array(
					'username' =>$this->request->getPost('username'),
					'firstname' =>$this->request->getPost('firstname'),
					'lastname' =>$this->request->getPost('lastname'),
					'email' =>$this->request->getPost('email'),
					'mobile_no' =>$this->request->getPost('mobile_no'),
					'password' =>  password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
					'role' =>$this->request->getPost('group'),
					'is_verify' => 1,
					'address' =>$this->request->getPost('address'),
					'is_active' =>$this->request->getPost('status'),
					'updated_at' => date('Y-m-d : h:m:s'),
				);
				//$data = $this->security->xss_clean($data);
				$result = $this->userModel->edit_user($data, $id);
				if($result){

					// Add User Activity
					//$this->activity_model->add(2);

					$this->session->setFlashdata('msg', 'User has been updated successfully!');
					return redirect()->to(base_url('admin/users'));
				}
			}
		}
		else{
			$data['user'] = $this->userModel->get_user_by_id($id);
			$data['user_groups'] = $this->userModel->get_user_groups();
			$data['view'] = 'admin/users/user_edit';
			return view('layout', $data);
		}
	}
	//-----------------------------------------------------------------------
	public function del($id = 0){
		$this->userModel->delete( $id);

		// Add User Activity
		//$this->activity_model->add(3);

		$this->session->setFlashdata('msg', 'Use has been deleted successfully!');
		return redirect()->to(base_url('admin/users'));
	}
}
