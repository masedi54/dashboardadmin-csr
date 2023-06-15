<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Pegawai extends CI_Model
{

    public function getUnit()
    {
        $this->db->select('*');
        $this->db->from('ref_unit');
        $get = $this->db->get();
        return $get->result();
    }
    public function getKendaraan()
    {
        $this->db->select('*');
        $this->db->from('kendaraan');

        $get = $this->db->get();
        return $get->result();
    }
    public function getPenyetuju()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('pool', 'users.id = pool.id_users');
        // $this->db->where('pool.region', $region);

        $get = $this->db->get();
        return $get->result();
    }

    public function getDataPegawai()
    {
        $this->db->select('*, pegawai.id as id_pegawai, users.id as id');
        $this->db->from('users'); 
        $this->db->join('pegawai', 'users.id = pegawai.id_user');
        // $this->db->join('ref_unit', 'pegawai.unit_kerja = ref_unit.id');

        return $this->db->get();
    }

    public function tambahUsers($users)
    {
        return $this->db->insert('users', $users);
    }

    public function tambahPegawai($data)
    {
        return $this->db->insert('pegawai', $data);
    }

    public function pegawaiId($id_pegawai)
    {
        $this->db->select('* , pegawai.id as id_pegawai, users.id as id_user');
        $this->db->from('users');
        $this->db->join('pegawai', 'users.id = pegawai.id_user');
        $this->db->join('ref_unit', 'pegawai.unit_kerja = ref_unit.id');

        $this->db->where('pegawai.id', $id_pegawai);
        $query = $this->db->get();
 
        return $query->row();
    }

    public function editUser($users, $id)
    {
        $this->db->set($users);
        $this->db->where('id', $id);
        return $this->db->update('users');
    }

    public function editPegawai($data, $id_pegawai)
    {
        $this->db->set($data);
        $this->db->where('id', $id_pegawai);
        return $this->db->update('pegawai');
    }

    public function hapusPegawai($id_pegawai)
    {
        $this->db->delete('pegawai', array('id' => $id_pegawai));
    }

    public function hapusUsers($id_pegawai)
    {
        $this->db->delete('users', array('id' => $id_pegawai));
    }

    public function ubahStatusPegawai($id_pegawai, $newStatus)
    {
        // var_dump($newStatus);
        // die;
        $dataStatus = [
            'is_active' => (string)$newStatus,
        ];

        $this->db->where(['id' => $id_pegawai]);
        $this->db->update('users', $dataStatus);


        return [
            'success' => true,
        ];
    }
}