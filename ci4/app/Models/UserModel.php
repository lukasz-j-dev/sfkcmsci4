<?php
use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class UserModel extends Model
{
    protected $table = 'ci_users';
	protected $db;
	protected $datatable;
	protected $userModel;
	protected $session;
	protected $allowedFields = [
		'username',
		'firstname',
		'lastname',
		'email',
		'mobile_no',
		'password',
		'address',
		'role',
		'is_active',
		'is_verify',
		'is_admin',
		'token',
		'password_reset_code',
		'last_ip',
		'created_at',
		'updated_at'
	];


	public function __construct()
    {
        $this->db = db_connect();

		$this->datatable =  new Datatable($this);
		$this->groupModel = model('GroupModel');
		$this->session = session();

    }

	public function add_user($data){
		$this->insert($data);
		return  $this->getInsertID();
	}
	//---------------------------------------------------
	// get all users for server-side datatable processing (ajax based)
	public function get_all_users(){
		$wh =array();
		$SQL ='SELECT * FROM ci_users';
		/* $wh[] = " is_admin = 0"; */
		if(count($wh)>0)
		{
			$WHERE = implode(' and ',$wh);
			return $this->datatable->LoadJson($SQL,$WHERE);
		}
		else
		{
			return $this->datatable->LoadJson($SQL);
		}
	}

	//---------------------------------------------------
	// get all user records
	public function get_all_simple_users(){
		$this->db->where('is_admin', 0);
		$this->db->order_by('created_at', 'desc');
		$query = $this->db->get('ci_users');
		return $result = $query->result_array();
	}

	//---------------------------------------------------
	// Count total user for pagination
	public function count_all_users(){
		return $this->db->count_all('ci_users');
	}

	//---------------------------------------------------
	// Get all users for pagination
	public function get_all_users_for_pagination($limit, $offset){
		$wh =array();	
		$this->db->order_by('created_at', 'desc');
		$this->db->limit($limit, $offset);

		if(count($wh)>0){
			$WHERE = implode(' and ',$wh);
			$query = $this->db->get_where('ci_users', $WHERE);
		}
		else{
			$query = $this->db->get('ci_users');
		}
		return $query->result_array();
		//echo $this->db->last_query();
	}


	//---------------------------------------------------
	// get all users for server-side datatable with advanced search
	public function get_all_users_by_advance_search(){
		$wh =array();
		$SQL ='SELECT * FROM ci_users';
		if($this->session->userdata('user_search_type')!='')
		$wh[]="is_active = '".$this->session->userdata('user_search_type')."'";
		if($this->session->userdata('user_search_from')!='')
		$wh[]=" `created_at` >= '".date('Y-m-d', strtotime($this->session->userdata('user_search_from')))."'";
		if($this->session->userdata('user_search_to')!='')
		$wh[]=" `created_at` <= '".date('Y-m-d', strtotime($this->session->userdata('user_search_to')))."'";

		$wh[] = " is_admin = 0";
		if(count($wh)>0)
		{
			$WHERE = implode(' and ',$wh);
			return $this->datatable->LoadJson($SQL,$WHERE);
		}
		else
		{
			return $this->datatable->LoadJson($SQL);
		}
	}

	public function get_user_by_id($id){

		return $this->asArray()->find($id);
	}

	//---------------------------------------------------
	// Edit user Record
	public function edit_user($data, $id){
		$this->update($id, $data);
		return true;
	}

	//---------------------------------------------------
	// Get User Role/Group
	public function get_user_groups(){
		return $this->groupModel->asArray()->findAll();

	}
	public function get_user_detail(){
		//$id = $this->session->get('admin_id');
		$id = 51;
		return $this->asArray()->find($id);
	}
	//--------------------------------------------------------------------
	public function update_user($data){
		$id = $this->session->userdata('admin_id');
		$this->update($id, $data);
		return true;
	}
	//--------------------------------------------------------------------
	public function change_pwd($data, $id){
		$this->update($id, $data);
		return true;
	}
}