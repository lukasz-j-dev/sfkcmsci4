<?php

namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
class Group extends BaseController
{
	protected $violationModel;
	protected $plateModel;
	protected $mmcModel;
	protected $villageModel;
	protected $session;
	protected $cameraModel;
	protected $userModel;
	protected $groupModel;
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
		$this->groupModel = model('GroupModel');
		
		$this->session = session();
		$this->request = $request;
    }
	public function index(){
		$data['all_groups'] = $this->groupModel->get_all_groups();
		$data['title'] = 'User Group';
		$data['view'] = 'admin/group/group_list';
		return view('layout', $data);
	}
	//------------------------------------------------------------------------
	public function add(){
		if ($this->request->getPost('submit')) {
			$validation = \Config\Services::validation();
			$validation->setRule('group_name', 'Group', 'trim|min_length[3]|is_unique[ci_user_groups.group_name]|required');
			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['title'] = 'Add Group';
				$data['view'] = 'admin/group/group_add';
				return view('layout', $data);
			}
			else{
				$data = array(
					'group_name' =>$this->request->getPost('group_name'),
				);
			//	$data = $this->security->xss_clean($data);
				$result = $this->groupModel->add_group($data);
				if($result){

					// Add User Activity
					//$this->activity_model->add(8);

					$this->session->setFlashdata('msg', 'Group is Added Successfully!');
					return redirect()->to(base_url('admin/group'));
				}
			}
		}
		else{
			$data['title'] = 'Add Group';
			$data['view'] = 'admin/group/group_add';
			return view('layout', $data);
		}
	}
	public function edit($id=0){
		if ($this->request->getPost('submit')) {
			$validation = \Config\Services::validation();
			$data = array(
				'group_name' =>$this->request->getPost('group_name'),
			);
		//	$data = $this->security->xss_clean($data);
			$result = $this->groupModel->edit_group($data, $id);
			if($result){
				// Add User Activity
				//$this->activity_model->add(9);

				$this->session->setFlashdata('msg', 'Group is Updated Successfully!');
				return redirect()->to(base_url('admin/group'));
			}
		}
		else{
			$data['group'] = $this->groupModel->get_group_by_id($id);
			$data['title'] = 'Edit Group';
			$data['view'] = 'admin/group/group_edit';
			return view('layout', $data);
		}
	}
	public function del($id){
		$this->groupModel->delete( $id);

		// Add User Activity
		//$this->activity_model->add(9);

		$this->session->setFlashdata('msg', 'Record is Deleted Successfully!');
		return redirect()->to(base_url('admin/group'));
	}
}
