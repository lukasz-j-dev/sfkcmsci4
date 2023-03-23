<?php
use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class VillageModel extends Model
{

	protected $table = 'cms_municipality';
	protected $db;
	protected $datatable;
	protected $primaryKey = 'municipality_id';

	protected $allowedFields = [
		'municipality_id',
		'municipality_name',
		'municipality_logo',
		'municipality_email_payments',
		'municipality_email_disputes',
		'municipality_email_general',
		'municipality_email_violation_pdf',
		'violation_fine_amount',
		'violation_late_fee_amount',
		'payable_to_name',
		'municipality_address1',
		'municipality_address2',
		'municipality_city',
		'municipality_state',
		'municipality_zipcode',
		'mayor_name',
		'municipality_status',
		'stripe_pub_key',
		'stripe_sec_key',
		'created_by_id',
		'last_modified_by_user_id',
		'created_date',
		'last_modified_date'
	];
    public function __construct()
    {
        $this->db = db_connect();

		$this->datatable =  new Datatable($this);
	

    }

    public function add_village($data){

		$this->insert($data);
		return  $this->getInsertID();
	}

	public function get_all_villages(){

		$wh =array();
		$SQL ='SELECT * FROM cms_municipality';

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

	public function get_all_simple_villages(){

		return $this->asArray()->findAll();
	}

	public function get_all_active_villages(){
        $query = $this->db->table('cms_municipality')->select('*')->where('municipality_status',1)->get();


		return $query->getResultArray();
	}

	public function count_all_villages(){

		return $this->countAllResults();
	}

	public function get_all_villages_for_pagination($limit, $offset){

		$wh =array();	
		$this->db->order_by('date_added', 'desc');
		$this->db->limit($limit, $offset);

		if(count($wh)>0){
			$WHERE = implode(' and ',$wh);
			$query = $this->db->get_where('cms_municipality', $WHERE);
		}
		else{
			$query = $this->db->get('cms_municipality');
		}

		return $query->result_array();
	}

	public function get_village_by_id($id){

		return $this->asArray()->find($id);

	}
	
	public function get_village_by_name($village_court){

		return $this->where('cms_municipality', $village_court)->asArray()->first();
	}

	public function edit_village($data, $id){

		$this->update($id, $data);
		return true;
	}

	public function get_all_villages_without_stripe_key() {
		
		$query = "select municipality_id, municipality_name from cms_municipality where municipality_status = 1 order by municipality_id";
		return $this->db->query($query)->getResultArray();
	}
}