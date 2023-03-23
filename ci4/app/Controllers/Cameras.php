<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Cameras extends BaseController
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

			$data['view'] = 'admin/cameras/camera_list';
	
			return view('layout', $data);
	
		}
		public function datatable_json(){				   					   
	
			$records = $this->cameraModel->get_all_cameras();
	
			$data = array();
	
			$i = 0;
	
			foreach ($records['data']  as $row) 
	
			{  
	
				$data[]= array(
	
					$row['scene_id'],
	
					$row['municipality_id'],
	
					$row['scene_location'],
					
					$row['scene_zipcode'],
					
					$row['camera_model'],
					
					$row['camera_install_date'],
					
					'<a title="View" class="view btn btn-sm btn-info" href="'.base_url('admin/cameras/edit/'.$row['scene_id']).'"> <i class="material-icons">visibility</i></a>
	
					<a title="Edit" class="update btn btn-sm btn-primary" href="'.base_url('admin/cameras/edit/'.$row['scene_id']).'"> <i class="material-icons">edit</i></a>
	
					<a title="Delete" class="delete btn btn-sm btn-danger" data-href="'.base_url('admin/cameras/del/'.$row['scene_id']).'" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
	
					',
					$row['camera_install_date']
	
				);
	
			}
	
			$records['data']=$data;
	
			echo json_encode($records);						   
	
		}
		public function add(){
			$data['village'] = $this->cameraModel->get_all_villages();
	
			if ($this->request->getPost('submit')) {
				$validation = \Config\Services::validation();

				$validation->setRule('municipality_id', 'municipality_id', 'trim');
				$validation->setRule('scene_location', 'scene_location', 'trim|required');
				$validation->setRule('scene_zipcode', 'scene_zipcode', 'trim|required');
				$validation->setRule('camera_model', 'camera_model', 'trim');
				$validation->setRule('camera_install_date', 'camera_install_date', 'trim');
				$validation->setRule('gps', 'gps', 'trim|required');
				if ($validation->withRequest($this->request)->run() == FALSE) {
					$data['camera']['simple_villages'] = $this->villageModel->get_all_active_villages();

					$data['view'] = 'admin/cameras/camera_add';
	
					return view('layout', $data);
	
				}else{
					$data = array(
	
						'municipality_id' =>$this->request->getPost('municipality_id'),
	
						'scene_location' =>$this->request->getPost('scene_location'),
	
						'scene_zipcode' =>$this->request->getPost('scene_zipcode'),
	
						'camera_model' =>$this->request->getPost('camera_model'),
	
						'camera_install_date' =>$this->request->getPost('camera_install_date'),
	
						'gps' => $this->request->getPost('gps'),
						
						'status' => 1,
	
	
					);
				
					$added_by = $this->session->get('admin_id');
					$n_date = strtotime(str_replace('-', ' ',$this->request->getPost('camera_install_date')));
					$data['camera_install_date'] = $n_date;
					$data['added_by'] = $added_by;
					$data['date_modified'] = date('Y-m-d');
	
				//	$data = $this->security->xss_clean($data);
	
					$result = $this->cameraModel->add_camera($data);
					if($result){
	
						// Add User Activity
	
						//$this->activity_model->add(1);
	
	
	
						$this->session->setFlashdata('msg', 'Camera has been added successfully!');
	
						return redirect()->to(base_url('admin/cameras'));
	
					}
				}
	
			}else{
			

				$data['camera']['simple_villages'] = $this->villageModel->get_all_active_villages();
				$data['view'] = 'admin/cameras/camera_add';
	
				return view('layout', $data);
	
			}
	
			
	
		}
		public function edit($id = 0){
			if ($this->request->getPost('submit')) {
				$validation = \Config\Services::validation();

				$validation->setRule('municipality_id', 'municipality_id', 'trim');
				$validation->setRule('scene_location', 'scene_location', 'trim|required');
				$validation->setRule('scene_zipcode', 'scene_zipcode', 'trim|required');
				$validation->setRule('camera_model', 'camera_model', 'trim');
				$validation->setRule('camera_install_date', 'camera_install_date', 'trim');
				$validation->setRule('camera_coordinates', 'camera_coordinates', 'trim|required');
				if ($validation->withRequest($this->request)->run() == FALSE) {
					$data['camera'] = $this->cameraModel->get_camera_by_id($id);
					$data['camera']['simple_villages'] = $this->villageModel->get_all_active_villages();
	
					if(isset($data['camera']['added_by']))
					$user_details = $this->userModel->get_user_by_id($data['camera']['added_by']);
					if(isset($user_details['firstname']) && isset($user_details['lastname']) )
					$data['camera']['added_by_details'] = $user_details['firstname'] . ' ' . $user_details['lastname'];
					else	
					$data['camera']['added_by_details'] = '';
	
					$data['view'] = 'admin/cameras/camera_edit';
	
					return view('layout', $data);
	
				}else{
					$data = array(
	
						'municipality_id' =>$this->request->getPost('municipality_id'),
	
						'scene_location' =>$this->request->getPost('scene_location'),
	
						'scene_zipcode' =>$this->request->getPost('scene_zipcode'),
	
						'camera_model' =>$this->request->getPost('camera_model'),
	
						'camera_install_date' =>$this->request->getPost('camera_install_date'),
	
						'gps' => $this->request->getPost('gps'),
						
						'date_added' =>$this->request->getPost('date_added'),
						
						'status' =>$this->request->getPost('status'),
	
	
					);
					$data['date_modified'] = date('Y-m-d');
					//$data = $this->security->xss_clean($data);
					
					$result = $this->cameraModel->edit_camera($data, $id);
					if($result){
	
						//$this->activity_model->add(2);
	
						$this->session->setFlashdata('msg', 'Camera has been updated successfully!');
						return redirect()->to(base_url('admin/cameras'));
					}
				}
			}
			else{
				$data['camera'] = $this->cameraModel->get_camera_by_id($id);
				$data['camera']['simple_villages'] = $this->villageModel->get_all_active_villages();

				if(isset($data['camera']['added_by']))
				$user_details = $this->userModel->get_user_by_id($data['camera']['added_by']);
				if(isset($user_details['firstname']) && isset($user_details['lastname']) )
				$data['camera']['added_by_details'] = $user_details['firstname'] . ' ' . $user_details['lastname'];
				else	
				$data['camera']['added_by_details'] = '';

				$data['view'] = 'admin/cameras/camera_edit';
				return view('layout', $data);
			}
		}
		public function del($id = 0){
			$this->cameraModel->delete( $id);
	
			// Add User Activity
			//$this->activity_model->add(3);
	
			$this->session->setFlashdata('msg', 'Camera has been deleted successfully!');
			return redirect()->to(base_url('admin/cameras'));
		}
	}
	