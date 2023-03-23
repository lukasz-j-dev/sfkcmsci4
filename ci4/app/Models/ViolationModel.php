<?php

use CodeIgniter\Model;
use App\Libraries\Datatable; // Import library

class ViolationModel extends Model
{
	protected $table = 'cms_violation';
	protected $db;
	protected $session;
	protected $datatable;
	protected $plateModel;
	protected $primaryKey = 'violation_id';
	protected $allowedFields = [
		'violation_id',
		'municipality_id',
		'camera_id',
		'snapshot_id',
		'mmc_uuid',
		'violation_uuid',
		'violation_number',
		'violation_pin',
		'violation_type',
		'plate_number',
		'violation_photo_url',
		'violation_video_url',
		'violation_stop_classification',
		'violation_status',
		'violation_added_date',
		'violation_date',
		'violation_notice_date',
		'violation_last_modified_date',
		'payment_violation_fine_amount',
		'payment_amount',
		'payment_due_date',
		'payment_date',
		'payment_status',
		'payment_method',
		'payment_receipt_email',
		'disputed_date',
		'dismissed_date',
		'dismiss_reason',
		'created_by_id',
		'stats_is_first_violation',
		'stats_sent_status',
		'stats_warning_period',
		'threshold_limit',
		'threshold_time',
		'payment_fine_amount'
	];
	public function __construct()
	{
		$this->db = db_connect();
		$this->session = session();
		$this->datatable =  new Datatable($this);
		$this->plateModel = model('PlateModel');
	}

	public function add_violation($data)
	{
		if (isset($data['plate_number']) && isset($data['violation_date'])) {
			if (!$this->plateModel->plate_exist($data['plate_number']))
				$this->plateModel->add_plate(array('plate_number' => $data['plate_number']));

			$is_exist = $this->is_exist_violation_by_params_new($data['plate_number'], $data['violation_date']);
			if (empty($is_exist)) {

				if (isset($data['violation_status']) && ($data['violation_status'] == 3 || $data['violation_status'] == 5 || $data['violation_status'] == 6)) {
					$data['stats_sent_status'] = 1;
				}

				$this->insert($data);
				$insert_id = $this->getInsertID();

				return  $insert_id;
			}
		}

		return 0;
	}

	// Shang - For Previous and Next button
	public function delete_violation_temp_id_by_userid($id)
	{

		$db      = \Config\Database::connect();
		//$db->table('cms_violation_temp_id')->delete(['user_id' => $id]);
	}

	public function add_violation_temp_id($v_data)
	{
		//  $this->db->table('cms_violation_temp_id')->insert( $v_data);

		return $this->db->insertID();
	}

