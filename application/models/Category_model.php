<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    var $table = 'm_category';
    var $view  = 'vw_pic';
    var $id    = 'id_category';

    public function get($id = null)
    {
        if (!is_null($id)) {
            $query = $this->db->select('*')->from($this->table)->where($this->id, $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }

        $query = $this->db->select('*')->from($this->table)->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    public function save($category)
    {
        $this->db->set($this->_setPic($category))->insert($this->table);

        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }

        return null;
    }

    public function update($category)
    {
        $id = $category['id'];

        $this->db->set($this->_setTicket($category))->where($this->id, $id)->update($this->table);

        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    public function delete($id)
    {
        //$this->db->where('id_ticket', $id)->delete('ticket');
        $this->db->set('stat','C')->where($this->id,$id)->update($this->table);
        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    private function _setPic($category)
    {
        return array(
            'name_category'    => $category['name'],
            'abbr'             => $category['abbr'],
        );
    }
}