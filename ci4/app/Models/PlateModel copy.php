<?php
use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class PlateModel extends Model
{
    protected $table = 'ci_plates';
	protected $db;
	protected $datatable;
	protected $allowedFields = [
		'plate',
		'plate_type',
		'name',
		'address',
		'city',
		'state',
		'zip_code',
		'vin',
		'body',
		'year',
		'make',
		'color',
		'dmv_date_checked',
		'dmv_status',
		'exp_date',
		'sex',
		'birth_date',
		'country',
		'mid_number',
		'screenshot',
		'date_added',
		'dmv_last_checked_date',
		'violations_30days',
		'violations_60days',
		'first_notice_date',
		'total_violations'
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
			select a.*, b.model
			from ci_plates as a
			left join cms_mmc_vehicle_ocr as b on a.plate = b.plate
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
			$query = $this->db->get_where('ci_plates', $WHERE);
		} else {
			$query = $this->db->get('ci_plates');
		}

		return $query->result_array();
	}

	public function get_plate_by_id($id)
	{
		return $this->asArray()->find($id);
	}

	public function plate_exist($plate)
	{
		$ret = $this->where('plate', $plate)->asArray()->first();
		if(!empty($ret)){
			return true;
		}else{
			return false;
		}
	}

	public function get_plates_by_plate($plate)
	{

		return $this->where('plate', $plate)->asArray()->first();
	}

	public function edit_plate($data, $id)
	{
		$this->update($id, $data);
		return true;
	}

	// Shang
	public function get_platforms($platform)
	{
		$result = $this->db->get_where('ci_platforms', array('platform' => $platform));
		return $result->row_array();
	}

	public function get_similar_plates($params)
	{
		$query = "select * from ci_plates where 1 and " . $params;
		return $this->db->query($query)->result_array();
	}

	public function get_plates($plate = '')
	{
		if ($plate)
			$this->db->where('plate', $plate);
		$this->db->order_by('id', 'asc');
		$query = $this->db->get('ci_plates');
		return $query->result_array();
	}

	public function update_dmv($query)
	{
		$this->db->query($query);
	}

	public function update_by_plate($data, $plate)
	{
		$this->db->where('plate', $plate);
		$this->db->update('ci_plates', $data);
		return true;
	}
}