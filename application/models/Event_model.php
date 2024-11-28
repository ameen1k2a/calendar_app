<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends CI_Model {

    protected $table = 'events';

    public function get_all_events() {
        return $this->db->get($this->table)->result_array();
    }

    public function insert_event($data) {
        $this->db->insert($this->table, $data);
    }

    public function update_event($id, $data) {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_event($id) {
        $this->db->where('id', $id)->delete($this->table);
    }
}
