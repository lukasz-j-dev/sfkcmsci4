<?php

use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class MmcModel extends Model
{
	protected $table = 'cms_mmc_vehicle_ocr';
	protected $db;
	protected $datatable;
	protected $primaryKey = 'mmc_id';

	protected $allowedFields = [
		'mmc_id',
		'mmc_uuid',
		'image_name',
		'video_name',
		'video_creation_date',
		'mmc_plate_number',
		'mmc_plate_region',
		'mmc_plate_score',
		'mmc_plate_image_coordinates',
		'mmc_vehicle_type_body',
		'mmc_vehicle_make',
		'mmc_vehicle_color',
		'mmc_vehicle_model',
		'mmc_vehicle_direction',
		'created_date',
		'last_modified_date'
	];
	public function __construct()
	{
		$this->db = db_connect();
		$this->datatable =  new Datatable($this);
	}
	public function add_mmc($data)
	{
		$this->insert($data);
		return  $this->getInsertID();
	}

	public function get_all_mmcs()
	{

		$wh = array();

		$SQL = 'SELECT * FROM cms_mmc_vehicle_ocr';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			return $this->datatable->LoadJson($SQL, $WHERE);
		} else {
			return $this->datatable->LoadJson($SQL);
		}
	}

	public function get_all_simple_mmcs()
	{
		return $this->asArray()->findAll();
	}

	// public function count_all_mmcs(){
	// 	return $this->countAllResults();
	// }

	// public function get_all_mmcs_for_pagination($limit, $offset){

	// 	$wh = array();	
	// 	$this->db->order_by('id', 'desc');
	// 	$this->db->limit($limit, $offset);

	// 	if(count($wh)>0){
	// 		$WHERE = implode(' and ',$wh);
	// 		$query = $this->db->get_where('cms_mmc_vehicle_ocr', $WHERE);
	// 	}
	// 	else{
	// 		$query = $this->db->get('cms_mmc_vehicle_ocr');
	// 	}

	// 	return $query->result_array();
	// }

	public function get_mmc_by_id($id)
	{

		$query = $this->asArray()->where('mmc_id',$id)->get();
		return $query->getRowArray();
	}
	public function get_mmc_by_uid($id)
	{

		$query = $this->asArray()->where('mmc_uuid',$id)->get();
		return $query->getRowArray();
	}

	public function get_mmcs_by_mmc($plate)
	{

		$query = $this->db->get_where('cms_mmc_vehicle_ocr', array('plate' => $plate));
		return $query->row_array();
	}

	public function get_mmcs_by_image_file_name($filename)
	{

		$query = $this->db->table('cms_mmc_vehicle_ocr')->select('*')->where('image_name', $filename)->get();
		return $query->getResultArray();
	}

	public function get_mmcs_by_keys($type, $make, $model)
	{
		$query = 'select plate from cms_mmc_vehicle_ocr where vehicle_type="' . $type . '" and make="' . $make . '" and model="' . $model . '" group by id order by plate';
		return $this->db->query($query)->result_array();
	}

	public function get_mmcs_by_pv($plate, $video_name)
	{

		$where_array = array(
			'mmc_plate_number' => $plate,
			'video_name' => $video_name
		);
		$query = $this->asArray()->where($where_array)->get();
		return $query->getRowArray();	}

	public function edit_mmc($data, $id)
	{

		$this->update( $id, $data);
		return true;
	}

	// Shang
	public function get_mmcs()
	{

		$this->db->order_by('time_stamp', 'desc');
		$query = $this->db->get('cms_mmc_vehicle_ocr');
		return $query->result_array();
	}
}
