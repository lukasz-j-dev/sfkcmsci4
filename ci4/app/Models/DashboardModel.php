<?php
use CodeIgniter\Model;

class DashboardModel extends Model
{
	protected $db;
    public function __construct()
    {
        $this->db = db_connect();
	}
		public function get_all_users(){
			return $this->db->table('ci_users')->countAllResults();
		}
		public function get_active_users(){
			return $this->db->table('ci_users')->where('is_active', 1)->countAllResults();

		}
		public function get_deactive_users(){

			return $this->db->table('ci_users')->where('is_active', 0)->countAllResults();
		}
	
}