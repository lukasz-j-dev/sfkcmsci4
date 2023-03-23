<?php
use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class UserModel extends Model
{
    protected $table = 'ci_users';
	protected $db;
	protected $datatable;
	protected $userModel;
	protected $groupModel;
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

    }
		//--------------------------------------------------------------------
		public function get_user_detail(){
			$id = $this->session->get('admin_id');
			return $this->asArray()->find($id);
		}
		//--------------------------------------------------------------------
		public function update_user($data){
			$id = $this->session->get('admin_id');
			$this->update($id, $data);
			return true;
		}
		//--------------------------------------------------------------------
		public function change_pwd($data, $id){
			$this->update($id, $data);
			return true;
		}

	}

