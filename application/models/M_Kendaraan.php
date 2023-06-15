<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Kendaraan extends CI_Model
{

    public function getDataKendaraan()
    {
        $this->db->select('*');
        $this->db->from('kendaraan'); 
   
        return $this->db->get();
    }

    public function tambahKendaraan($data)
    {
        return $this->db->insert('kendaraan', $data);
    }

    public function KendaraanId($id)
    {
        $this->db->select('*');
        $this->db->from('kendaraan');
        $this->db->where('id', $id);
        $query = $this->db->get();
 
        return $query->row();
    }

    public function editKendaraan($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('kendaraan');
    }

    public function hapusKendaraan($id)
    {
        $this->db->delete('kendaraan', array('id' => $id));
    }
}