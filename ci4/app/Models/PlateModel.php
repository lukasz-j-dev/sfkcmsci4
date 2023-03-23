<?php
use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class PlateModel extends Model
{
    protected $table = 'cms_plate';
	protected $db;
	protected $datatable;
	protected $primaryKey = 'plate_id';

	protected $allowedFields = [
		'plate_id',
		'plate_number',
		'dmv_plate_type',
		'dmv_vehicle_body',
		'dmv_vehicle_make',
		'dmv_vehicle_color',
		'dmv_vehicle_year',
		'dmv_vehicle_vin',
		'dmv_contact_name',
		'dmv_contact_address',
		'dmv_contact_city',
		'dmv_contact_state',
		'dmv_contact_zipcode',
		'dmv_contact_county',
		'dmv_contact_sex',
		'dmv_contact_birth_date',
		'dmv_status',
		'dmv_expiration_date',
		'dmv_mid_number',
		'dmv_last_checked_date',
		'dmv_hash',
		'created_by_id',
		'last_modified_by_user_id',
		'stats_total_violations',
		'stats_violations_30d',
		'stats_violations_90d',
		'stats_first_notice_date',
		'created_date',
		'last_modified_date'
	];
    public function __construct()
    {
        $this->db = db_connect();

		$this->datatable =  new Datatable($this);
	

    }
    public function add_plate($data)
	{
		$this->insert($data);
		return  $this->getInsertID();
	}

	public function get_all_plates()
	{
		$wh = array();
		$SQL = '
			select a.*, b.mmc_vehicle_model
			from cms_plate as a
			inner join cms_mmc_vehicle_ocr as b on a.plate_number = b.mmc_plate_number
		';

		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			return $this->datatable->LoadJson($SQL, $WHERE);
		} else {
			return $this->datatable->LoadJson($SQL);
		}
	}

	public function get_all_simple_plates()
	{
		return $this->asArray()->findAll();
	}

	public function count_all_plates()
	{
	 	return $this->countAllResults();
	}

	public function get_all_plates_for_pagination($limit, $offset)
	{
		$wh = array();
		$this->db->order_by('date_added', 'desc');
		$this->db->limit($limit, $offset);

		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			$query = $this->db->get_where('cms_plate', $WHERE);
		} else {
			$query = $this->db->get('cms_plate');
		}

		return $query->result_array();
	}

	public function get_plate_by_id($id)
	{
		return $this->asArray()->find($id);
	}

	public function plate_exist($plate)
	{
		$ret = $this->where('plate_number', $plate)->asArray()->first();
		if(!empty($ret)){
			return true;
		}else{
			return false;
		}
	}

	public function get_plates_by_plate($plate)
	{

		return $this->where('plate_number', $plate)->asArray()->first();
	}

	public function edit_plate($data, $id)
	{
		$this->update($id, $data);
		return true;
	}

	// Shang
	public function get_platforms($platform)
	{
		$result = $this->db->table('ci_platforms')->where( array('platform' => $platform))->get();
	
		return $result->getRowArray();
	}

	public function get_similar_plates($params)
	{
		$query = "select * from cms_plate where 1 and " . $params;
		return $this->db->query($query)->getResultArray();
	}

	public function get_plates($plate = '')
	{
		if ($plate){

		
			$query =$this->where('plate_number', $plate)->get();
		return $query->getResultArray();
	}
	return [];
	}

	public function update_dmv($query)
	{
		$this->db->query($query);
	}

	public function update_by_plate($data, $plate)
	{
	
		$this->where('plate_number', $plate)->set($data)->update();


		return true;
	}
}