<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
	protected $dashboardModel;
	protected $session;
	public function __construct()
	{
		$this->dashboardModel = model('DashboardModel');
		$this->session = session();

	}
    public function index()
    {
	
		if ($this->session->get('admin_id')) {
		
		} else {
			return	redirect()->to(base_url('auth/login'));
		}

        $data['all_users'] = $this->dashboardModel->get_all_users();
		$data['active_users'] = $this->dashboardModel->get_active_users();
		$data['deactive_users'] = $this->dashboardModel->get_deactive_users();
		$data['title'] = 'Dashboard';
		$data['view'] = 'admin/dashboard/index';

		return view('layout', $data);
    }
}
