<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        if (!is_null($id)) {
            $query = $this->db->select('*')->from('vw_ticket')->where('id_ticket', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }

        $query = $this->db->select('*')->from('vw_ticket')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    public function get_progress($id = null)
    {
        if (!is_null($id)) {
            $query = $this->db->select('id_ticket,progress')->from('vw_ticket')->where('id_ticket', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }

        $query = $this->db->select('id_ticket,progress')->from('vw_ticket')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    public function get_user($id = null)
    {
          if (!is_null($id)) {
            $query = $this->db->select('id_user')->from('ticket')->where('id_ticket', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }

        $query = $this->db->select('id_user')->from('ticket')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    public function save($ticket)
    {
        $this->db->set($this->_setTicket($ticket))->insert('ticket');

        if ($this->db->affected_rows() === 1) {
            $date = $this->getDatetimeNow();
            $user = $ticket['user'];
            $data = [
                'created_date' => $date,
                'created_by'   => $user
            ];
            $id = $this->db->insert_id();
            $this->db->set($data)->where('id_ticket', $id)->update('ticket');
            return $id;
        }

        return null;
    }

    public function update($ticket)
    {
        $id = $ticket['id_ticket'];

        $this->db->set($this->_setTicket($ticket))->where('id_ticket', $id)->update('ticket');

        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    public function update_progress($ticket)
    {
        $id = $ticket['id_ticket'];

        $data = array(
               'progress' => $ticket['progress'],
               'stat'     => 'P'
            );

        $this->db->set($data)->where('id_ticket', $id)->update('ticket');

        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    public function delete($id)
    {
        //$this->db->where('id_ticket', $id)->delete('ticket');
        $this->db->set('stat','Ca')->where('id_ticket',$id)->update('ticket');
        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    private function _setTicket($ticket)
    {
        return array(
            'problems'      => $ticket['problems'],
            'attachment'    => $ticket['attachment'],
            'id_category'   => $ticket['id_category'],
            'id_pic'        => $ticket['id_pic'],
            'id_user'       => $ticket['id_user'],
            'progress'      => $ticket['progress'],
            'stat'          => $ticket['stat'],
        );
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('Asia/Makassar');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ H:i:s');
    }

    function simpan_upload($id,$attachment,$type){
        $where = array('id_ticket' => $id);
        $data = array(
            'attachment' => $attachment,
        );
        $this->db->update('ticket',$data,$where);

        return $this->db->affected_rows();
    }

}