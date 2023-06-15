<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Order extends CI_Model
{

    public function getUnit()
    {
        $this->db->select('*');
        $this->db->from('ref_unit');
        $get = $this->db->get();
        return $get->result();
    }
    public function getPegawaiId($id)
    {
        
        $this->db->select('pegawai.id as id_pegawai');
        $this->db->from('users');
        $this->db->join('pegawai', 'users.id = pegawai.id_user');
        $this->db->where('pegawai.id_user', $id);

        return $this->db->get()->result();
    }

    public function getAtasanId($id)
    {
        
        $this->db->select('pool.id as id_atasan');
        $this->db->from('users');
        $this->db->join('pool', 'users.id = pool.id_users');
        $this->db->where('pool.id_users', $id);

        return $this->db->get()->result();
    }

    public function getDataOrder($id_user)
    {
        // $this->db->select('*, pegawai.id as id_pegawai, kendaraan.id as id_kendaraan, pool.id as id_atasan, pemesanan.id as id_order');
        $this->db->select('*');
        $this->db->from('pemesanan'); 
        $this->db->join('pegawai', 'pemesanan.id_pegawai = pegawai.id');
        $this->db->join('kendaraan', 'pemesanan.id_kendaraan = kendaraan.id');
        $this->db->where('pegawai.id_user', $id_user);

        // $this->db->join('pool', 'pemesanan.id_atasan = pool.id');

        return $this->db->get();
    }

    // FUNGSI QUERY ATASAN
    public function getDataOrderAtasan($id_atasan)
    {
        $this->db->select('*, pegawai.id as id_pegawai, kendaraan.id as id_kendaraan, pool.id as id_atasan, pemesanan.id as id_order');
        // $this->db->select('*');
        $this->db->from('pemesanan'); 
        $this->db->join('pegawai', 'pemesanan.id_pegawai = pegawai.id');
        $this->db->join('kendaraan', 'pemesanan.id_kendaraan = kendaraan.id');
        $this->db->join('pool', 'pemesanan.id_atasan = pool.id');
        $this->db->where('pool.id', $id_atasan);

        return $this->db->get();
    }

    public function getDataPemesanan()
    {
        $this->db->select('*, pegawai.id as id_pegawai, kendaraan.id as id_kendaraan, pemesanan.id as id_order');
        $this->db->from('pemesanan'); 
        $this->db->join('pegawai', 'pemesanan.id_pegawai = pegawai.id');
        $this->db->join('kendaraan', 'pemesanan.id_kendaraan = kendaraan.id');
        // $this->db->join('pool', 'pemesanan.id_atasan = pool.id');

        return $this->db->get();
    }

    public function getDataPemesananID($id)
    {
        $this->db->select('*, pegawai.id as id_pegawai, kendaraan.id as id_kendaraan, pemesanan.id as id_order');
        $this->db->from('pemesanan'); 
        $this->db->join('pegawai', 'pemesanan.id_pegawai = pegawai.id');
        $this->db->join('kendaraan', 'pemesanan.id_kendaraan = kendaraan.id');
        $this->db->where('pemesanan.id', $id);

        $query = $this->db->get();
        return $query->row();
    }

    public function uploadSertif($users, $id)
    {
        $this->db->set($users);
        $this->db->where('id', $id);
        return $this->db->update('users');
    }

    public function pemesanan($data)
    {
        return $this->db->insert('pemesanan', $data);
    }

    public function ubahStatus($status_order, $id)
    {
        $dataStatus = [
            'status_order' => (string)$status_order,
        ];

        $this->db->where(['id' => $id]);
        $this->db->update('pemesanan', $dataStatus);

        return[
            'success' => true,
        ];
    }
    
}