<?php
use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class GroupModel extends Model
{
    protected $table = 'ci_user_groups';
	protected $db;
	protected $datatable;
	protected $allowedFields = [
		'group_name'
	];
    public function __construct()
    {
        $this->db = db_connect();

		$this->datatable =  new Datatable($this);

    }
  
	public function add_group($data){
		$this->insert($data);
		return  $this->getInsertID();
	}

	public function get_all_groups(){
		return $this->asArray()->findAll();
	}

	public function edit_group($data, $id){
		$this->update($id, $data);
		return true;

	}

	public function get_group_by_id($id){
		return $this->asArray()->find($id);
	
	}
}