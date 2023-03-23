<?php
use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class CameraModel extends Model
{
    protected $table = 'cms_camera_scene';
	protected $db;
	protected $datatable;
	protected $primaryKey = 'scene_id';

	protected $allowedFields = [
		'scene_id',
		'municipality_id',
		'scene_number',
		'scene_location',
		'scene_zipcode',
		'scene_city',
		'scene_state',
		'camera_model',
		'camera_serial_number',
		'camera_coordinates',
		'camera_status',
		'camera_install_date',
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

	public function add_camera($data){
			
		$this->insert($data);
		return  $this->getInsertID();
	}

	//---------------------------------------------------
	public function get_all_villages(){

		return $this->asArray()->findAll();


	}

	public function get_all_cameras(){

		$wh =array();

		$SQL ='SELECT * FROM cms_camera_scene';

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

	public function get_all_simple_cameras(){


		return $this->asArray()->findAll();;

	}



	//---------------------------------------------------

	public function count_all_cameras(){

		return $this->db->count_all('cms_camera_scene');

	}



	//---------------------------------------------------
	
	public function get_all_cameras_for_pagination($limit, $offset){

		$wh =array();	

		$this->db->order_by('date_added', 'desc');

		$this->db->limit($limit, $offset);



		if(count($wh)>0){

			$WHERE = implode(' and ',$wh);

			$query = $this->db->get_where('cms_camera_scene', $WHERE);

		}

		else{

			$query = $this->db->get('cms_camera_scene');

		}

		return $query->result_array();

		//echo $this->db->last_query();

	}




	//---------------------------------------------------

	public function get_camera_by_id($id){

		$query = $this->asArray()->find($id);
		return $query;
	}
	
	public function get_camera_by_name($camera_location){

		$query = $this->db->get_where('cms_camera_scene', array('camera_location' => $camera_location));

		return $result = $query->row_array();

	}

	public function get_camera_by_stop_sign_location($stop_sign_location){

		$query = $this->asArray()->where('scene_number',$stop_sign_location)->get();
		return $query->getRowArray();
	}




	//---------------------------------------------------

	public function edit_camera($data, $id){

		$this->update( $id, $data);
		return true;


	}

}