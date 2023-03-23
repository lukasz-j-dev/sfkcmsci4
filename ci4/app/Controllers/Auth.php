<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Auth extends BaseController
{
	protected $violationModel;
	protected $plateModel;
	protected $mmcModel;
	protected $villageModel;
	protected $session;
	protected $cameraModel;
	protected $userModel;
	protected $authModel;
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
		$this->villageModel = model('villageModel');
		$this->cameraModel = model('CameraModel');
		$this->userModel = model('UserModel');
		$this->authModel = model('AuthModel');

		$this->session = session();
		$this->request = $request;
	}

	//-------------------------------------------------------------------------
	public function index()
	{
		if ($this->session->get('is_admin_login')) {
			redirect()->to(base_url('admin/dashboard'));
		}
		if ($this->session->get('is_user_login')) {
			redirect()->to(base_url('user/profile'));
		} else {
			redirect()->to(base_url('auth/login'));
		}
	}
	//-------------------------------------------------------------------------	
	public function login()
	{
		
		if ($this->request->getPost('submit')) {

			// for google recaptcha
			if ($this->authModel->check_recaptcha_status() == true) {
				if (!$this->recaptcha_verify_request()) {
					$this->session->setFlashdata('form_data', $this->request->getPost(''));
					$this->session->setFlashdata('warning', 'reCaptcha Error');
					redirect(base_url('auth/login'));
					exit();
				}
			}
			$validation = \Config\Services::validation();
			$validation->setRule('email', 'Email', 'trim|required');
			$validation->setRule('password', 'Password', 'trim|required');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				return view('auth/login');
			} else {
				$data = array(
					'email' => $this->request->getPost('email'),
					'password' => $this->request->getPost('password')
				);
				//$data = $this->security->xss_clean($data);
				$result = $this->authModel->login($data);
				if ($result) {
					if ($result['is_verify'] == 0) {
						$this->session->setFlashdata('warning', 'Please verify your email address!');
						return redirect()->to(base_url('auth/login'));
						exit;
					}
					if ($result['is_admin'] == 1) {
						$admin_data = array(
							'admin_id' => $result['id'],
							'name' => $result['firstname'],
							'is_admin_login' => TRUE
						);
						$this->session->set($admin_data);

						// Add User Activity
						//$this->activity_model->add(4);

						return redirect()->to(base_url('admin/dashboard'));
					}
					if ($result['is_admin'] == 0) {
						$user_data = array(
							'user_id' => $result['id'],
							'name' => $result['firstname'],
							'is_user_login' => TRUE
						);
						$this->session->set($user_data);

						// Add User Activity
						//$this->activity_model->add(4);

						return redirect()->to(base_url('user/profile'));
					}
				} else {
					$data['msg'] = 'Invalid Email or Password!';
					return view('auth/login', $data);
				}
			}
		} else {
			$data['title'] = 'Login';
			return view('auth/login');
		}
	}

	//-------------------------------------------------------------------------
	public function register()
	{
		if ($this->request->getPost('submit')) {

			// for google recaptcha
			if ($this->authModel->check_recaptcha_status() == true) {
				if (!$this->recaptcha_verify_request()) {
					$this->session->setFlashdata('form_data', $this->request->getPost());
					$this->session->setFlashdata('warning', 'reCaptcha Error');
					return redirect()->to(base_url('auth/register'));
					exit();
				}
			}
			$validation = \Config\Services::validation();
			$validation->setRule('username', 'Username', 'trim|required|is_unique[ci_users.username]');
			$validation->setRule('firstname', 'Firstname', 'trim|required');
			$validation->setRule('lastname', 'Lastname', 'trim|required');
			$validation->setRule('email', 'Email', 'trim|valid_email|is_unique[ci_users.email]|required');
			$validation->setRule('password', 'Password', 'trim|required|min_length[8]');
			$validation->setRule('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['title'] = 'Create an Account';
				return view('auth/register', $data);
			} else {
				$data = array(
					'firstname' => $this->request->getPost('firstname'),
					'lastname' => $this->request->getPost('lastname'),
					'email' => $this->request->getPost('email'),
					'password' =>  password_hash($this->input->post('password'), PASSWORD_BCRYPT),
					'is_active' => 1,
					'is_verify' => 0,
					'token' => md5(rand(0, 1000)),
					'last_ip' => '',
					'created_at' => date('Y-m-d : h:m:s'),
					'updated_at' => date('Y-m-d : h:m:s'),
				);
				//$data = $this->security->xss_clean($data);
				$result = $this->authModel->register($data);
				if ($result) {
					//sending welcome email to user
					$name = $data['firstname'] . ' ' . $data['lastname'];
					$email_verification_link = base_url('auth/verify/') . '/' . $data['token'];
					$body = $this->mailer->Tpl_Registration($name, $email_verification_link);
					$this->load->helper('email_helper');
					$to = $data['email'];
					$subject = 'Activate your account';
					$message =  $body;
					$email = sendEmail($to, $subject, $message, $file = '', $cc = '');
					$email = true;
					if ($email) {
						$this->session->setFlashdata('success', 'Your Account has been made, please verify it by clicking the activation link that has been send to your email.');
						return redirect()->to(base_url('auth/login'));
					} else {
						echo 'Email Error';
					}
				}
			}
		} else {
			$data['title'] = 'Create an Account';
			return view('auth/register', $data);
		}
	}

	//----------------------------------------------------------	
	public function verify()
	{
		$verification_id = $this->uri->segment(3);
		$result = $this->authModel->email_verification($verification_id);
		if ($result) {
			$this->session->setFlashdata('success', 'Your email has been verified, you can now login.');
			return redirect()->to(base_url('auth/login'));
		} else {
			$this->session->setFlashdata('success', 'The url is either invalid or you already have activated your account.');
			return redirect()->to(base_url('auth/login'));
		}
	}

	//--------------------------------------------------		
	public function forgot_password()
	{
		if ($this->request->getPost('submit')) {

			// for google recaptcha
			if ($this->authModel->check_recaptcha_status() == true) {
				if (!$this->recaptcha_verify_request()) {
					$this->session->setFlashdata('form_data', $this->request->getPost());
					$this->session->setFlashdata('warning', 'reCaptcha Error');
					return redirect()->to(base_url('auth/forgot_password'));
					exit();
				}
			}
			$validation = \Config\Services::validation();

			//checking server side validation
			$validation->setRule('email', 'Email', 'valid_email|trim|required');
			if ($validation->withRequest($this->request)->run() == FALSE) {
				return view('auth/forget_password');
				return;
			}
			$email = $this->request->getPost('email');
			$response = $this->authModel->check_user_mail($email);
			if ($response) {
				$rand_no = rand(0, 1000);
				$pwd_reset_code = md5($rand_no . $response['id']);
				$this->authModel->update_reset_code($pwd_reset_code, $response['id']);
				// --- sending email
				$name = $response['firstname'] . ' ' . $response['lastname'];
				$email = $response['email'];
				$reset_link = base_url('auth/reset-password/' . $pwd_reset_code);
				$body = $this->mailer->Tpl_PwdResetLink($name, $reset_link);

				$this->load->helper('email_helper');
				$to = $email;
				$subject = 'Reset your password';
				$message =  $body;
				$email = sendEmail($to, $subject, $message, $file = '', $cc = '');
				if ($email) {
					$this->session->setFlashdata('success', 'We have sent instructions for resetting your password to your email');

					return redirect()->to(base_url('auth/forgot-password'));
				} else {
					$this->session->setFlashdata('error', 'There is the problem on your email');
					return redirect()->to(base_url('auth/forgot-password'));
				}
			} else {
				$this->session->setFlashdata('error', 'The Email that you provided are invalid');
				return redirect()->to(base_url('auth/forgot-password'));
			}
		} else {
			$data['title'] = 'Forget Password';
			return view('auth/forget_password', $data);
		}
	}

	//--------------------------------------------------		
	public function reset_password($id = 0)
	{
		// check the activation code in database
		if ($this->request->getPost('submit')) {
			$validation->setRule('password', 'Password', 'trim|required|min_length[8]');
			$validation->setRule('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				$result = false;
				$data['reset_code'] = $id;
				$data['title'] = 'Reseat Password';
				return view('auth/reset_password', $data);
			} else {
				$new_password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
				$this->authModel->reset_password($id, $new_password);
				$this->session->setFlashdata('success', 'New password has been Updated successfully.Please login below');
				return redirect()->to(base_url('auth/login'));
			}
		} else {
			$result = $this->authModel->check_password_reset_code($id);
			if ($result) {
				$data['reset_code'] = $id;
				$data['title'] = 'Reseat Password';
				return view('auth/reset_password', $data);
			} else {
				$this->session->setFlashdata('error', 'Password Reset Code is either invalid or expired.');
				return redirect()->to(base_url('auth/forgot-password'));
			}
		}
	}

	//-------------------------------------------------------------------------
	public function profile()
	{
		if ($this->request->getPost('submit')) {
			$data = array(
				'username' => $this->request->getPost('username'),
				'firstname' => $this->request->getPost('firstname'),
				'lastname' => $this->request->getPost('lastname'),
				'email' => $this->request->getPost('email'),
				'mobile_no' => $this->request->getPost('mobile_no'),
				'updated_at' => date('Y-m-d : h:m:s'),
			);
			$data = $this->security->xss_clean($data);
			$result = $this->authModel->update_admin($data);
			if ($result) {

				// Add User Activity
				//$this->activity_model->add(6);

				$this->session->setFlashdata('msg', 'Profile has been Updated Successfully!');
				return redirect()->to(base_url('auth/profile'), 'refresh');
			}
		} else {
			$data['admin'] = $this->authModel->get_admin_detail();
			$data['title'] = 'User Profile';
			$data['view'] = 'auth/profile';
			return view('layout', $data);
		}
	}
	//-------------------------------------------------------------------------
	public function change_pwd()
	{
		$id = $this->session->userdata('admin_id');
		if ($this->request->getPost('submit')) {
			$validation->setRule('password', 'Password', 'trim|required');
			$validation->setRule('confirm_pwd', 'Confirm Password', 'trim|required|matches[password]');
			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['admin'] = $this->authModel->get_admin_detail();
				$data['view'] = 'auth/profile';
				return view('layout', $data);
			} else {
				$data = array(
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
				);
				$data = $this->security->xss_clean($data);
				$result = $this->authModel->change_pwd($data, $id);
				if ($result) {

					// Add User Activity
					//$this->activity_model->add(7);

					$this->session->setFlashdata('msg', 'Password has been changed successfully!');
					return redirect()->to(base_url('auth/profile'));
				}
			}
		} else {
			$data['title'] = 'Change Password';
			$data['view'] = 'auth/change_pwd';
			return view('layout', $data);
		}
	}
	//-------------------------------------------------------------------------
	public function logout()
	{
		// Add User Activity
		//$this->activity_model->add(5);

		$this->session->destroy();
		return redirect()->to(base_url('auth/login'));
	}

	//verify recaptcha
	public function recaptcha_verify_request()
	{
		$recaptcha = $this->request->getPost('g-recaptcha-response');
		if (!empty($recaptcha)) {
			$response = $this->recaptcha->verifyResponse($recaptcha);
			if (isset($response['success']) && $response['success'] === true) {
				return true;
			}
		}
		return false;
	}
}  // end class
