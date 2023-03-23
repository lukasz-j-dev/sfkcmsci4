<?php

namespace App\Controllers;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\Files\File;

class Violations extends BaseController
{
	protected $violationModel;
	protected $plateModel;
	protected $mmcModel;
	protected $villageModel;
	protected $session;
	protected $cameraModel;
	protected $userModel;
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
		$this->villageModel = model('VillageModel');
		$this->cameraModel = model('CameraModel');
		$this->userModel = model('UserModel');
		
		$this->session = session();
		$this->request = $request;
    }

	public function index()
	{
		$data['view'] = 'admin/violations/violation_list';
		return view('layout', $data);
	}

	public function all()
	{
		$data['view'] = 'admin/violations/violation_all';
		return view('layout', $data);
	}

	public function missingmedia()
	{
		$data['view'] = 'admin/violations/violation_missing_media';
		return view('layout', $data);
	}

	public function missingvideo()
	{
		$data['view'] = 'admin/violations/violation_missing_video';
		return view('layout', $data);
	}

	public function new()
	{
		$data['view'] = 'admin/violations/violation_new';
		return view('layout', $data);
	}

	public function duplicates()
	{
		$data['view'] = 'admin/violations/violation_duplicates';
		return view('layout', $data);
	}

	public function duplicates_new($type)
	{
		switch ($type) {
			case '6hours':
				$data['page_title'] = 'Violation List (Duplicates with 6 Hours range of violation date)';
				$data['page_sub_title'] = 'Showing a list of all violations that are duplicates, where the combination of plate number AND violation date AND violation time appears in at least one other violation ID, with violation date of duplicates are in 6 hours range. Violation status "Archived", "Dismissed" and Plate containing the word DEMO are excluded.';
				break;
			case 'exact_date':
				$data['page_title'] = 'Violation List (Duplicates with exact value of violation date)';
				$data['page_sub_title'] = 'Showing a list of all violations that are duplicates, where the combination of plate number AND violation date AND violation time appears in at least one other violation ID, with violation date of duplicates are excatly the same. Violation status "Archived", "Dismissed" and Plate containing the word DEMO are excluded.';
				break;
		}

		$data['cur_tab'] = 'pre-check';
		$data['sub_tab'] = $type;

		$data['type'] = $type;
		$data['view'] = 'admin/violations/new/violation_duplicates';
		return view('layout', $data);
	}

	public function duplicatemedia()
	{
		$data['view'] = 'admin/violations/violation_duplicate_media';
		return view('layout', $data);
	}

	public function duplicatemedia_new($type)
	{
		$data['type'] = $type;
		$data['cur_tab'] = 'pre-check';
		$data['sub_tab'] = $type;
		$data['view'] = 'admin/violations/new/violation_duplicate_media';
		return view('layout', $data);
	}

	public function prequalified()
	{
		$data['view'] = 'admin/violations/violation_prequalified';
		return view('layout', $data);
	}

	public function multiviolations()
	{
		$data['view'] = 'admin/violations/violation_multiviolations';
		return view('layout', $data);
	}

	public function notmailedyet()
	{
		$data['view'] = 'admin/violations/violation_notmailedyet';
		return view('layout', $data);
	}

	public function withinwarningperiod()
	{
		$data['view'] = 'admin/violations/violation_withinwarningperiod';
		return view('layout', $data);
	}

	public function reviewed()
	{
		$data['view'] = 'admin/violations/violation_reviewed';
		return view('layout', $data);
	}

	public function mailed()
	{
		$data['view'] = 'admin/violations/violation_mailed';
		return view('layout', $data);
	}

	public function disputed()
	{
		$data['view'] = 'admin/violations/violation_disputed';
		return view('layout', $data);
	}

	public function unpaid()
	{
		$data['view'] = 'admin/violations/violation_unpaid';
		return view('layout', $data);
	}

	public function paid()
	{
		$data['view'] = 'admin/violations/violation_paid';
		return view('layout', $data);
	}

	public function pastdue()
	{
		$data['view'] = 'admin/violations/violation_pastdue';
		return view('layout', $data);
	}

	public function warning()
	{
		$data['view'] = 'admin/violations/violation_warning';
		return view('layout', $data);
	}

	public function dismissed()
	{
		$data['view'] = 'admin/violations/violation_dismissed';
		return view('layout', $data);
	}

	public function archived()
	{
		$data['view'] = 'admin/violations/violation_archived';
		return view('layout', $data);
	}

	public function demo()
	{
		$data['view'] = 'admin/violations/violation_all_withdemo';
		return view('layout', $data);
	}

	public function bulk_edit()
	{
		$data['view'] = 'admin/violations/violation_bulk_edit';
		return view('layout', $data);
	}

	public function datatable_json_all()
	{
		// Shang
		$records = $this->violationModel->get_violations_all($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {

			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			if ($row['violation_type'] == 1) $row['violation_type'] = 'Violation';
			if ($row['violation_type'] == 2) $row['violation_type'] = 'Warning';
			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				$row['violation_type'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/all') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>'
			
			);
			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Duplicates
	public function datatable_json_duplicates()
	{
		// Shang
		$records = $this->violationModel->get_violations_duplicates($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {

			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			if ($row['violation_type'] == 1) $row['violation_type'] = 'Violation';
			if ($row['violation_type'] == 2) $row['violation_type'] = 'Warning';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total']))
			// 	$violation_total = $row['violation_total'];
			// else
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['scene_location'],
				$row['zip'],
				$row['stats_total_violations'],
				$row['violation_type'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/all') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Duplicates new
	public function datatable_json_duplicates_new($type)
	{
		// Shang
		$records = $this->violationModel->get_violations_duplicates_new($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir'], $type);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {

			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			if ($row['violation_type'] == 1) $row['violation_type'] = 'Violation';
			if ($row['violation_type'] == 2) $row['violation_type'] = 'Warning';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total']))
			// 	$violation_total = $row['violation_total'];
			// else
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'] . ' - ' . $row['violation_type'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				date('Y-m-d H:i A',strtotime($row['violation_date'])),
                $status_txt . '-' . $payment_status,
                $row['scene_number'] . '-' . $row['scene_zipcode'],
                $row['stats_total_violations'],
                $row['dmv_status'],
                $row['dmv_vehicle_body'] . '/' . $row['dmv_vehicle_make'] . '/' . $row['dmv_vehicle_year'],
                date('Y-m-d',strtotime($row['violation_added_date'])),
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;

		header('Content-Type: application/json');
		echo json_encode($records);
	}

	// Duplicate Media
	public function datatable_json_duplicate_media()
	{
		$type='';
		// Shang
		$records = $this->violationModel->get_violations_duplicate_media_new($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir'], $type);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {

			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['violation_added_date'] == '0000-00-00 00:00:00' || $row['violation_added_date'] == null || date('Y', strtotime($row['violation_added_date']))  == 1969) {
				$violation_added_date = '';
			} else {
				$violation_added_date = date('M d Y', strtotime($row['violation_added_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			if ($row['violation_type'] == 1) $row['violation_type'] = 'Violation';
			if ($row['violation_type'] == 2) $row['violation_type'] = 'Warning';

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				'
					<a href="' . $row['violation_video_url'] . '" target="_blank">video link</a>
				',
				$violation_date . ' ' . $row['violation_date'],
				$violation_added_date,
				$violation_notice_date,
				$row['scene_location'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['dmv_status'],       // Shang
				$row['violation_type'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/all') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>'
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// With Demo
	public function datatable_json_withdemo()
	{
		$records = $this->violationModel->get_violations_withdemo();

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date,
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id']) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Paid
	public function datatable_json_paid()
	{
		// Shang
		$records = $this->violationModel->get_violations_paid($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/paid') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				'
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Past due
	public function datatable_json_pastdue()
	{
		// Shang
		$records = $this->violationModel->get_violations_pastdue($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/paid') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				'
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Warning
	public function datatable_json_warning()
	{
		// Shang
		$records = $this->violationModel->get_violations_warning($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/paid') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				'
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Missing Media
	public function datatable_json_missing_media()
	{
		// Shang
		$records = $this->violationModel->get_violations_missing_media($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/missingmedia') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				'
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Missing Video
	public function datatable_json_missing_video()
	{
		// Shang
		$records = $this->violationModel->get_violations_missing_video($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				'<video style="width: 184px; height: 84px;" controls>
					<source src="' . $row['violation_video_url'] . '" type="video/mp4">
				</video>',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/missingmedia') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				'
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Reviewed
	public function datatable_json_reviewed()
	{
		// Shang
		$records = $this->violationModel->get_violations_reviewed($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) { 
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'Reviewed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			$violation_type = '';
			if ($row['violation_type'] == 1) $violation_type = 'Violation';
			else if ($row['violation_type'] == 2) $violation_type = 'Warning';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			// Warning period
			// $warning_period_text = 'Not mailed yet';
			// $period = $this->violationModel->get_warning_period( $row['violation_id'] );
			// if ( $period['date_diff'] > 0 && $period['date_diff'] <= 14 ) {
			// 	$warning_period_text = 'Within warning period';
			// 	$this->violationModel->edit_violation( array('stats_warning_period' => 1), $row['violation_id'] );
			// }
			// else if ( $period['date_diff'] > 14 ) {
			// 	$warning_period_text = 'Outside warning period';
			// 	$this->violationModel->edit_violation( array('stats_warning_period' => 0), $row['violation_id'] );
			// }
			// else {
			// 	$stats_first_notice_date  = date('M d Y', strtotime($period['stats_first_notice_date']));
			// 	$violation_notice_date    = date('M d Y', strtotime($period['violation_notice_date']));
			// 	$violation_date = date('M d Y', strtotime($period['violation_date']));

			// 	if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date == $violation_notice_date )
			// 		$warning_period_text = 'First violation';
			// 	else if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date != $violation_notice_date && $stats_first_notice_date > $violation_date )
			// 		$warning_period_text = 'Violation before first notice';
			// 	else
			// 		$warning_period_text = 'Not mailed yet';
			// 	$this->violationModel->edit_violation( array('stats_warning_period' => 0), $row['violation_id'] );
			// }

			// Warning period - https://a.cl.ly/GGuzgJ17
			$warning_period_text = '';
			if ( $row['stats_warning_period'] == 0 ) $warning_period_text = 'Not mailed yet';
			else if ( $row['stats_warning_period'] == 1 ) $warning_period_text = 'First violation';
			else if ( $row['stats_warning_period'] == 2 ) $warning_period_text = 'Within warning period';
			else if ( $row['stats_warning_period'] == 3 ) $warning_period_text = 'Outside warning period';
			else $warning_period_text = 'Violation before first notice';

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				$row['mmc_plate_number'],
				$row['mmc_plate_score'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$violation_type,
				$payment_status,
				$row['dmv_contact_city'],
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				$row['stats_first_notice_date'],
				$warning_period_text,
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/new') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Unpaid
	public function datatable_json_unpaid()
	{
		// Shang
		$records = $this->violationModel->get_violations_unpaid($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/unpaid') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				'
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// New
	public function datatable_json_new()
	{
		// Shang
		$records = $this->violationModel->get_violations_new($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Violation total
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			// Warning period
			// $warning_period_text = 'Not mailed yet';
			// $period = $this->violationModel->get_warning_period( $row['violation_id'] );
			// if ( $period['date_diff'] > 0 && $period['date_diff'] <= 14 ) {
			// 	$warning_period_text = 'Within warning period';
			// 	$this->violationModel->edit_violation( array('stats_warning_period' => 1), $row['violation_id'] );
			// }
			// else if ( $period['date_diff'] > 14 ) {
			// 	$warning_period_text = 'Outside warning period';
			// 	$this->violationModel->edit_violation( array('stats_warning_period' => 0), $row['violation_id'] );
			// }
			// else {
			// 	$stats_first_notice_date  = date('M d Y', strtotime($period['stats_first_notice_date']));
			// 	$violation_notice_date    = date('M d Y', strtotime($period['violation_notice_date']));
			// 	$violation_date = date('M d Y', strtotime($period['violation_date']));

			// 	if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date == $violation_notice_date )
			// 		$warning_period_text = 'First violation';
			// 	else if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date != $violation_notice_date && $stats_first_notice_date > $violation_date )
			// 		$warning_period_text = 'Violation before first notice';
			// 	else
			// 		$warning_period_text = 'Not mailed yet';
			// 	$this->violationModel->edit_violation( array('stats_warning_period' => 0), $row['violation_id'] );
			// }

			// Warning period - https://a.cl.ly/GGuzgJ17
			$warning_period_text = '';
			if ( $row['stats_warning_period'] == 0 ) $warning_period_text = 'Not mailed yet';
			else if ( $row['stats_warning_period'] == 1 ) $warning_period_text = 'First violation';
			else if ( $row['stats_warning_period'] == 2 ) $warning_period_text = 'Within warning period';
			else if ( $row['stats_warning_period'] == 3 ) $warning_period_text = 'Outside warning period';
			else $warning_period_text = 'Violation before first notice';

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				$row['mmc_plate_number'],
				$row['mmc_plate_score'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_city'],
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				$row['stats_first_notice_date'],
				$warning_period_text,
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/new') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Pre-qualified
	public function datatable_json_prequalified()
	{
		$records = $this->violationModel->get_violations_prequalified($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total']))
			// 	$violation_total = $row['violation_total'];
			// else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				$row['mmc_plate_number'],
				$row['mmc_plate_score'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_city'],
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/new') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Multi violations
	public function datatable_json_multiviolations()
	{
		$records = $this->violationModel->get_violations_multiviolations($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total']))
			// 	$violation_total = $row['violation_total'];
			// else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				$row['mmc_plate_number'],
				$row['mmc_plate_score'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_city'],
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/new') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Not mailed yet
	public function datatable_json_notmailedyet()
	{
		$records = $this->violationModel->get_violations_notmailedyet();

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				$row['mmc_plate_number'],
				$row['mmc_plate_score'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_city'],
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/new') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Within warning period
	public function datatable_json_withinwarningperiod()
	{
		$records = $this->violationModel->get_violations_withinwarningperiod();

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($row['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($row['violation_status'] == 3) $status_txt = 'Mailed';
			if ($row['violation_status'] == 4) $status_txt = 'Archived';
			if ($row['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($row['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				$row['mmc_plate_number'],
				$row['mmc_plate_score'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_city'],
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/new') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Dismissed
	public function datatable_json_dismissed()
	{
		// Shang
		$records = $this->violationModel->get_violations_dismissed($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'Dismissed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id'] . '/dismissed') . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id']) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Mailed
	public function datatable_json_mailed()
	{
		// Shang
		$records = $this->violationModel->get_violations_mailed($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'Mailed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			/*
			$violation_total = 0;
			if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
				$violation_total = $row['violation_total'];
			} else {
				if ($violation_notice_date)
					$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			}
			*/

			// Warning period - https://a.cl.ly/GGuzgJ17
			$warning_period_text = '';
			if ( $row['stats_warning_period'] == 0 ) $warning_period_text = 'Not mailed yet';
			else if ( $row['stats_warning_period'] == 1 ) $warning_period_text = 'First violation';
			else if ( $row['stats_warning_period'] == 2 ) $warning_period_text = 'Within warning period';
			else if ( $row['stats_warning_period'] == 3 ) $warning_period_text = 'Outside warning period';
			else $warning_period_text = 'Violation before first notice';

			/*
			$warning_period_text = 'Not mailed yet';
			$period = $this->violationModel->get_warning_period( $row['violation_id'] );
			if ( $period['date_diff'] > 0 && $period['date_diff'] <= 14 ) {
				$warning_period_text = 'Within warning period';
				$this->violationModel->edit_violation( array('stats_warning_period' => 1), $row['violation_id'] );
			}
			else if ( $period['date_diff'] > 14 ) {
				$warning_period_text = 'Outside warning period';
				$this->violationModel->edit_violation( array('stats_warning_period' => 0), $row['violation_id'] );
			}
			else {
				$stats_first_notice_date  = date('M d Y', strtotime($period['stats_first_notice_date']));
				$violation_notice_date    = date('M d Y', strtotime($period['violation_notice_date']));
				$violation_date = date('M d Y', strtotime($period['violation_date']));

				if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date == $violation_notice_date )
					$warning_period_text = 'First violation';
				else if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date != $violation_notice_date && $stats_first_notice_date > $violation_date )
					$warning_period_text = 'Violation before first notice';
				else
					$warning_period_text = 'Not mailed yet';
				$this->violationModel->edit_violation( array('stats_warning_period' => 0), $row['violation_id'] );
			}
			*/

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				$row['stats_first_notice_date'],
				$warning_period_text,	// https://a.cl.ly/GGuzgJ17
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/mailed') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				',
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Disputed
	public function datatable_json_disputed()
	{
		// Shang
		$records = $this->violationModel->get_violations_disputed($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {
			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/disputed') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				',
				
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Archived
	public function datatable_json_archived()
	{
		// Shang
		$records = $this->violationModel->get_violations_archived($_GET['columns'][$_GET['order'][0]['column']]['name'], $_GET['order'][0]['dir']);

		$data = array();
		$i = 0;

		// Shang - For Previous and Next button
		$user_id = $this->session->get('admin_id');
		$this->violationModel->delete_violation_temp_id_by_userid($user_id);

		foreach ($records['data']  as $row) {

			if ($row['violation_date'] == '0000-00-00 00:00:00' || $row['violation_date'] == null || date('Y', strtotime($row['violation_date']))  == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($row['violation_date']));
			}
			if ($row['payment_due_date'] == '0000-00-00 00:00:00' || $row['payment_due_date'] == null || date('Y', strtotime($row['payment_due_date']))  == 1969) {
				$payment_due_date = '';
			} else {
				$payment_due_date = date('M d Y', strtotime($row['payment_due_date']));
			}
			if ($row['violation_notice_date'] == '0000-00-00 00:00:00' || $row['violation_notice_date'] == null || date('Y', strtotime($row['violation_notice_date']))  == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($row['violation_notice_date']));
			}

			$status_txt = 'Archived';

			$payment_status = 'Unpaid';
			if ($row['payment_status'] == 1) $payment_status = 'Paid';

			// Shang
			// $violation_total = 0;
			// if (!empty($sort_field) && $sort_field == 'violation_total' && isset($row['violation_total'])) {
			// 	$violation_total = $row['violation_total'];
			// } else {
			// 	if ($violation_notice_date)
			// 		$violation_total = $this->violationModel->get_violations_count($row['plate_number'], $row['violation_notice_date']);
			// }

			$data[] = array(
				'<input type="checkbox" class="violation_check" name="violation_ids[]" value="' . $row['violation_id'] . '"/>',
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> ' . $row['violation_id'] . '</a>',
				$row['violation_number'] . '-' . $row['violation_pin'],
				$row['plate_number'],
				'<img src="' . $row['violation_photo_url'] . '" style="width: 84px;" />',
				$violation_date . ' ' . $row['violation_date'],
				$violation_notice_date,
				$payment_due_date,
				$row['payment_fine_amount'],
				$status_txt,
				$payment_status,
				$row['dmv_contact_zipcode'],
				$row['stats_total_violations'],       // Shang
				$row['dmv_status'],
				'<a title="View" class="view btn btn-sm btn-info" href="/violations/index.php?vio_num='.$row['violation_number'] . '-' . $row['violation_pin'].'"> <i class="material-icons">language</i></a>
				<a title="Edit" class="update btn btn-sm btn-primary" href="' . base_url('admin/violations/edit/' . $row['violation_id']) . '"> <i class="material-icons">edit</i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger" data-href="' . base_url('admin/violations/del/' . $row['violation_id'] . '/archived') . '" data-toggle="modal" data-target="#confirm-delete"> <i class="material-icons">delete</i></a>
				<a href="/ticket/print_ticketpdf.php?violation_id=' . $row['violation_id'] . '&pdf_template=3&nocache=' . str_pad(rand(0, pow(10, 10) - 1), 10, '0', STR_PAD_LEFT) . '" class="btn btn-primary btn-sm pdf_link">PDF</a>
				',
			
			);

			// Shang - For Previous and Next button
			$v_data = array(
				'v_id' => $row['violation_id'],
				'user_id' => $user_id
			);
			$this->violationModel->add_violation_temp_id($v_data);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Other violations
	public function datatable_json_other_violations($id, $plate)
	{
		// Shang
		$records = $this->violationModel->get_violations_other($id, $plate);

		$data = array();
		$i = 0;

		foreach ($records['data']  as $record) {
			if ($record['violation_date'] == '0000-00-00 00:00:00' || $record['violation_date'] == null || date('Y', strtotime($record['violation_date'])) == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($record['violation_date']));
			}
			if ($record['violation_notice_date'] == '0000-00-00 00:00:00' || $record['violation_notice_date'] == null || date('Y', strtotime($record['violation_notice_date'])) == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($record['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($record['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($record['violation_status'] == 3) $status_txt = 'Mailed';
			if ($record['violation_status'] == 4) $status_txt = 'Archived';
			if ($record['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($record['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($record['payment_status'] == 1) $payment_status = 'Paid';

			// Warning period
			/*
			$warning_period_text = 'Not mailed yet';
			$period = $this->violationModel->get_warning_period( $record['violation_id'] );
			
			if ( $period['date_diff'] > 0 && $period['date_diff'] <= 14 ) {
				// echo 'a';
				$warning_period_text = 'Within warning period';
				$this->violationModel->edit_violation( array('stats_warning_period' => 1), $record['violation_id'] );
			}
			else if ( $period['date_diff'] > 14 ) {
				// echo 'b';
				$warning_period_text = 'Outside warning period';
				$this->violationModel->edit_violation( array('stats_warning_period' => 0), $record['violation_id'] );
			}
			else {
				// echo 'c';
				// if ( status = 3,5,6 ) First violation
				// else Not mailed yet.
				// if first notice date is greater than violation date then should say "Violation before first notice date"

				$stats_first_notice_date  = date('M d Y', strtotime($period['stats_first_notice_date']));
				$violation_notice_date    = date('M d Y', strtotime($period['violation_notice_date']));
				$violation_date = date('M d Y', strtotime($period['violation_date']));

				if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date == $violation_notice_date )
					$warning_period_text = 'First violation';
				else if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date != $violation_notice_date && $stats_first_notice_date > $violation_date )
					$warning_period_text = 'Violation before first notice';
				else
					$warning_period_text = 'Not mailed yet';
				$this->violationModel->edit_violation( array('stats_warning_period' => 0), $record['violation_id'] );
			}
			*/
			// die;

			// Warning period - https://a.cl.ly/GGuzgJ17
			$warning_period_text = '';
			if ( $record['stats_warning_period'] == 0 ) $warning_period_text = 'Not mailed yet';
			else if ( $record['stats_warning_period'] == 1 ) $warning_period_text = 'First violation';
			else if ( $record['stats_warning_period'] == 2 ) $warning_period_text = 'Within warning period';
			else if ( $record['stats_warning_period'] == 3 ) $warning_period_text = 'Outside warning period';
			else $warning_period_text = 'Violation before first notice';

			if ($record['violation_type'] == 1) $record['violation_type'] = 'Violation';
			else if ($record['violation_type'] == 2) $record['violation_type'] = 'Warning';

			$data[] = array(
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $record['violation_id']) . '"> ' . $record['violation_id'] . '</a>',
				'<img src="' . $record['violation_photo_url'] . '" style="width: 84px;" />',
				'<video style="width: 184px; height: 100px;" controls>
					<source src="' . $record['violation_video_url'] . '" type="video/mp4">
				</video>',
				$record['violation_number'] . '-' . $record['violation_pin'],
				$record['plate_number'],
				$violation_date . ' / ' . $record['violation_date'],
				$violation_notice_date,
				$status_txt . ' - ' . $payment_status,
				$record['scene_location'],
				$record['dmv_contact_address'],
				$record['dmv_contact_city'],
				$record['dmv_contact_state'],
				$record['dmv_contact_zipcode'],
				$record['violation_notice_date'],
				$warning_period_text,
				$record['dmv_status'],
				$record['violation_type'],
				'<a title="Archive" class="archive btn btn-sm btn-primary" data-href="' . base_url('admin/violations/archive/' . $record['violation_id'] . '/' . $id) . '" data-toggle="modal" data-target="#confirm-archive"><i class="material-icons">update</i></a>'
			);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	// Other violations
	public function datatable_json_duplicates_by_violation($id)
	{
		// Shang
		$records = $this->violationModel->get_duplicates_by_violation($id);

		$data = array();
		$i = 0;

		foreach ($records['data']  as $record) {
			if ($record['violation_date'] == '0000-00-00 00:00:00' || $record['violation_date'] == null || date('Y', strtotime($record['violation_date'])) == 1969) {
				$violation_date = '';
			} else {
				$violation_date = date('M d Y', strtotime($record['violation_date']));
			}
			if ($record['violation_notice_date'] == '0000-00-00 00:00:00' || $record['violation_notice_date'] == null || date('Y', strtotime($record['violation_notice_date'])) == 1969) {
				$violation_notice_date = '';
			} else {
				$violation_notice_date = date('M d Y', strtotime($record['violation_notice_date']));
			}

			$status_txt = 'New';
			if ($record['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($record['violation_status'] == 3) $status_txt = 'Mailed';
			if ($record['violation_status'] == 4) $status_txt = 'Archived';
			if ($record['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($record['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($record['payment_status'] == 1) $payment_status = 'Paid';

			// Warning period
			/*
			$warning_period_text = 'Not mailed yet';
			$period = $this->violationModel->get_warning_period( $record['violation_id'] );

			if ( $period['date_diff'] > 0 && $period['date_diff'] <= 14 ) {
				// echo 'a';
				$warning_period_text = 'Within warning period';
				$this->violationModel->edit_violation( array('stats_warning_period' => 1), $record['violation_id'] );
			}
			else if ( $period['date_diff'] > 14 ) {
				// echo 'b';
				$warning_period_text = 'Outside warning period';
				$this->violationModel->edit_violation( array('stats_warning_period' => 0), $record['violation_id'] );
			}
			else {
				// echo 'c';
				// if ( status = 3,5,6 ) First violation
				// else Not mailed yet.
				// if first notice date is greater than violation date then should say "Violation before first notice date"

				$stats_first_notice_date  = date('M d Y', strtotime($period['stats_first_notice_date']));
				$violation_notice_date    = date('M d Y', strtotime($period['violation_notice_date']));
				$violation_date = date('M d Y', strtotime($period['violation_date']));

				if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date == $violation_notice_date )
					$warning_period_text = 'First violation';
				else if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date != $violation_notice_date && $stats_first_notice_date > $violation_date )
					$warning_period_text = 'Violation before first notice';
				else
					$warning_period_text = 'Not mailed yet';
				$this->violationModel->edit_violation( array('stats_warning_period' => 0), $record['violation_id'] );
			}
			*/
			// die;

			// Warning period - https://a.cl.ly/GGuzgJ17
			$warning_period_text = '';
			if ( $record['stats_warning_period'] == 0 ) $warning_period_text = 'Not mailed yet';
			else if ( $record['stats_warning_period'] == 1 ) $warning_period_text = 'First violation';
			else if ( $record['stats_warning_period'] == 2 ) $warning_period_text = 'Within warning period';
			else if ( $record['stats_warning_period'] == 3 ) $warning_period_text = 'Outside warning period';
			else $warning_period_text = 'Violation before first notice';

			if ($record['violation_type'] == 1) $record['violation_type'] = 'Violation';
			else if ($record['violation_type'] == 2) $record['violation_type'] = 'Warning';


			$data[] = array(
				'<a title="Edit" href="' . base_url('admin/violations/edit/' . $record['violation_id']) . '"> ' . $record['violation_id'] . '</a>',
				'<img src="' . $record['violation_photo_url'] . '" style="width: 84px;" />',
				'<video style="width: 184px; height: 100px;" controls>
					<source src="' . $record['violation_video_url'] . '" type="video/mp4">
				</video>',
				$record['violation_number'] . '-' . $record['violation_pin'],
				$record['plate_number'],
				$violation_date . ' / ' . $record['violation_date'],
				$violation_notice_date,
				$status_txt . ' - ' . $payment_status,
				$record['scene_location'],
				$record['dmv_contact_address'],
				$record['dmv_contact_city'],
				$record['dmv_contact_state'],
				$record['dmv_contact_zipcode'],
				$record['stats_first_notice_date'],
				$warning_period_text,
				$record['dmv_status'],
				$record['violation_type'],
				'<a title="Archive" class="archive btn btn-sm btn-primary" data-href="' . base_url('admin/violations/archive/' . $record['violation_id'] . '/' . $id) . '" data-toggle="modal" data-target="#confirm-archive"><i class="material-icons">update</i></a>'
			);

			$i++;
		}

		$records['data'] = $data;
		echo json_encode($records);
	}

	public function add()
	{
		if ($this->request->getPost('submit')) {
			$validation->setRule('violation_notice_date', 'violation_notice_date', 'trim|required');
			$validation->setRule('municipality_id', 'municipality_id', 'trim');
			$validation->setRule('camera', 'camera', 'trim|required');
			$validation->setRule('plate_number', 'plate_number', 'trim|required');
			$validation->setRule('state', 'state', 'trim|required');
			$validation->setRule('type', 'type', 'trim');
			$validation->setRule('violation_date', 'violation_date', 'trim|required');
			$validation->setRule('violation_date', 'violation_date', 'trim|required');
			$validation->setRule('payment_due_date', 'payment_due_date', 'trim|required');
			$validation->setRule('violation_photo_url', 'violation_photo_url', 'trim');
			$validation->setRule('violation_video_url', 'violation_video_url', 'trim');

			if ($this->form_validation->run() == FALSE) {
				$data['view'] = 'admin/violations/violation_add';
				return view('layout', $data);
			} else {
				$data = array(
					'violation_notice_date' => $this->request->getPost('violation_notice_date'),
					'municipality_id' => $this->request->getPost('municipality_id'),
					'camera' => $this->request->getPost('camera'),
					'plate_number' => $this->request->getPost('plate_number'),
					'state' => $this->request->getPost('state'),
					'type' => $this->request->getPost('type'),
				
					'payment_fine_amount' => $this->request->getPost('payment_fine_amount'),
					'violation_date' =>  $this->request->getPost('violation_date'),
					'violation_date' => $this->request->getPost('violation_date'),
					'violation_status' => 1,
					'payment_due_date' => $this->request->getPost('payment_due_date'),
					'violation_photo_url' => $this->request->getPost('violation_photo_url'),
					'violation_video_url' => $this->request->getPost('violation_video_url')
				);

				$pin = mt_rand(101, 998);
				$added_by = $this->session->get('admin_id');
				$v_date = strtotime(str_replace('-', ' ', $this->request->getPost('violation_date')));
				$n_month = date("m", $v_date);
				$n_day = date("d", $v_date);
				$n_year = date("y", $v_date);
				$data['violation_pin'] = $pin;
				$data['added_by'] = $added_by;
				$data['date_modified_new'] = date('Y-m-d H:i:s');
				$data = $this->security->xss_clean($data);

				$result = $this->violationModel->add_violation($data);
				if ($result) {
					$violation_number = $n_year . $n_month . $n_day . $result;
					$data['violation_number'] = $violation_number;
					$result = $this->violationModel->edit_violation($data, $result);

					$this->activity_model->add(1);
					$this->session->setFlashdata('msg', 'Violation has been added successfully!');

					return redirect()->to(base_url('admin/violations'));
				}
			}
		} else {
			$data['violation']['simple_villages'] = $this->villageModel->get_all_active_villages();
			$data['violation']['simple_cameras'] = $this->cameraModel->get_all_simple_cameras();
			$data['view'] = 'admin/violations/violation_add';
			return view('layout', $data);
		}
	}

	public function edit($id = 0)
	{
		
		// var_dump($id);
		// exit;

// leave PropertyName blank to get all properties.

		if ($this->request->is('post')) {
			$validation = \Config\Services::validation();

			$validation->setRule('violation_notice_date', 'violation_notice_date', 'trim|required');
			$validation->setRule('violation_type', 'violation_type', 'trim|required');
			$validation->setRule('municipality_id', 'municipality_id', 'trim');
			$validation->setRule('camera_id', 'camera_id', 'trim|required');
			$validation->setRule('plate_number', 'plate_number', 'trim|required');
			$validation->setRule('violation_date', 'violation_date', 'trim|required');
			$validation->setRule('payment_due_date', 'payment_due_date', 'trim|required');

			if ($validation->withRequest($this->request)->run() == FALSE) {
				$data['view'] = 'admin/violations/violation_edit_new';  // violation_edit
				$violation = $this->violationModel->get_violation_by_id($id);
				$data['violation'] = array_merge($violation, $_POST);
				$data['violation']['simple_villages'] = $this->villageModel->get_all_active_villages();
				$data['violation']['simple_cameras'] = $this->cameraModel->get_all_simple_cameras();
				
				$user_details = $this->userModel->get_user_by_id($data['violation']['created_by_id']);
				$data['violation']['added_by_details'] ='';
				if(!empty($user_details))
				$data['violation']['added_by_details'] = $user_details['firstname'] . ' ' . $user_details['lastname'];
				return view('layout', $data);
			} else {
				if ($this->request->getPost('violation_date') == '0000-00-00 00:00:00' || $this->request->getPost('violation_date') == null || date('Y', strtotime($this->request->getPost('violation_date')))  == 1969) {
					$violation_date = '0000-00-00 00:00:00';
				} else {
					$violation_date = date('Y-m-d H:i:s', strtotime($this->request->getPost('violation_date')));
				}
				if ($this->request->getPost('payment_due_date') == '0000-00-00 00:00:00' || $this->request->getPost('payment_due_date') == null || date('Y', strtotime($this->request->getPost('payment_due_date')))  == 1969) {
					$payment_due_date = '0000-00-00 00:00:00';
				} else {
					$payment_due_date = date('Y-m-d H:i:s', strtotime($this->request->getPost('payment_due_date')));
				}
				if ($this->request->getPost('violation_notice_date') == '0000-00-00 00:00:00' || $this->request->getPost('violation_notice_date') == null || date('Y', strtotime($this->request->getPost('violation_notice_date')))  == 1969) {
					$violation_notice_date = '0000-00-00 00:00:00';
				} else {
					$violation_notice_date = date('Y-m-d H:i:s', strtotime($this->request->getPost('violation_notice_date')));
				}
				if ($this->request->getPost('payment_date') == '0000-00-00 00:00:00' || $this->request->getPost('payment_date') == null || date('Y', strtotime($this->request->getPost('payment_date')))  == 1969) {
					$payment_date = '0000-00-00 00:00:00';
				} else {
					$payment_date = date('Y-m-d H:i:s', strtotime($this->request->getPost('payment_date')));
				}
				if ($this->request->getPost('violation_added_date') == '0000-00-00 00:00:00' || $this->request->getPost('violation_added_date') == null || date('Y', strtotime($this->request->getPost('violation_added_date')))  == 1969) {
					$violation_added_date = '0000-00-00 00:00:00';
				} else {
					$violation_added_date = date('Y-m-d H:i:s', strtotime($this->request->getPost('violation_added_date')));
				}

				$dismiss_reason = '';
				if ( $this->request->getPost('dismiss_reason') != null )
					$dismiss_reason = $this->request->getPost('dismiss_reason');

				$data = array(
					'violation_notice_date' => $violation_notice_date,
					'dismiss_reason' => $dismiss_reason,
					'violation_date' => $violation_date,
					'payment_due_date' => $payment_due_date,
					'payment_date' => $payment_date,
					'violation_type' => $this->request->getPost('violation_type'),
					'municipality_id' => $this->request->getPost('municipality_id'),
					'camera_id' => $this->request->getPost('camera_id'),
					'plate_number' => $this->request->getPost('plate_number'),
					'violation_photo_url' => $this->request->getPost('violation_photo_url'),
					'violation_video_url' => $this->request->getPost('violation_video_url'),
					'violation_added_date' => $violation_added_date,
					'violation_last_modified_date' => date('Y-m-d H:i:s'),
					'payment_fine_amount' => $this->request->getPost('payment_fine_amount'),
					'payment_amount' => $this->request->getPost('payment_amount'),
					'violation_status' => $this->request->getPost('violation_status'),
					'payment_method' => $this->request->getPost('payment_method'),
					'payment_status' => $this->request->getPost('payment_status'),
					'violation_stop_classification' => $this->request->getPost('stop_classification'),
				);

				// Shang
				if ($this->request->getPost('violation_status') == 5) {
					$data['dismissed_date'] = date('Y-m-d H:i:s');
				}

				$data['date_modified_new'] = date('Y-m-d H:i:s');

			//	$data = $this->security->xss_clean($data);
				$result = $this->violationModel->edit_violation($data, $id);
				
				if ($result) {
					$this->violationModel->recalculate_totals( $this->request->getPost('plate_number') );		// recalculate total count
					$this->session->setFlashdata('msg', 'Violation has been updated successfully!');
					return	redirect()->to(base_url('admin/violations/edit/' . $id));
				}
			}
		} else {
			$violation = $this->violationModel->get_violation_by_id($id);
			$plate_details = $this->plateModel->get_plates_by_plate($violation['plate_number']);

		
			// Shang
			$data['total'] = isset($plate_details['stats_total_violations']) ? $plate_details['stats_total_violations'] : 0;
			$data['sixty_days'] = (isset($plate_details['violations_60days']) && $plate_details['violations_60days'] == 1) ? $plate_details['violations_60days'] : 0;

			$up_data = array();
		//	if ((empty($violation['dmv_contact_state']) && !empty($plate_details['dmv_contact_state'])) || ($plate_details['dmv_contact_state'] != $violation['stdmv_contact_stateate'])) $up_data['state'] = addslashes($plate_details['state']);
		//	if ((empty($violation['type']) && !empty($plate_details['plate_type'])) || ($plate_details['plate_type'] != $violation['type'])) $up_data['type'] = $plate_details['plate_type'];
		
			if (count($up_data) > 0) {
				//$up_data = $this->security->xss_clean($up_data);
				$this->violationModel->edit_violation($up_data, $id);
				$violation = $this->violationModel->get_violation_by_id($id);
			
			}
			$data['violation'] = $violation;
			$data['next_violation'] = $this->violationModel->get_adjacent_violations_details($id, true);
			$data['prev_violation'] = $this->violationModel->get_adjacent_violations_details($id, false);
			$data['plate_details'] = $plate_details;

			// $data['mmc_details'] = $this->mmcModel->get_mmcs_by_mmc($data['violation']['plate_number']);		// Shang
			$filename = basename($data['violation']['violation_photo_url']);
			if ( count($this->mmcModel->get_mmcs_by_image_file_name($filename)) )
				$data['mmc_details'] = $this->mmcModel->get_mmcs_by_image_file_name($filename)[0];		// Shang
			else $data['mmc_details'] = [];

			// Get similar MMC plates
			if(isset($data['mmc_details']['vehicle_type'])){
			$data['similar_mmc_plates'] = $this->mmcModel->get_mmcs_by_keys($data['mmc_details']['vehicle_type'], $data['mmc_details']['make'], $data['mmc_details']['model']);

			// Get similar DMV plates
			$data['similar_dmv_plates'] = '';
			if (is_array($data['similar_mmc_plates']) && count($data['similar_mmc_plates'])) {
				$sp = '';
				foreach ($data['similar_mmc_plates'] as $plate)
					$sp .= 'plate="' . $plate['plate_number'] . '" or ';
				if (strlen($sp)) $sp = substr($sp, 0, -3) . ' order by plate';
				$data['similar_dmv_plates'] = $this->plateModel->get_similar_plates($sp);
			}
		}
			// Warning period
			/*
			$warning_period_text = 'Not mailed yet';
			$period = $this->violationModel->get_warning_period( $id );
			if ( $period['date_diff'] > 0 && $period['date_diff'] <= 14 ) {
				$warning_period_text = 'Within warning period';
				// $this->violationModel->edit_violation( array('stats_warning_period' => 1), $id );
			}
			else if ( $period['date_diff'] > 14 ) {
				$warning_period_text = 'Outside warning period';
				// $this->violationModel->edit_violation( array('stats_warning_period' => 0), $id );
			}
			else {
				$stats_first_notice_date  = date('M d Y', strtotime($period['stats_first_notice_date']));
				$violation_notice_date    = date('M d Y', strtotime($period['violation_notice_date']));
				$violation_date = date('M d Y', strtotime($period['violation_date']));

				if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date == $violation_notice_date )
					$warning_period_text = 'First violation';
				else if ( ($period['violation_status'] == 3 || $period['violation_status'] == 5 || $period['violation_status'] == 6) && $stats_first_notice_date != $violation_notice_date && $stats_first_notice_date > $violation_date )
					$warning_period_text = 'Violation before first notice';
				else
					$warning_period_text = 'Not mailed yet';
				// $this->violationModel->edit_violation( array('stats_warning_period' => 0), $id );
			}
			*/

			// Warning period - https://a.cl.ly/GGuzgJ17
			$warning_period_text = '';
			if ( $violation['stats_warning_period'] == 0 ) $warning_period_text = 'Not mailed yet';
			else if ( $violation['stats_warning_period'] == 1 ) $warning_period_text = 'First violation';
			else if ( $violation['stats_warning_period'] == 2 ) $warning_period_text = 'Within warning period';
			else if ( $violation['stats_warning_period'] == 3 ) $warning_period_text = 'Outside warning period';
			else $warning_period_text = 'Violation before first notice';
			$data['warning_period'] = $warning_period_text;

			$data['violation']['simple_villages'] = $this->villageModel->get_all_active_villages();
			$data['violation']['simple_cameras'] = $this->cameraModel->get_all_simple_cameras();

		
		//	created_by_id
			
			$user_details = $this->userModel->get_user_by_id($data['violation']['created_by_id']);
			$data['violation']['added_by_details'] ='';
			if(!empty($user_details)){
				$data['violation']['added_by_details'] = $user_details['firstname'] . ' ' . $user_details['lastname'];
			}
			$data['view'] = 'admin/violations/violation_edit_new';  // violation_edit

			return view('layout', $data);
		}
	}

	public function del($id = array(), $type = 'all') // Shang added $type
	{
		$this->villageModel->delete($id);

		// Add User Activity
	//	$this->activity_model->add(3);

		$this->session->setFlashdata('msg', 'Violation has been deleted successfully!');
	return	redirect()->to(base_url('admin/violations/' . $type));
	}

	public function delete() {
		$violation_ids = $this->request->getPost('violation_ids');

		if (count($violation_ids)) {
			foreach ($violation_ids as $violation_id) {
				$this->db->delete('cms_violation', array('id' => $violation_id));
			}
			// Add User Activity
			$this->activity_model->add(3);

			$this->session->setFlashdata('msg', 'Violation(s) has been deleted successfully!');

		}
		else $this->session->setFlashdata('error', 'Oops something went wrong');

		redirect()->to(base_url($this->request->getPost('current_uri')));
	}

	public function archive($archive_id = 0, $id = 0)
	{
		if ( $archive_id > 0 && $id > 0 ) {
			$data = array(
				'violation_status' => 4
			);
			$this->db->where('id', $archive_id);
			$this->db->update('cms_violation', $data);
	
			// Add User Activity
			$this->activity_model->add(3);
	
			$this->session->setFlashdata('msg', 'Archived successfully!');
			redirect()->to(base_url('admin/violations/edit/' . $id));
		}
	}

	public function print_ticket($id = 0)
	{
		$data['violation'] = $this->violationModel->get_violation_by_id($id);
		return view('admin/violations/print_ticket', $data);
	}

//	public function importcsv($type = 'all')   // Shang added $type parameter
//	{
//		$handle = fopen($_FILES["file"]["tmp_name"], 'r');
//
//		if ($handle) {
//			$violation_head = fgetcsv($handle, 1000, ",");
//			while (($v = fgetcsv($handle, 1000, ",")) !== FALSE) {
//				// Shang
//				$data = array();
//
//				$data['violation_number'] = '';
//				if (is_numeric(array_search("violation_number", $violation_head)) &&  array_search("violation_number", $violation_head) >= 0 && !empty($v[array_search("violation_number", $violation_head)]))
//					$data['violation_number'] = $v[array_search("violation_number", $violation_head)];
//
//				$data['violation_added_date'] = date('Y-m-d H:i:s');
//				if (is_numeric(array_search("violation_added_date", $violation_head)) && array_search("violation_added_date", $violation_head) >= 0 && !empty($v[array_search("violation_added_date", $violation_head)]) && $v[array_search("violation_added_date", $violation_head)] != NULL && $v[array_search("violation_added_date", $violation_head)] != '0000-00-00 00:00:00')
//					$data['violation_added_date'] = date('Y-m-d H:i:s', strtotime($v[array_search("violation_added_date", $violation_head)]));
//
//				$data['violation_notice_date'] = NULL;
//				if (is_numeric(array_search("violation_notice_date", $violation_head)) && array_search("violation_notice_date", $violation_head) >= 0 && !empty($v[array_search("violation_notice_date", $violation_head)]) && $v[array_search("violation_notice_date", $violation_head)] != NULL && $v[array_search("violation_notice_date", $violation_head)] != '0000-00-00 00:00:00')
//					$data['violation_notice_date'] = date('Y-m-d H:i:s', strtotime($v[array_search("violation_notice_date", $violation_head)]));
//
//				$data['violation_date'] = NULL;
//				if (is_numeric(array_search("violation_date", $violation_head)) && array_search("violation_number", $violation_head) >= 0 && !empty($v[array_search("violation_date", $violation_head)]) && $v[array_search("violation_date", $violation_head)] != NULL && $v[array_search("violation_date", $violation_head)] != '0000-00-00 00:00:00')
//					$data['violation_date'] = date('Y-m-d H:i:s', strtotime($v[array_search("violation_date", $violation_head)]));
//
//				$data['payment_due_date'] = NULL;
//				if (is_numeric(array_search("payment_due_date", $violation_head)) && array_search("payment_due_date", $violation_head) >= 0 && !empty($v[array_search("payment_due_date", $violation_head)]) && $v[array_search("payment_due_date", $violation_head)] != NULL && $v[array_search("payment_due_date", $violation_head)] != '0000-00-00 00:00:00')
//					$data['payment_due_date'] = date('Y-m-d H:i:s', strtotime($v[array_search("payment_due_date", $violation_head)]));
//
//				$data['violation_date'] = '';
//				if (is_numeric(array_search("violation_date", $violation_head)) && array_search("violation_date", $violation_head) >= 0)
//					$data['violation_date'] = $v[array_search("violation_date", $violation_head)];
//
//				$data['plate_number'] = '';
//				if (is_numeric(array_search("plate", $violation_head)) && array_search("plate", $violation_head) >= 0 && !empty($v[array_search("plate", $violation_head)]))
//					$data['plate_number'] = $v[array_search("plate", $violation_head)];
//
//				$data['municipality_id'] = 0;
//				if (is_numeric(array_search("municipality_id", $violation_head)) && array_search("municipality_id", $violation_head) >= 0 && $v[array_search("municipality_id", $violation_head)]) {
//					$village_details = $this->villageModel->get_village_by_name($v[array_search("municipality_id", $violation_head)]);
//					if (isset($village_details['violation_id']) && $village_details['violation_id'] > 0)
//						$data['municipality_id'] = $village_details['violation_id'];
//				}
//
//				$data['camera'] = 0;
//				if (is_numeric(array_search("scene_location", $violation_head)) && array_search("scene_location", $violation_head) >= 0 && $v[array_search("scene_location", $violation_head)]) {
//					$camera_details = $this->cameraModel->get_camera_by_name($v[array_search("scene_location", $violation_head)]);
//					if (isset($camera_details['violation_id']) && $camera_details['violation_id'] > 0)
//						$data['camera'] = $camera_details['violation_id'];
//				}
//
//				$data['payment_fine_amount'] = 0.00;
//				if (is_numeric(array_search("payment_fine_amount", $violation_head)) && array_search("payment_fine_amount", $violation_head) >= 0 && $v[array_search("payment_fine_amount", $violation_head)])
//					$data['payment_fine_amount'] = $v[array_search("payment_fine_amount", $violation_head)];
//
//				$data['violation_status'] = 1;
//				if (is_numeric(array_search("status", $violation_head)) && array_search("status", $violation_head) >= 0 && $v[array_search("status", $violation_head)]) {
//					if ($v[array_search("status", $violation_head)] == 'Reviewed') 	   $data['violation_status'] = 2;
//					else if ($v[array_search("status", $violation_head)] == 'Mailed')    $data['violation_status'] = 3;
//					else if ($v[array_search("status", $violation_head)] == 'Archived')  $data['violation_status'] = 4;
//					else if ($v[array_search("status", $violation_head)] == 'Dismissed') $data['violation_status'] = 5;
//					else if ($v[array_search("status", $violation_head)] == 'Disputed')  $data['violation_status'] = 6;
//				}
//
//				$data['payment_status'] = 0;
//				if (is_numeric(array_search("payment_status", $violation_head)) && array_search("payment_status", $violation_head) >= 0 && $v[array_search("payment_status", $violation_head)]) {
//					if ($v[array_search("payment_status", $violation_head)] == 'Unpaid')    $data['payment_status'] = 0;
//					else if ($v[array_search("payment_status", $violation_head)] == 'Paid') $data['payment_status'] = 1;
//				}
//
//				$data['violation_photo_url'] = '';
//				if (is_numeric(array_search("violation_photo_url", $violation_head)) && array_search("violation_photo_url", $violation_head) >= 0 && $v[array_search("violation_photo_url", $violation_head)])
//					$data['violation_photo_url'] = $v[array_search("violation_photo_url", $violation_head)];
//
//				$data['violation_video_url'] = '';
//				if (is_numeric(array_search("violation_video", $violation_head)) && array_search("violation_video", $violation_head) >= 0 && $v[array_search("violation_video", $violation_head)])
//					$data['violation_video_url'] = $v[array_search("violation_video", $violation_head)];
//
//				$data['date_modified_new'] = date("Y-m-d H:i:s");
//				$data['added_by'] = 43;
//
//				$data = $this->security->xss_clean($data);
//
//				// print_r($data);
//				// die;
//
//				// if (is_numeric(array_search("ID", $violation_head)) && array_search("ID", $violation_head) >= 0 && !empty($v[array_search("ID", $violation_head)])) {
//				$vid = trim($v[array_search("ID", $violation_head)]);
//				if (is_numeric($vid) && $vid >= 0) {
//					// Update
//					$v_details = $this->violationModel->get_violation_by_id($vid);
//					if ($v_details['violation_id'] > 0) {
//						$this->violationModel->edit_violation($data, $v_details['violation_id']);
//					}
//				} else {
//					// Insert
//					// $data = $this->security->xss_clean($data);
//					$insert_id = $this->violationModel->add_violation($data);
//					if ($insert_id) {
//						$insert_data = array();
//
//						$d = date('y-m-d', strtotime($v[array_search("violation_date", $violation_head)]));
//						$d_arr = explode('-', $d);
//						$violation_number = $d_arr[0] . $d_arr[1] . $d_arr[2] . $insert_id;
//
//						$insert_data['violation_number'] = $violation_number;
//						$insert_data['violation_pin'] = mt_rand(101, 998);
//						$insert_data = $this->security->xss_clean($insert_data);
//						$this->violationModel->edit_violation($insert_data, $insert_id);
//					}
//				}
//			}
//
//			if ($handle)
//				fclose($handle);
//
//			$this->violationModel->recalculate_totals();		// recalculate total count
//			$this->session->setFlashdata('msg', 'Violation has been imported successfully!');
//			// redirect()->to(base_url('admin/violations/'.$type));
//		} else {
//			$data['view'] = 'admin/violations/import';
//			$data['type'] = $type;
//			return view('layout', $data);
//		}
//	}

	public function importcsv ()
	{
		return library('upload');
		$result = $this->upload->do_upload('file');
		var_dump($result);
	}

	public function quick_import($type = 'all')   // Shang added $type parameter
	{
		$data['view'] = 'admin/violations/quick_import';
		$data['villages'] = $this->villageModel->get_all_active_villages();
		$data['cameras']  = $this->cameraModel->get_all_simple_cameras();
		$data['type'] = $type;		// Shang
		return view('layout', $data);
	}

	public function import()
	{
		$data['view'] = 'admin/violations/new/violation_import';
		$data['cur_tab'] = 'pre-check';
		$data['sub_tab'] = 'import';
		return view('layout', $data);
	}

	public function import_save()
	{
		$data = $violations = $violations_validated = $violations_skipped = array();

		// Upload file library initialization
		$config['upload_path'] = FCPATH . '/uploads/violation_csv';
		$config['allowed_types'] = 'csv';
		$validationRule = [
			'violation_csv' => [
				'label' => 'Csv File',
				'rules' => [
					'uploaded[violation_csv]',
					//	'ext_in[violation_csv,csv]'
					// 'is_image[userfile]',
					// 'mime_in[userfile,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
					// 'max_size[userfile,100]',
					// 'max_dims[userfile,1024,768]',
				],
			],
		];
		if (!$this->validate($validationRule)) {
			$data = ['errors' => $this->validator->getErrors()];

			// handle the upload failure


			$data['view'] = 'admin/violations/new/violation_import';
			return view('layout', $data);
		}


		if ($file = $this->request->getFile('violation_csv')) {
			if ($file->isValid() && !$file->hasMoved()) {


				// Get random file name
				$newName = $file->getRandomName();


				// Store file in public/csvfile/ folder
				$file->move(WRITEPATH . 'uploads/violation_csv/', $newName);

				// Reading file
				$file = fopen(WRITEPATH . "uploads/violation_csv/" . $newName, "r");
				$i = 0;
				$numberOfFields = 4; // Total number of fields

				$importData_arr = array();

				$header = fgetcsv($file);


				// Initialize $importData_arr Array
				while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
					$violations[] = array_combine($header, $row);;
				}

				fclose($file);

				// delete stored file when done.
				//unlink($upload_data['full_path']);

				

				foreach ($violations as $input) {
					// Data validation for violation fields.
					if (!isset($input['uuid']) ||
					!isset($input['mmc_uuid']) ||
					!isset($input['stop_sign_location']) ||
					!isset($input['violation_date']) ||
					!isset($input['violation_video']) ||
					!isset($input['plate_photo']) ||
					!isset($input['threshold_limit']) ||
					!isset($input['threshold_time']) ||
					!isset($input['plate'])
					) {
						//skip record
						$violations_skipped[] = $input;
						continue; // move to the next iteration
					}

					// Get camera record from stop_sign_location
					$camera = $this->cameraModel->get_camera_by_stop_sign_location($input['stop_sign_location']);
					// Check the correct value of each column
					if (
						!is_numeric($input['threshold_limit']) || // threshold_limit must be a number
						!is_numeric($input['threshold_time']) || // threshold_time must be a number
						!strtotime($input['violation_date']) || // violation_date must be a valid date format
						empty($camera)
					) {
						//skip record
						$violations_skipped[] = $input;
						continue; // move to the next iteration
					}

					$violation = [];
					$violation['violation_date'] = date('Y-m-d H:i:s', str_replace('/','-',strtotime($input['violation_date'])));

					// skip violation if exists
					$violation_exists = $this->violationModel->is_exist_violation_by_params_new($input['plate'], $input['violation_video']);

					// Get village record from camera record.
					$village = $this->villageModel->get_village_by_id($camera['municipality_id']);

					if (
						!empty($violation_exists) ||
						empty($village)
					) {
						$violations_skipped[] = $input;
						continue;
					}

					
					$violation['camera_id'] = $camera['scene_id'];
					$violation['municipality_id'] = $village['municipality_id'];

					$violation['uuid'] = $input['uuid'];
					$violation['mmc_uuid'] = $input['mmc_uuid'];
					$violation['stop_sign_location'] = $input['stop_sign_location'];
					$violation['violation_date'] = $input['violation_date'];
					$violation['violation_video_url'] = $input['violation_video'];
					$violation['violation_photo_url'] = $input['plate_photo'];
					$violation['threshold_limit'] = $input['threshold_limit'];
					$violation['threshold_time'] = $input['threshold_time'];
					$violation['plate_number'] = $input['plate'];
				

					// static fields
					$violation['violation_status'] = 1;
					$violation['stats_sent_status'] = 0;
					$violation['violation_added_date'] = date('Y-m-d h:i:s');
					$violation['violation_notice_date'] = date('Y-m-d', strtotime('+2 days', time()));
					$violation['payment_due_date'] = date('Y-m-d', strtotime('+32 days', time()));;
					$violation['payment_status'] = 0;
					$violation['violation_type'] = 1;
					$violation['created_by_id'] = $this->session->get('admin_id');
					$violation['violation_pin'] = mt_rand(101, 998);
					$violation['violation_stop_classification'] = '';

					
					// other tweaks to meet the old code and logic :'(

					// add violation record to the database
					$violation_id = $this->violationModel->add_violation($violation);

					if (!$violation_id) {
						// if the query fails for somereason, skip the record
						$violations_skipped[] = $violation;
						continue;
					}

					// generate violation_number from now's date + violation ID.
					// example: 221224123 with 22 is the year of 2022, 12 is the month, 24 is the day, 123 is the violation ID.
					else $this->violationModel->edit_violation(['violation_number' => date('ymd') . $violation_id], $violation_id);

					// store the new record in $violations_validated array.
					$violations_validated[] = $violation;
				}

				$data['violation_total_count'] = count($violations);
				$data['violation_skipped_count'] = count($violations_skipped);
				$data['violation_validated_count'] = count($violations_validated);
				$data['file_name'] = $newName;

				// generate skipped violations csv file
				if ($data['violation_skipped_count']) {
					// Create csv file and store it in /uploads/violation_csv folder

					$skipped_violations_csv_file_name = 'violations_skipped_' . date('Y-m-d H-i-s') . '_' . $newName;
					$skipped_violations_csv_file_path = PUBLIC_PATH.'/uploads/violation_csv/' . $skipped_violations_csv_file_name;
					$violations_skipped = array_merge(array($header), $violations_skipped);

				
					// create the csv file
					$skipped_violation_csv_string = $this->array_to_csv($violations_skipped);

					write_file($skipped_violations_csv_file_path, $skipped_violation_csv_string);

					$data['violation_skipped_url'] = base_url('uploads/violation_csv/' . $skipped_violations_csv_file_name);
				}
			}
		}

		$data['view'] = 'admin/violations/new/violation_import';
		return view('layout', $data);
	}

	
		function array_to_csv($array)
		{
			// Open a memory "file" for writing
			$fh = fopen('php://temp', 'w');
	
			// Write the array to the file
			foreach ($array as $row) {
				fputcsv($fh, $row);
			}
	
			// Rewind the file pointer
			rewind($fh);
	
			// Read the contents of the file into a string
			$csv = stream_get_contents($fh);
	
			// Close the file handle
			fclose($fh);
	
			return $csv;
		}
	public function download_sample_csv() {
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="import_violation_sample.csv"');

		readfile('uploads/violation_csv/import_violation_sample.csv');
	}

	public function quick_import_csv($type = 'all') // Shang added $type param
	{
		// Shang
		if ($this->request->getPost('submit')) {
			return helper('url', 'form');
			$validation->setRule('municipality_id', 'municipality_id', 'trim|required');
			$validation->setRule('scene_location', 'scene_location', 'trim|required');
			$validation->setRule('violation_notice_date', 'violation_notice_date', 'trim|required');
			$validation->setRule('payment_due_date', 'payment_due_date', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$data['view'] = 'admin/violations/quick_import';
				$data['type'] = $type;
				$data['villages'] = $this->villageModel->get_all_active_villages();
				$data['cameras']  = $this->cameraModel->get_all_simple_cameras();
				return view('layout', $data);
			} else {
				$handle = fopen($_FILES["file"]["tmp_name"], 'r');
				if ($handle) {
					// fgetcsv($handle, 10000, ",");
					$idx = 0;
					while (! feof($handle)) {
						$data = array();
						$row = fgetcsv($handle);
						
						if ( $idx == 0 ) {
							$idx ++;
							continue;
						}

						if ( trim($row[0]) != 'plate_number' && trim($row[0]) != 'plate_number' ) {
							$data['plate_number'] = $row[0];
							$data['violation_date'] = date('Y-m-d H:i:s', strtotime($row[1]));
							$data['violation_date'] = $row[2];
							$data['violation_video_url'] = $row[3];
							$data['violation_photo_url'] = $row[4];
	
							$data['violation_notice_date'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('violation_notice_date')));
							$data['payment_due_date']	 = date('Y-m-d H:i:s', strtotime($this->request->getPost('payment_due_date')));
							$data['municipality_id'] = $this->request->getPost('municipality_id');
	
							$data['camera_id'] = $this->request->getPost('scene_location');
	
							$vd = $this->villageModel->get_village_by_id($this->request->getPost('municipality_id'));
							
							$data['payment_fine_amount'] = $vd['violation_fine_amount'];
							$data['violation_status'] = 1;
							$data['payment_status'] = 0;
							$data['date_modified_new'] = date("Y-m-d H:i:s");
							$data['added_by'] = 43;
	
							// Insert
							$data['date_paid_new'] = NULL;
							$data['violation_added_date'] = date("Y-m-d H:i:s");
							//$data = $this->security->xss_clean($data);
	
							$insert_id = $this->violationModel->add_violation($data);
							if ($insert_id) {
								$insert_data = array();
	
								// $violation_number = date('y').date('m').date('d') . $insert_id;
								$d = date('y-m-d', strtotime($row[1]));
								$d_arr = explode('-', $d);
								$violation_number = $d_arr[0] . $d_arr[1] . $d_arr[2] . $insert_id; //date('y').date('m').date('d') . $insert_id;
	
								$insert_data['violation_number'] = $violation_number;
								$insert_data['violation_pin'] = mt_rand(101, 998);
							//	$insert_data = $this->security->xss_clean($insert_data);
								$this->violationModel->edit_violation($insert_data, $insert_id);
							}
						}

						$idx ++;
					}

					if ( $handle )
						fclose($handle);

					$this->violationModel->recalculate_totals();		// recalculate total count

					$this->session->setFlashdata('msg', 'Violation has been imported successfully!');
					redirect()->to(base_url('admin/violations/' . $type)); // Shang added $type parameter
					
				} else {
					$data['view'] = 'admin/violations/quick_import';
					$data['type'] = $type;
					return view('layout', $data);
				}
			}
		} else {
			$data['view'] = 'admin/violations/quick_import';
			$data['type'] = $type;
			return view('layout', $data);
		}
	}

	public function exportall()
	{
		return helper('download');
		$fileName = "Violation Report";
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
		$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
		);
		$sheet->setCellValue('A1', 'violation_notice_date');
		$sheet->setCellValue('B1', 'municipality_id');
		$sheet->setCellValue('C1', 'scene_location');
		$sheet->setCellValue('D1', 'plate_number');
		$sheet->setCellValue('E1', 'state');
		$sheet->setCellValue('F1', 'type');
		$sheet->setCellValue('G1', 'violation_date');
		$sheet->setCellValue('H1', 'violation_date');
		$sheet->setCellValue('I1', 'payment_due_date');
		$sheet->setCellValue('J1', 'violation_photo_url');
		$sheet->setCellValue('K1', 'violation_video_url');
		$sheet->setCellValue('L1', 'violation_number');
		$sheet->setCellValue('M1', 'pin');
		$sheet->setCellValue('N1', 'camera_zip');
		$sheet->setCellValue('O1', 'payment_fine_amount');
		$sheet->setCellValue('P1', 'date_paid_new');
		$sheet->setCellValue('Q1', 'amount_paid');
		$sheet->setCellValue('R1', 'payment_method');
		$sheet->setCellValue('S1', 'violation_status');
		$rows = 2;
		//var_dump($excel_format_data);
		//exit;
		$records = $this->violationModel->get_all_simple_violations();
		foreach ($records as $val) {
			if ($val['violation_status'] == 2) $status_txt = 'Reviewed';
			elseif ($val['violation_status'] == 3) $status_txt = 'Mailed';
			elseif ($val['violation_status'] == 4) $status_txt = 'Archived';
			elseif ($val['violation_status'] == 5) $status_txt = 'Dismissed';
			elseif ($val['violation_status'] == 6) $status_txt = 'Disputed';
			else $status_txt = 'New';

			$payment_status = 'Unpaid';
			if ($val['payment_status'] == 1) $payment_status = 'Paid';

			$sheet->setCellValue('A' . $rows, $val['violation_notice_date']);
			$sheet->setCellValue('B' . $rows, $val['municipality_id']);
			$sheet->setCellValue('C' . $rows, $val['scene_location']);
			$sheet->setCellValue('D' . $rows, $val['plate_number']);
			$sheet->setCellValue('E' . $rows, $val['state']);
			$sheet->setCellValue('F' . $rows, $val['type']);
			$sheet->setCellValue('G' . $rows, $val['violation_date']);
			$sheet->setCellValue('H' . $rows, $val['violation_date']);
			$sheet->setCellValue('I' . $rows, $val['payment_due_date']);
			$sheet->setCellValue('J' . $rows, $val['violation_photo_url']);
			$sheet->setCellValue('K' . $rows, $val['violation_video_url']);
			$sheet->setCellValue('L' . $rows, $val['violation_number']);
			$sheet->setCellValue('M' . $rows, $val['violation_pin']);
			$sheet->setCellValue('N' . $rows, $val['camera_zip']);
			$sheet->setCellValue('O' . $rows, $val['payment_fine_amount']);
			$sheet->setCellValue('P' . $rows, $val['date_paid_new']);
			$sheet->setCellValue('Q' . $rows, $val['amount_paid']);
			$sheet->setCellValue('R' . $rows, $val['payment_method']);
			$sheet->setCellValue('S' . $rows, $status_txt);
			$sheet->setCellValue('T' . $rows, $payment_status);
			$rows++;
		}
		$writer = new Xlsx($spreadsheet);
		$writer->save('uploads/excel/' . $fileName . '.xlsx');
		force_download('uploads/excel/' . $fileName . '.xlsx', NULL);
	}

	public function export()
	{
		if (!empty($_REQUEST['violation_id'])) {
			$violation_ids = $_REQUEST['violation_id'];
			return helper('download');
			$fileName = "Violation Report";
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->getStyle('A1')->getFont()->setBold(true)->setName('Arial')->SetSize(10);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				)
			);
			$sheet->setCellValue('A1', 'violation_notice_date');
			$sheet->setCellValue('B1', 'municipality_id');
			$sheet->setCellValue('C1', 'scene_location');
			$sheet->setCellValue('D1', 'plate');
			$sheet->setCellValue('E1', 'state');
			$sheet->setCellValue('F1', 'violation_type');
			$sheet->setCellValue('G1', 'violation_date');
		
			$sheet->setCellValue('M1', 'violation_date');
			$sheet->setCellValue('N1', 'payment_due_date');
			$sheet->setCellValue('O1', 'violation_photo_url');
			$sheet->setCellValue('P1', 'violation_video_url');
			$sheet->setCellValue('Q1', 'violation_number');
			$sheet->setCellValue('R1', 'violation_pin');
			$sheet->setCellValue('S1', 'camera_zip');
			$sheet->setCellValue('T1', 'payment_fine_amount');
			$sheet->setCellValue('U1', 'date_paid_new');
			$sheet->setCellValue('V1', 'amount_paid');
			$sheet->setCellValue('W1', 'payment_method');
			$sheet->setCellValue('X1', 'violation_status');
			$rows = 2;
			//var_dump($excel_format_data);
			//exit;
			$records = $query = $this->db->table('cms_violation')->whereIn('id', $violation_ids)->getResultArray();
			foreach ($records as $val) {
				if ($val['violation_status'] == 2) $status_txt = 'Reviewed';
				elseif ($val['violation_status'] == 3) $status_txt = 'Mailed';
				elseif ($val['violation_status'] == 4) $status_txt = 'Archived';
				elseif ($val['violation_status'] == 5) $status_txt = 'Dismissed';
				elseif ($val['violation_status'] == 6) $status_txt = 'Disputed';
				else $status_txt = 'New';

				$payment_status = 'Unpaid';
				if ($val['payment_status'] == 1) $payment_status = 'Paid';
				$sheet->setCellValue('A' . $rows, $val['violation_notice_date']);
				$sheet->setCellValue('B' . $rows, $val['municipality_id']);
				$sheet->setCellValue('C' . $rows, $val['scene_location']);
				$sheet->setCellValue('D' . $rows, $val['plate_number']);
				$sheet->setCellValue('E' . $rows, $val['state']);
				$sheet->setCellValue('F' . $rows, $val['violation_type']);
				$sheet->setCellValue('G' . $rows, $val['violation_date']);
				
				$sheet->setCellValue('M' . $rows, $val['violation_date']);
				$sheet->setCellValue('N' . $rows, $val['payment_due_date']);
				$sheet->setCellValue('O' . $rows, $val['violation_photo_url']);
				$sheet->setCellValue('P' . $rows, $val['violation_video_url']);
				$sheet->setCellValue('Q' . $rows, $val['violation_number']);
				$sheet->setCellValue('R' . $rows, $val['violation_pin']);
				$sheet->setCellValue('S' . $rows, $val['camera_zip']);
				$sheet->setCellValue('T' . $rows, $val['payment_fine_amount']);
				$sheet->setCellValue('U' . $rows, $val['date_paid_new']);
				$sheet->setCellValue('V' . $rows, $val['amount_paid']);
				$sheet->setCellValue('W' . $rows, $val['payment_method']);
				$sheet->setCellValue('X' . $rows, $status_txt);
				$sheet->setCellValue('z' . $rows, $payment_status);
				$rows++;
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save('uploads/excel/' . $fileName . '.xlsx');
			force_download('uploads/excel/' . $fileName . '.xlsx', NULL);
		}
	}

	public function exportcsv()
	{
		if (!empty($_REQUEST['violation_id'])) {
			$violation_ids = implode(',', $_REQUEST['violation_id']);
			$records = $this->db->query("select a.*, b.scene_location, c.municipality_id as municipality_id from cms_violation a left join ci_cameras b on b.id = a.camera left join cms_municipality c on a.municipality_id = c.id where a.id in ($violation_ids)")->result_array();
			if (count($records) > 0) {
				$delimiter = ",";
				$filename = "Violation Report.csv";

				// Create a file pointer 
				$f = fopen('php://memory', 'w');

				// Set column headers 
				$fields = array('violation_notice_date', 'municipality_id', 'scene_location', 'plate_number', 'state', 'type', 'violation_date', 'full_name', 'address1', 'address2', 'dmv_contact_city', 'zip', 'violation_date', 'payment_due_date', 'violation_photo_url', 'violation_video_url', 'violation_number', 'pin', 'payment_fine_amount', 'date_paid_new', 'amount_paid', 'payment_method', 'violation_status');
				fputcsv($f, $fields, $delimiter);

				// Output each row of the data, format line as csv and write to file pointer 
				foreach ($records as $val) {
					if ($val['violation_status'] == 2) $status_txt = 'Reviewed';
					elseif ($val['violation_status'] == 3) $status_txt = 'Mailed';
					elseif ($val['violation_status'] == 4) $status_txt = 'Archived';
					elseif ($val['violation_status'] == 5) $status_txt = 'Dismissed';
					elseif ($val['violation_status'] == 6) $status_txt = 'Disputed';
					else $status_txt = 'New';
					$scene_location = !empty($val['scene_location']) ? $val['scene_location'] : '';
					$lineData = array($val['violation_notice_date'], $val['municipality_id'], $scene_location, $val['plate_number'], $val['state'], $val['type'], $val['violation_date'], $val['full_name'], $val['address1'], $val['address2'], $val['dmv_contact_city'], $val['zip'], $val['violation_date'], $val['payment_due_date'], $val['violation_photo_url'], $val['violation_video_url'], $val['violation_number'], $val['violation_pin'], $val['payment_fine_amount'], $val['date_paid_new'], $val['amount_paid'], $val['payment_method'], $status_txt);
					fputcsv($f, $lineData, $delimiter);
				}

				// Move back to beginning of file 
				fseek($f, 0);

				// Set headers to download file rather than displayed 
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="' . $filename . '";');

				//output all remaining data on a file pointer 
				fpassthru($f);
			}
		}
	}

	public function exportallcsv($type)
	{
		// Shang
		$results = $this->violationModel->get_csv_data($type);
		if (is_array($results) && count($results)) {
			$delimiter = ",";
			$file_name = "Violation Report(" . $type . ").csv";

			// Create a file pointer 
			$f = fopen('php://memory', 'w');

			// Set column headers 
			$fields = array(
				'ID',
				'violation_number',
				'violation_added_date',
				'violation_notice_date',
				'violation_date',
				'violation_date',
				'payment_due_date',
				'plate_number',
				'municipality_id',
				'scene_location',
				'payment_fine_amount',
				'violation_status',
				'payment_status',
				'violation_photo_url',
				'violation_video_url'
			);
			fputcsv($f, $fields, $delimiter);

			// Output each row of the data, format line as csv and write to file pointer 
			foreach ($results as $result) {

				$id = $result['violation_id'];

				$violation_number = $result['violation_number'];
				if (empty($violation_number)) {
					$m = date("m");
					$d = date("d");
					$y = date("y");
					$violation_number = $y . $m . $d . $id;
				}

				$status = 'New';
				switch ($result['violation_status']) {
					case '2':
						$status = 'Reviewed';
						break;
					case '3':
						$status = 'Mailed';
						break;
					case '4':
						$status = 'Archived';
						break;
					case '5':
						$status = 'Dismissed';
						break;
					case '6':
						$status = 'Dismissed';
						break;
				}

				$payment_status = 'Unpaid';
				if ($result['payment_status'] == 1) $payment_status = 'Paid';

				$payment_fine_amount = $result['payment_fine_amount'];
				if ($payment_fine_amount == 0.00 || empty($payment_fine_amount) || $payment_fine_amount == NULL)
					$payment_fine_amount = $result['violation_fine_amount'];

				$lineData = array(
					$id,
					$violation_number,
					date('M d Y', strtotime($result['violation_added_date'])),
					date('M d Y', strtotime($result['violation_notice_date'])),
					date('M d Y', strtotime($result['violation_date'])),
					$result['violation_date'],
					date('M d Y', strtotime($result['payment_due_date'])),
					$result['plate_number'],
					$result['municipality_id'],
					$result['scene_location'],
					$payment_fine_amount,
					$status,
					$payment_status,
					$result['violation_photo_url'],
					$result['violation_video_url']
				);
				fputcsv($f, $lineData, $delimiter);
			}

			// Move back to beginning of file 
			fseek($f, 0);

			// Set headers to download file rather than displayed 
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $file_name . '";');

			//output all remaining data on a file pointer 
			fpassthru($f);
		}
	}

	// Tooltip
	public function tooltip($plate)
	{
		$result = $this->violationModel->get_violation_by_plate($plate);
		// print_r($result);
		// die;

		$html = "<h1>No result.</h1>";
		if (is_array($result) && count($result)) {
			$violation_date = date('M d Y', strtotime($result['violation_date']));
			$violation_date = $result['violation_date'];
			$date = $violation_date . ', ' . $violation_date;

			$status_txt = 'New';
			if ($result['violation_status'] == 2) $status_txt = 'Reviewed';
			if ($result['violation_status'] == 3) $status_txt = 'Mailed';
			if ($result['violation_status'] == 4) $status_txt = 'Archived';
			if ($result['violation_status'] == 5) $status_txt = 'Dismissed';
			if ($result['violation_status'] == 6) $status_txt = 'Disputed';

			$payment_status = 'Unpaid';
			if ($result['payment_status'] == 1) $payment_status = 'Paid';

			$html = '
				<div style="width: 60%;">
					<img src="' . $result['violation_photo_url'] . '" style="border-radius: 10px 0 0 10px; width: 100%;"/>
				</div>
				<div style="width: 40%; background-color: #f0efed; border-radius: 0 10px 10px 0; padding: 15px;">
					<div class="text-left" style="width: 100%; padding: 10px 5px 5px 10px;">
						<b>Status:</b> ' . $status_txt . ' - ' . $payment_status . '
					</div>
					<div class="text-left" style="width: 100%; padding: 0 5px 10px 10px;">
						<b>Violation date:</b> ' . $date . '
					</div>
					<div class="text-left" style="width: 100%; padding: 0 5px 10px 10px;">
						<b>Total definition:</b>
						<div style="padding-left: 10px;">
							<p style="margin: 3px !important;">1. Get today date</p>
							<p style="margin: 3px !important;">2. Get notice date</p>
							<p style="margin: 3px !important;">3. Calculate diff between today and notice date</p>
							<p style="margin: 3px !important;">4. Filter records less than 60 and status is not archived</p>
						</div>
					</div>
				</div>
			';
		}
		echo $html;
	}

	// Status bulk update
	public function bulk_update_violation_status()
	{
		if (isset($_POST['violation_ids'])) {
			$data['data']     = $_POST['violation_ids'];
			$data['cameras']  = $this->cameraModel->get_all_simple_cameras();
			$data['villages'] = $this->villageModel->get_all_villages_without_stripe_key();
			echo json_encode($data);
		} else echo 'noids';
	}

	// Status bulk DMV update
	public function bulk_update_dmv_status()
	{
		if (is_array($this->request->getPost('violation_ids')) && count($this->request->getPost('violation_ids'))) {
			$ids = $this->request->getPost('violation_ids');
			foreach ($ids as $id) {
				$plate = $this->violationModel->get_violation_by_id($id);
				if (is_array($plate) && count($plate) && $plate['plate_number'] != 'DEMO')
					$this->plateModel->dmv_api($plate['plate_number'], $id);
			}

			echo 'success';
		} else {
			echo 'err';
		}
	}

	// Violation bulk edit
	public function violation_bulk_edit($type = 'all')
	{
		$data = array();

		if (!empty(trim($this->request->getPost('municipality_id'))) && is_numeric(trim($this->request->getPost('municipality_id'))))
			$data['municipality_id'] = trim($this->request->getPost('municipality_id'));
		if (!empty(trim($this->request->getPost('scene_location'))) && is_numeric(trim($this->request->getPost('scene_location'))))
			$data['camera'] = trim($this->request->getPost('scene_location'));
		if (!empty(trim($this->request->getPost('violation_status'))) && is_numeric(trim($this->request->getPost('violation_status'))))
			$data['violation_status'] = trim($this->request->getPost('violation_status'));
		if (!empty(trim($this->request->getPost('payment_status'))) && is_numeric(trim($this->request->getPost('payment_status'))))
			$data['payment_status'] = trim($this->request->getPost('payment_status'));
		if (!empty(trim($this->request->getPost('violation_notice_date')))) {
			$violation_notice_date = date('Y-m-d H:i:s', strtotime($this->request->getPost('violation_notice_date')));
			$data['violation_notice_date'] = $violation_notice_date;
		}
		if (!empty(trim($this->request->getPost('payment_due_date')))) {
			$payment_due_date = date('Y-m-d H:i:s', strtotime($this->request->getPost('payment_due_date')));
			$data['payment_due_date'] = $payment_due_date;
		}

		if (is_array($data) && count($data))
			foreach ($_POST as $key => $id)
				if (is_numeric($key))
					$this->violationModel->edit_violation($data, $id);

		$this->session->setFlashdata('msg', 'Violation info has been bulk edited successfully!');
		$data['view'] = 'admin/violations/violation_bulk_edit';
		$data['type'] = $type;
		return view('layout', $data);
		// redirect()->to(base_url('admin/violations/bulk_edit/' . $type));
	}

	public function update_first_notice_date()
	{
		// 1. Check each plate number and see how many violations of any status you can find. Update plate table with that total count.
		// 2. For each violation, check if status is 3, 5 or 6, then update sent_status = 1
		// 3. Update first notice date
		$this->violationModel->update_totalviolationscount_sentstatus_firstnoticedate_from_violation();
		$this->violationModel->update_warning_period();
		
		echo 'First violation dates are updated successfully.';
	}

	public function recalculate_totals( $plate = '' )
	{
		if ( $this->violationModel->recalculate_totals( $plate ) )
			echo 'Total violations are recaculated successfully.';
	}

	
}