	public function get_violations_list()
	{
		$wh = array();
		$SQL = 'SELECT * FROM cms_violation';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			return $this->datatable->LoadJson($SQL, $WHERE);
		} else {
			return $this->datatable->LoadJson($SQL);
		}
	}

	public function get_violations_withdemo()
	{
		$wh = array("plate_number = 'DEMO'");
		$SQL = 'SELECT * FROM cms_violation';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			return $this->datatable->LoadJson($SQL, $WHERE);
		} else {
			return $this->datatable->LoadJson($SQL);
		}
	}

	public function get_adjacent_violations_details($id, $next = true)
	{
		// Shang - For Previous and Next button
		$violation_id_hash  = array();
		$violation_idx_hash = array();

		// $query = $this->db->table('cms_violation_temp_id')->select('v_id')->where('user_id', $this->session->get('admin_id'))->get();
		// // $this->db->select('v_id');
		// // $this->db->from('cms_violation_temp_id');
		// // $this->db->where('user_id', $this->session->userdata('admin_id'));
		// if ($query->getNumRows() > 0) {
		// 	$violations_list = $query->getResult();
		// 	foreach ($violations_list as $idx => $violation) {
		// 		$violation_id_hash[ $violation->v_id ] = $idx;
		// 		$violation_idx_hash[ $idx ] = $violation->v_id;
		// 	}

		// 	if (isset($violation_id_hash[ $id ])) {
		// 		$cur_idx = $violation_id_hash[ $id ];
		// 		if ($next) {
		// 			$tmp_idx = $cur_idx + 1;
		// 			if ( isset($violation_idx_hash[ $tmp_idx ]) && $tmp_idx < count( $violation_idx_hash ) )
		// 				$id  = $violation_idx_hash[$tmp_idx];
		// 			else $id = $violation_idx_hash[ count( $violation_idx_hash ) - 1 ];
		// 		} else {
		// 			$tmp_idx = $cur_idx - 1;
		// 			if ( isset( $violation_idx_hash[ $tmp_idx ] ) && $tmp_idx >= 0 )
		// 				$id  = $violation_idx_hash[ $tmp_idx ];
		// 			else $id = $violation_idx_hash[ 0 ];
		// 		}

		// 		$query = $this->db->where("id", $id)->get('cms_violation');
		// 		return $query->row_array();
		// 	}
		// }

		/*
		$sym = $next ? ' >' : ' <';
		$order = $next ? 'asc' : 'desc';
		$query = $this->db->where("id" . $sym,  $id)->limit(1, 0)->order_by('id', $order)->get('cms_violation');

		return $result = $query->row_array();
		*/
	}

	// All
	public function get_violations_all($sort_field = '', $sort_order = '')
	{

		$results = array();
		$wh  = array('a.violation_status != 4', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			// return $this->datatable->LoadJson($SQL,$WHERE);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			// return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if ( is_array($results) && count($results) ) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Duplicates
	public function get_violations_duplicates($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh  = array('a.violation_status != 4', 'a.violation_status != 5', "a.plate_number not like '%DEMO%'");
		$SQL = '
			SELECT count(a.violation_id) as cnt, group_concat(a.violation_id) as duplicated_ids, a.*, b.stats_total_violations, c.scene_location
			FROM cms_violation as a
			INNER JOIN
			(
				SELECT count(violation_id) as cnt, group_concat(violation_id), violation_id, plate_number, violation_video_url, violation_photo_url, violation_date
				FROM cms_violation
				where violation_status <> 4 and violation_status <> 5 and plate_number  not like "%DEMO%"
				GROUP BY plate_number
				having cnt > 1
			) temp ON a.plate_number = temp.plate_number and a.violation_date = temp.violation_date and  abs(timestampdiff(minute, STR_TO_DATE(concat("2022-11-29 ", a.violation_date), "%Y-%m-%d %h:%i %p"), STR_TO_DATE(concat("2022-11-29 ", temp.violation_date), "%Y-%m-%d %h:%i %p"))) <= 360
			inner join cms_plate as b on a.plate_number = b.plate_number
			inner join cms_camera_scene as c on a.camera_id = c.scene_id
		';

		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			$results = $this->datatable->LoadJson($SQL, $WHERE, 'group by a.plate_number having cnt > 1 ', ' a.plate_number, a.violation_date,  a.violation_id desc ');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		$duplicated_results = array();
		$duplicated_record_cnt = 0;

		if (is_array($results) && count($results)) {
			foreach ($results['data'] as $result) {
				if ($result['cnt'] > 1) {
					$ids = $result['duplicated_ids'];
					$ids_arr = explode(',', $ids);
					if (is_array($ids_arr) && count($ids_arr)) {
						foreach ($ids_arr as $id) {
							$duplicated_record_cnt++;
							$query = '
								select b.dmv_status, b.dmv_contact_zipcode, a.*, c.scene_location
								from cms_violation as a
								left join cms_plate as b on a.plate_number = b.plate_number
								inner join cms_camera_scene as c on a.camera_id = c.scene_id
								where a.violation_id = ' . $id . '
							';
							$res = $this->db->query($query)->getResultArray();
							$duplicated_results[] = $res[0];
						}
					}
				}
			}

			$results['data'] = $duplicated_results;
			$results['recordsTotal'] = $duplicated_record_cnt;
			$results['recordsFiltered'] = $duplicated_record_cnt;
		}

		return $results;
	}

	// Duplicates new
	public function get_violations_duplicates_new($sort_field = '', $sort_order = '', $type)
	{
		$results = array();
		$where = '';
		$group_by = '';
		$order = ' v.plate_number, v.violation_date v.id desc ';
		// WHERE condition based on type of page: 6hours / exact_date
		switch ($type) {
			case '6hours':
				$query = '
					select count(a.violation_id) as cnt, group_concat(a.violation_id) as duplicated_ids, b.stats_total_violations, c.scene_location
					from cms_violation as a, cms_violation temp, cms_plate b, cms_camera_scene c';
                $where = 'a.violation_id <> temp.violation_id 
                and a.violation_status not in (4, 5) 
                AND a.plate_number NOT LIKE "%DEMO%" 
                and temp.violation_status not in (4, 5) 
                AND temp.plate_number NOT LIKE "%DEMO%" 
                and a.plate_number = temp.plate_number 
                and abs(timestampdiff(minute, a.violation_date, temp.violation_date)) <= 360
				and a.plate_number = b.plate_number and a.camera_id = c.scene_id';
				$group_by = 'group by a.plate_number having cnt > 1';
				break;
			case 'exact_date':
				$query = '
					select  count(a.violation_id) as cnt, group_concat(a.violation_id) as duplicated_ids, STR_TO_DATE(CONCAT(SUBSTRING(a.violation_date FROM 1 FOR 11), a.violation_date), "%Y-%m-%d %h:%i %p") as full_date, b.stats_total_violations, c.scene_location
					from cms_violation a, cms_plate b, cms_camera_scene c';
				$where = 'a.violation_status not in (4, 5) AND a.plate_number NOT LIKE "%DEMO%" 
				and a.plate_number = b.plate_number and a.camera_id = c.scene_id';
				$group_by = 'group by a.plate_number, full_date having cnt > 1';
				break;
		}

		// Build the query


		// Fetch duplicates from DB
		$results = $this->datatable->LoadJson($query, $where, $group_by, $order);

		// Search for fetched duplicated informations for display
		$duplicated_results = array();
		$duplicated_record_cnt = 0;
		if (is_array($results) && count($results)) {
			foreach ($results['data'] as $result) {
				if ($result['cnt'] > 1) {
					$ids = $result['duplicated_ids'];
					$ids_arr = explode(',', $ids);
					if (is_array($ids_arr) && count($ids_arr)) {
						foreach ($ids_arr as $id) {
							$duplicated_record_cnt++;
							$query = '
								select a.*, b.dmv_status, b.dmv_contact_zipcode, b.dmv_vehicle_body, b.dmv_vehicle_make, b.dmv_vehicle_year, b.stats_total_violations, c.scene_location, c.scene_number, c.scene_zipcode
								from cms_violation as a
								left join cms_plate as b on a.plate_number = b.plate_number
								inner join cms_camera_scene as c on a.camera_id = c.scene_id
								where a.violation_id = ' . $id . '
							';
							$res = $this->db->query($query)->getResultArray();
							$duplicated_results[] = $res[0];
						}
					}
				}
			}

			$results['data'] = $duplicated_results;
			$results['recordsTotal'] = $duplicated_record_cnt;
			$results['recordsFiltered'] = $duplicated_record_cnt;
		}

		return $results;
	}

	// Duplicate media
	public function get_violations_duplicate_media()
	{
		$results = array();
		$wh  = array("tbl.plate not like '%DEMO%'");
		$SQL = '
			select tbl.id, tbl.plate_number, tbl.violation_number, tbl.pin, tbl.violation_photo_url, tbl.violation_video_url, tbl.violation_date, tbl.violation_added_date, tbl.violation_notice_date, tbl.status, tbl.payment_status, tbl.camera,
			b.dmv_contact_zipcode, b.dmv_status, d.scene_location, c.mmc_plate_score
			from 
			(
				(
					select count(id) as cnt, id, plate_number, violation_number, pin, violation_photo_url, violation_video_url, violation_date,  violation_added_date, violation_notice_date, status, payment_status, camera
					from cms_violation
					where 1 and plate_number  not like "%DEMO%"
					group by id, violation_photo_url
					having cnt > 1
					order by violation_photo_url
				)
				union all
				(
					select count(id) as cnt, id, plate_number, violation_number, pin, violation_photo_url, violation_video_url, violation_date,  violation_added_date, violation_notice_date, status, payment_status, camera
					from cms_violation 
					where 1 and plate_number  not like "%DEMO%"
					group by id, violation_video_url
					having cnt > 1
					order by violation_video_url
				)
			) as tbl
			left join cms_plate as b on tbl.plate = b.plate_number
			left join cms_mmc_vehicle_ocr as c on tbl.plate = c.mmc_plate_number
			left join cms_camera_scene as d on tbl.camera = d.scene_id
		';

		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			// echo $WHERE;
			$results = $this->datatable->LoadJson($SQL, $WHERE, 'group by tbl.id, tbl.plate_number, tbl.violation_number, tbl.violation_photo_url, tbl.violation_video_url, tbl.violation_date,  tbl.violation_added_date, tbl.violation_notice_date, tbl.status, tbl.payment_status, tbl.camera ', ' tbl.id desc ');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		return $results;
	}

	// Duplicate media new
	public function get_violations_duplicate_media_new($sort_field = '', $sort_order = '', $type)
	{
		$results = array();

		$query = '
					select count(a.violation_id) as cnt, group_concat(a.violation_id) as duplicated_ids, 
					       SUBSTRING_INDEX(a.violation_photo_url, "/", -1) as file_name_duplicate, SUBSTRING_INDEX(a.violation_video_url, "/", -1) as video_name_duplicate, a.*,
					       b.stats_total_violations, b.dmv_contact_zipcode, b.dmv_status, d.scene_location
					from cms_violation a, cms_plate b, cms_camera_scene d';
		// a.violation_status in (1, 2, 7) and a.plate_number not like "%DEMO%"
		$where = ' a.plate_number = b.plate_number and a.camera_id = d.scene_id';
		switch ($type) {
			case 'images':
				$group_by = ' group by file_name_duplicate having cnt > 1';
				$order = ' file_name_duplicate, a.violation_id desc ';
				break;
			case 'videos':
				$group_by = ' group by video_name_duplicate having cnt > 1';
				$order = ' video_name_duplicate, a.violation_id desc ';
				break;
			default:
				$group_by = ' group by file_name_duplicate having cnt > 1';
				$order = ' file_name_duplicate, a.violation_id desc ';
		}

		// Fetch duplicates from DB
		$results = $this->datatable->LoadJson($query, $where, $group_by, $order);

		$duplicated_results = array();
		$duplicated_record_cnt = 0;
		if (is_array($results) && count($results)) {
			foreach ($results['data'] as $result) {
				if ($result['cnt'] > 1) {
					$ids = $result['duplicated_ids'];
					$ids_arr = explode(',', $ids);
					if (is_array($ids_arr) && count($ids_arr)) {
						foreach ($ids_arr as $id) {
							$duplicated_record_cnt++;
							$query = '
								select b.dmv_status, b.dmv_contact_zipcode, a.*, c.scene_location
								from cms_violation as a
								left join cms_plate as b on a.plate_number = b.plate_number
								inner join cms_camera_scene as c on a.camera_id = c.scene_id
								where a.violation_id = ' . $id . '
							';
							$res = $this->db->query($query)->getResultArray();
							$duplicated_results[] = $res[0];
						}
					}
				}
			}

			$results['data'] = $duplicated_results;
			$results['recordsTotal'] = $duplicated_record_cnt;
			$results['recordsFiltered'] = $duplicated_record_cnt;
		}

		return $results;
	}

	// Missing Media
	public function get_violations_missing_media($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array("a.violation_photo_url = ''", "a.violation_photo_url is null", "a.violation_video_url = ''", "a.violation_video_url is null");
		$EXTRA_WHERE_AND = array('a.violation_status != 4');

		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' or ', $wh);
			$whereand = implode($EXTRA_WHERE_AND);
			$whereand .= " and a.plate_number != 'DEMO'";
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ', $whereand);
		} else {
			//return $this->datatable->LoadJsoncus($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Missing Video
	public function get_violations_missing_video($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array("a.violation_status != 4", "a.plate_number != 'DEMO'");

		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ', 'a.violation_id desc');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		return $results;
	}

	// Reviewed
	public function get_violations_reviewed($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.violation_status = 2', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_contact_zipcode, b.dmv_status, b.stats_first_notice_date, b.stats_total_violations, c.mmc_plate_number as mmc_plate_number, c.mmc_plate_score, a.*, b.dmv_contact_city
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
			left join cms_mmc_vehicle_ocr as c on a.plate_number = c.mmc_plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			// return $this->datatable->LoadJson($SQL,$WHERE, ' group by a.violation_id');
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id');
		} else {
			// return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// New
	public function get_violations_new($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.violation_status = 1', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_contact_city, b.dmv_contact_zipcode, b.dmv_status, b.stats_first_notice_date, b.stats_total_violations, c.mmc_plate_number as mmc_plate_number, c.mmc_plate_score,
			a.violation_date, a.payment_due_date, a.violation_notice_date, a.payment_status, a.stats_warning_period,
			a.violation_id, a.violation_number, a.violation_pin, a.plate_number, a.violation_photo_url, a.violation_date, a.payment_amount, 
			b.dmv_contact_name, b.dmv_contact_address, b.dmv_contact_zipcode
			from cms_violation as a
			left join cms_mmc_vehicle_ocr as c on a.plate_number = c.mmc_plate_number
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		// echo $SQL;
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			//return $this->datatable->LoadJson($SQL,$WHERE, ' group by a.violation_id');
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id');
		} else {
			//return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		/*
		$wh  = array('a.violation_status != 4', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			// return $this->datatable->LoadJson($SQL,$WHERE);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			// return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}
		*/

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Pre-qualified
	public function get_violations_prequalified($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.violation_status = 1', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_contact_zipcode, b.dmv_status, b.stats_total_violations, c.mmc_plate_number as mmc_plate_number, c.mmc_plate_score, a.*, b.dmv_contact_city
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
			left join cms_mmc_vehicle_ocr as c on a.plate_number = c.mmc_plate_number
		';
		if (count($wh) > 0) {
			$WHERE  = implode(' and ', $wh);
			$WHERE .= ' and c.mmc_plate_score >= 85';
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);

		// 	$results['data'] = array_filter(
		// 		$results['data'],
		// 		function ($obj) {
		// 			return $obj['violation_total'] == 1;
		// 		}
		// 	);
		// }

		return $results;
	}

	// Multi violations
	public function get_violations_multiviolations($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.violation_status = 1', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_contact_zipcode, b.dmv_status, b.stats_total_violations, c.mmc_plate_number as mmc_plate_number, c.mmc_plate_score, a.*, b.dmv_contact_city
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
			left join cms_mmc_vehicle_ocr as c on a.plate_number = c.mmc_plate_number
		';
		if (count($wh) > 0) {
			$WHERE  = implode(' and ', $wh);
			$WHERE .= ' and c.mmc_plate_score >= 85';
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);

		// 	$results['data'] = array_filter(
		// 		$results['data'],
		// 		function ($obj) {
		// 			return $obj['violation_total'] > 1;
		// 		}
		// 	);
		// }

		return $results;
	}

	// Not mailed yet
	public function get_violations_notmailedyet()
	{
		$results = array();
		$wh = array('a.violation_status = 1', "a.plate_number != 'DEMO'", 'a.stats_warning_period = 0');
		$SQL = '
			select b.dmv_contact_zipcode, b.dmv_status, b.stats_total_violations, c.mmc_plate_number as mmc_plate_number, c.mmc_plate_score, a.*, b.dmv_contact_city
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
			left join cms_mmc_vehicle_ocr as c on a.plate_number = c.mmc_plate_number
		';
		if (count($wh) > 0) {
			$WHERE  = implode(' and ', $wh);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		return $results;
	}

	// Within warning period
	public function get_violations_withinwarningperiod()
	{
		$results = array();
		$wh = array('a.violation_status = 1', "a.plate_number != 'DEMO'", 'a.stats_warning_period = 2');
		$SQL = '
			select b.dmv_contact_zipcode, b.dmv_status, b.stats_total_violations, c.mmc_plate_number as mmc_plate_number, c.mmc_plate_score, a.*, b.dmv_contact_city
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
			left join cms_mmc_vehicle_ocr as c on a.plate_number = c.mmc_plate_number
		';
		if (count($wh) > 0) {
			$WHERE  = implode(' and ', $wh);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		return $results;
	}

	// Shang
	public function get_violations_count($plate_number, $violation_notice_date)
	{
		$today = date('Y-m-d') . ' 00:00:00';
		$query = "
			select count(*) as cnt 
			from cms_violation
			where plate_number  = '" . $plate . "' and DATEDIFF('" . $today . "', '" . $violation_notice_date . "') < 60  and status <> 4
			group by plate
		";
		$res = $this->db->query($query)->getResultArray();
		if (isset($res[0]['cnt']) && is_numeric($res[0]['cnt']) && $res[0]['cnt'] > 0) {
			$data = array(
				'violations_30days' => 1,
				'violations_60days' => 1
			);
			$this->plateModel->update_by_plate($data, $plate);
			return $res[0]['cnt'];
		}

		return 0;
	}

	// Dismissed
	public function get_violations_dismissed($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.violation_status = 5', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			//return $this->datatable->LoadJson($SQL,$WHERE);
			$results = $results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			//return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Paid
	public function get_violations_paid($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.payment_status = 1', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			// return $this->datatable->LoadJson($SQL,$WHERE);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			//return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Unpaid
	public function get_violations_unpaid($sort_field = '', $sort_order = '')
	{
		$results = array();
		$cur_time = time();
		$wh = array("a.violation_status in (3,6)", "a.payment_status=0", "a.plate_number != 'DEMO'");

		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			//return $this->datatable->LoadJson($SQL,$WHERE);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			//return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Past due
	public function get_violations_pastdue($sort_field = '', $sort_order = '')
	{
		$today = date('Y-m-d') . ' 00:00:00';
		$wh = array('a.violation_status != 4', 'a.violation_status != 5', 'a.payment_status = 0', "a.plate_number not like '%DEMO%'", "DATEDIFF('" . $today . "', a.payment_due_date) > 8");

		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Warning
	public function get_violations_warning($sort_field = '', $sort_order = '')
	{
		$today = date('Y-m-d') . ' 00:00:00';
		$wh = array('(a.violation_status = 1 or a.violation_status = 2)', "a.plate_number not like '%DEMO%'", "a.violation_type = 2");

		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			$results = $this->datatable->LoadJson($SQL);
		}

		return $results;
	}

	// Mailed
	public function get_violations_mailed($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh  = array('a.violation_status = 3', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_first_notice_date, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			// return $this->datatable->LoadJson($SQL,$WHERE, ' group by a.violation_id');
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id');
		} else {
			// return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		/*
		if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
			// if (is_array($results) && count($results)) {
			$new_array = array();
			foreach ($results['data'] as $result) {
				$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
				$result['violation_total'] = $violation_total;
				$new_array[] = $result;
			}
			$results['data'] = $new_array;

			$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
			array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		}
		*/

		return $results;
	}

	// Archived
	public function get_violations_archived($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.violation_status = 4', "a.plate_number != 'DEMO'");

		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			//return $this->datatable->LoadJson($SQL,$WHERE);
			$results = $results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			//return $this->datatable->LoadJson($SQL);
			$results = $results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Disputed
	public function get_violations_disputed($sort_field = '', $sort_order = '')
	{
		$results = array();
		$wh = array('a.violation_status = 6', "a.plate_number != 'DEMO'");
		$SQL = '
			select b.dmv_status, b.dmv_contact_zipcode, b.stats_total_violations, a.*
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
		';
		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			//return $this->datatable->LoadJson($SQL,$WHERE);
			$results = $this->datatable->LoadJson($SQL, $WHERE, ' group by a.violation_id ');
		} else {
			//return $this->datatable->LoadJson($SQL);
			$results = $this->datatable->LoadJson($SQL);
		}

		// Shang
		// if (is_array($results) && count($results) && !empty($sort_field) && $sort_field == 'violation_total') {
		// 	// if (is_array($results) && count($results)) {
		// 	$new_array = array();
		// 	foreach ($results['data'] as $result) {
		// 		$violation_total = $this->get_violations_count($result['plate_number'], $result['violation_notice_date']);
		// 		$result['violation_total'] = $violation_total;
		// 		$new_array[] = $result;
		// 	}
		// 	$results['data'] = $new_array;

		// 	$sort_order = $sort_order == 'asc' ? SORT_ASC : SORT_DESC;
		// 	array_multisort(array_column($results['data'], 'violation_total'), $sort_order, $results['data']);
		// }

		return $results;
	}

	// Other violations
	public function get_violations_other($id, $plate)
	{
		$results = array();
		$where   = array("t.violation_status != 4", "t.plate_number != 'DEMO'");

		$res_plate = $this->plateModel->get_plates_by_plate($plate);

		if (is_array($res_plate) && count($res_plate)) {

			$address  = trim($res_plate['dmv_contact_address']);
			$city     = trim($res_plate['dmv_contact_city']);
			$state 	  = trim($res_plate['dmv_contact_state']);
			$zip_code = trim($res_plate['dmv_contact_zipcode']);

			$SQL = "
				select t.*, b.plate_number, b.dmv_contact_address, b.dmv_contact_city, b.dmv_contact_state, b.dmv_contact_zipcode, b.dmv_status, t.violation_notice_date, c.scene_location
				from
				(
					select tbl.*
					from 
					(
						(
							select violation_id, plate_number, violation_number, violation_pin, violation_date, violation_notice_date, violation_video_url, violation_photo_url, violation_status, payment_status, camera_id, stats_warning_period, violation_type
							from cms_violation
							where plate_number  = '$plate' and plate_number  <> 'DEMO' and violation_id <> '$id'
						)
						UNION
						(
							select a.violation_id, b.plate_number, a.violation_number, a.violation_pin, a.violation_date, a.violation_notice_date, a.violation_video_url, a.violation_photo_url, a.violation_status, a.payment_status, a.camera_id, a.stats_warning_period, a.violation_type
							from cms_violation as a
							left join cms_plate as b on a.plate_number = b.plate_number
							where b.plate_number <> 'DEMO' and a.violation_id <> '$id' and b.dmv_contact_address = '$address' and b.dmv_contact_city = '$city' and b.dmv_contact_state = '$state' and b.dmv_contact_zipcode = '$zip_code'
						)
					) as tbl
					group by tbl.violation_id
				) as t
				left join cms_plate as b on t.plate_number = b.plate_number
				left join cms_camera_scene as c on t.camera_id = c.scene_id

				/*group by t.violation_id
				order by t.plate_number,  t.violation_date desc, t.violation_id desc*/
			";
		} else {
			$SQL = "
				select t.*, b.plate_number, b.dmv_contact_address, b.dmv_contact_city, b.dmv_contact_state, b.dmv_contact_zipcode, b.dmv_status, t.violation_notice_date, c.scene_location
				from
				(
					select tbl.*
					from 
					(
						select violation_id, plate_number, violation_number, violation_pin, violation_date, violation_notice_date, violation_video_url, violation_photo_url, violation_status, payment_status, camera, stats_warning_period, violation_type
						from cms_violation
						where plate_number = '$plate' and plate_number <> 'DEMO' and violation_id <> '$id'
					) as tbl
					group by tbl.violation_id
				) as t
				left join cms_plate as b on t.plate_number = b.plate_number
				left join cms_camera_scene as c on t.camera = c.scene_id

				/*group by t.violation_id
				order by t.plate_number, t.violation_date desc, t.violation_id desc*/
			";
		}

		$WHERE = implode(' and ', $where);
		$results = $this->datatable->LoadJson($SQL, $WHERE, 'group by t.violation_id');

		return $results;
	}

	public function get_duplicates_by_violation($id)
	{

		// get the violation
		$violation = $this->asObject()->find($id);
		$this->datatable =  new Datatable($this);
		if ($violation) {
			$query = 'select t.*, b.plate_number, b.dmv_contact_address, b.dmv_contact_city, b.dmv_contact_state, b.dmv_contact_zipcode, b.dmv_status, b.stats_first_notice_date, c.scene_location
			from cms_violation t
			inner join cms_plate as b on t.plate_number = b.plate_number
			inner join cms_camera_scene as c on t.camera_id = c.scene_id';
			$WHERE = ' t.violation_id <> ' . $violation->violation_id . ' and (SUBSTRING_INDEX(t.violation_photo_url, "/", -1) = ' . 'SUBSTRING_INDEX("' . $violation->violation_photo_url . '", "/", -1)
			or SUBSTRING_INDEX(t.violation_video_url, "/", -1) = ' . 'SUBSTRING_INDEX("' . $violation->violation_video_url . '", "/", -1)
			or (abs(timestampdiff(minute, STR_TO_DATE(concat(SUBSTRING(t.violation_date from 1 for 11), t.violation_date), "%Y-%m-%d %h:%i %p"), STR_TO_DATE(concat(SUBSTRING("' . $violation->violation_date . '" from 1 for 11), "' . $violation->violation_date . '"), "%Y-%m-%d %h:%i %p"))) <= 360) and t.plate_number = "' . $violation->plate_number . '")';
			return $this->datatable->LoadJson($query, $WHERE, 'group by t.violation_id');
		} else return array();
	}

	public function get_all_simple_violations()
	{
		$this->db->order_by('date_added', 'desc');
		$query = $this->db->get('cms_violation');
		return $query->getResultArray();
	}

	public function count_all_violations()
	{
		return $this->db->count_all('cms_violation');
	}

	public function get_all_violations_for_pagination($limit, $offset)
	{
		$wh = array();
		$this->db->order_by('date_added', 'desc');
		$this->db->limit($limit, $offset);

		if (count($wh) > 0) {
			$WHERE = implode(' and ', $wh);
			$query = $this->db->get_where('cms_violation', $WHERE);
		} else {
			$query = $this->db->get('cms_violation');
		}

		return $query->getResultArray();
	}

	public function get_violation_by_id($id)
	{

		return $this->asArray()->find($id);
	}

	public function get_violation_by_id_palte($plate_number, $plate_video, $violation_photo_url)
	{
		/*$query = $this->db->get_where('cms_violation', array('plate_number' => $plate_number,'violation_video_url'=>$plate_video,'violation_photo_url'=>$violation_photo_url));
		return $result = $query->row_array();*/

		$sql = "select * from cms_violation where plate_number='" . $plate . "' or violation_video_url='" . $plate_video . "' or violation_photo_url='" . $violation_photo_url . "'";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function get_violation_by_id_all()
	{
		/*$query = $this->db->get_where('cms_violation', array('plate_number' => $plate_number,'violation_video_url'=>$plate_video,'violation_photo_url'=>$violation_photo_url));
		return $result = $query->row_array();*/

		$sql = "select * from cms_violation";
		$query = $this->db->query($sql);
		return $query->getResultArray();
	}

	public function get_violation_by_num_pin($violation_number, $pin)
	{
		$query = $this->db->get_where('cms_violation', array('violation_number' => $violation_number, 'pin' => $pin));
		return $query->row_array();
	}

	public function edit_violation($data, $id)
	{
		$this->update($id, $data);

		if (isset($data['violation_status']) && ($data['violation_status'] == 3 || $data['violation_status'] == 5 || $data['violation_status'] == 6)) {
			$sent_status = array('stats_sent_status' => 1);
			$this->update($id, $sent_status);
		}

		return true;
	}

	// Shang
	public function get_csv_data($type)
	{
		$condition = "";

		switch ($type) {
			case "all":
				$condition = "";
				break;
			case "missing_media":
				$condition = "and a.violation_photo_url = '' or a.violation_photo_url is null or a.violation_video_url = '' or a.violation_video_url is null and  a.plate_number != 'DEMO'";
				break;
			case "new":
				$condition = "and a.violation_status = 1 and a.plate_number != 'DEMO'";
				break;
			case "reviewed":
				$condition = "and a.violation_status = 2 and a.plate_number != 'DEMO'";
				break;
			case "mailed":
				$condition = "and a.violation_status = 3 and a.plate_number != 'DEMO'";
				break;
			case "disputed":
				$condition = "and a.violation_status = 6 and a.plate_number != 'DEMO'";
				break;
			case "unpaid":
				$condition = "and a.violation_status in (3, 6) and a.payment_status = 0 and a.plate_number != 'DEMO'";
				break;
			case "paid":
				$condition = "and a.payment_status = 1 and a.plate_number != 'DEMO'";
				break;
			case "dismissed":
				$condition = "and a.violation_status = 5 and a.plate_number != 'DEMO'";
				break;
			case "archived":
				$condition = "and a.violation_status = 4 and a.plate_number != 'DEMO'";
				break;
		}

		$query = "
			select a.*, b.scene_location, c.municipality_id, c.violation_fine_amount
			from cms_violation as a
			left join cms_camera_scene as b on a.camera_id = b.scene_id
			left join cms_municipality as c on a.municipality_id = c.municipality_id
			where 1 $condition
		";
		return $this->db->query($query)->getResultArray();
	}

	public function get_violation_by_plate($plate)
	{
		$this->db->select('violation_photo_url, status, payment_status, violation_date, violation_date');
		$this->db->from('cms_violation');
		$this->db->where('plate_number', $plate);
		$this->db->limit(1, 0);
		$this->db->order_by('id', 'desc');
		$result = $this->db->get()->row_array();
		return $result;
	}

	public function bulk_update_violation_status($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('cms_violation', $data);
		return true;
	}

	public function is_exist_violation_by_params($plate_number, $date) // $plate_number, $video, $image, $date, $time
	{
		if ($plate_number && $date) {


			$query = 'select count(*) as cnt from cms_violation where plate_number="' . $plate_number . '" and violation_date="' . $date . '" ';
			return $this->db->query($query)->getResultArray();
		}

		return array();
	}

	public function is_exist_violation_by_params_new($plate_number, $violation_video) // $plate_number, $video, $image, $date, $time
	{

		if ($plate_number && $violation_video) {

			$query = $this->asArray()->where('plate_number', $plate_number)->where('violation_video_url', $violation_video)->get();
			return $query->getRowArray();
		}
		return [];
	}

	public function get_first_notice_date()
	{
		$query = '
			select violation_id, plate_number, violation_notice_date
			from cms_violation
			where violation_id in (
				select min(a.violation_id)
				from cms_violation as a
				left join cms_plate as b on a.plate_number = b.plate_number
				where (a.violation_status = 3 or a.violation_status = 5 or a.violation_status = 6) and b.plate_number not like "%DEMO%"
				group by b.plate_number
				order by a.violation_id
			)
			group by plate_number
			order by plate_number
		';
		return $this->db->query($query)->getResultArray();
	}

	public function get_warning_period($id)
	{
		$query = '
			/*select DATEDIFF(a.violation_date, b.stats_first_notice_date) as date_diff, a.violation_status, a.violation_notice_date, a.violation_date, b.stats_first_notice_date*/
			select DATEDIFF(b.stats_first_notice_date, a.violation_date) as date_diff, a.violation_status, a.violation_notice_date, a.violation_date, b.stats_first_notice_date
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
			where a.violation_id = ' . $id;

		$result = $this->db->query($query);
		return $result->getResultArray();
	}

	public function recalculate_totals($plate_number = '')
	{

		$where_str = '';
		if ($plate_number) $where_str = ' and plate_number="' . $plate_number . '"';

		// Check each plate_number  number and see how many violations of any status you can find. Update plate_number  table with that total count.
		$query = 'select plate_number, count(*) as violation_total_count from cms_violation where 1 ' . $where_str . ' and plate_number  not like "%DEMO%" group by plate_number order by plate_number';
		$v_lists = $this->db->query($query)->getResultArray();

		foreach ($v_lists as $list) {
			$plate = $list['plate_number'];
			$violation_total_count = $list['violation_total_count'];
			$data = array('stats_total_violations' => $violation_total_count);
			$this->plateModel->update_by_plate($data, $plate);
		}

		return true;
	}

	public function update_warning_period($plate_number = '')
	{
		// Update warning period
		// 0-Not mailed yet, 1-First violation, 2-Within warning period, 3-Outside warning period, 4-Violation before first notice
		$where_str = '';
		if ($plate_number) $where_str = ' and a.plate="' . $plate_number . '"';

		$query = '
			select a.violation_id, a.violation_date, a.stats_is_first_violation, b.stats_first_notice_date
			from cms_violation as a
			left join cms_plate as b on a.plate_number = b.plate_number
			where 1 ' . $where_str . ' and a.plate_number not like "%DEMO%" 
			order by a.plate_number desc
		';
		$v_lists = $this->db->query($query)->getResultArray();

		foreach ($v_lists as $list) {
			$first_notice_date  = $list['stats_first_notice_date'];
			$violation_date	    = $list['violation_date'];
			$is_first_violation = $list['stats_is_first_violation'];
			$id			   	    = $list['violation_id'];

			$data = null;
			$data = array('stats_warning_period' => 0);
			if (is_null($first_notice_date) || empty($first_notice_date)) {
				$data = array('stats_warning_period' => 0);  // Not mailed yet
			} else {
				if (!$is_first_violation) {
					$violation_date = new DateTime($violation_date);
					$first_notice_date = new DateTime($first_notice_date);
					$interval = $first_notice_date->diff($violation_date);
					// $diff = $interval->days;
					$diff = (int) $interval->format('%R%a');
					if ($diff != FALSE) {
						if ($diff > 8)
							$data = array('stats_warning_period' => 3);
						else {
							if ($diff >= 0 && $diff < 8)
								$data = array('stats_warning_period' => 2);
							else
								$data = array('stats_warning_period' => 4);
						}
					} else $data = array('stats_warning_period' => 0);
				}
			}

			$this->update($id, $data);
		}
	}

	public function update_totalviolationscount_sentstatus_firstnoticedate_from_violation($plate_number = '')
	{
		$where_str = '';
		if ($plate_number) $where_str = ' and plate="' . $plate_number . '"';

		// Check each plate_number  number and see how many violations of any status you can find. Update plate_number  table with that total count.
		$query = 'select plate_number, count(*) as violation_total_count from cms_violation where 1 ' . $where_str . ' and plate_number  not like "%DEMO%" group by plate_number  order by plate_number';
		$v_lists = $this->db->query($query)->getResultArray();
		foreach ($v_lists as $list) {
			$plate = $list['plate_number'];
			$violation_total_count = $list['violation_total_count'];

			// Check if plate_number  exists in plate_number  table
			if (!$this->plateModel->plate_exist($plate))
				$this->plateModel->add_plate(array('plate_number' => $plate));

			$data = array('stats_total_violations' => $violation_total_count);
			$this->plateModel->update_by_plate($data, $plate);
		}

		// For each violation, check if status is 3, 5 or 6, then update sent_status = 1
		$query = 'select violation_id, violation_status, plate_number, stats_sent_status from cms_violation where 1 ' . $where_str . ' and plate_number  not like "%DEMO%" order by plate_number';
		$v_lists = $this->db->query($query)->getResultArray();
		foreach ($v_lists as $list) {
			$id = $list['violation_id'];
			$plate = $list['plate_number'];
			$status = $list['violation_status'];
			// $sent_status = $list['stats_sent_status'];
			if ($status == 3 || $status == 5 || $status == 6) {
				$data = array('stats_sent_status' => 1);
				$this->update($id, $data);
			}
		}

		// Update first notice date
		$query = 'select plate_number  from cms_violation where 1 ' . $where_str . ' and plate_number  not like "%DEMO%" group by plate_number  order by plate_number';
		$v_lists = $this->db->query($query)->getResultArray();
		foreach ($v_lists as $list) {
			$plate = $list['plate_number'];

			$query = 'select violation_id, violation_notice_date from cms_violation where 1 and plate_number  = "' . $plate . '" and stats_sent_status = 1 order by violation_notice_date limit 1';
			$d_lists = $this->db->query($query)->getResultArray();
			foreach ($d_lists as $d) {
				$d_id = $d['violation_id'];
				$d_notice_date = $d['violation_notice_date'];

				$this->plateModel->update_by_plate(array('stats_first_notice_date' => $d_notice_date), $plate);

				$this->update($d_id, array('stats_warning_period' => 1, 'is_first_violation' => 1));
			}
		}

		return true;
	}
}
