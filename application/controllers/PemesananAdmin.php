<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PemesananAdmin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Order', 'o');
        $this->load->model('M_Pegawai', 'p');
        $this->load->model('M_Auth', 'm_auth');
        $this->load->model('M_Sidebar', 'm_sidebar');

    }

    // MENU ADMIN
    public function index(){
        $currentUser = $this->m_auth->getCurrentUser();
        $menu = $this->m_sidebar->getSidebarMenu($currentUser['user_group_id']);
        $region = $this->p->getPenyetuju();

        // var_dump($region);
        // die;
        $data['menu'] = $menu;
        $data['unit'] = $this->p->getUnit();
        $data['kendaraan'] = $this->p->getKendaraan();
        $data['penyetuju'] = $region;
        $data['nama'] = $currentUser['nama'];
        $data['region'] = $currentUser['region'];

        // var_dump($data['penyetuju']);
        // die;
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar');
        $this->load->view('admin/pemesanan');
        $this->load->view('template/footer');
    }

    public function dataPemesanan()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        // $id_user = $this->session->userdata('id_user');
        $query = $this->o->getDataPemesanan();
        
        // var_dump($query->result());
        // die;

        $data = [];
        
        foreach($query->result() as $key => $r){          
            $data[] = array(
                'no' => $key+1,
                'id_transaksi' => $r->id_order,
                'id_pegawai' => $r->id_pegawai,
                'region' => $r->unit_kerja,
                'kegiatan' => $r->kegiatan,
                'tujuan' => $r->tujuan,
                'mulai' => $r->mulai,
                'kembali' => $r->selesai,
                'atasan' => ($r->id_atasan == "" ? '<a type="button" href="javascript:void(0)" onclick=" addPenyetuju('."'".$r->id_order."'".')" class="btn btn-secondary"><i class="fa fa-plus" aria-hidden="true" style="color: #ffff;"></i></a>' : $r->id_atasan),
                'biaya' => ($r->biaya == "0" ? '<p> - </p>' : $r->biaya),
                // 'status' => $r->status_order,
                'status' => $this->status($r->status_order)
            ); 
        }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordFiltered" => $query->num_rows(),
            "data" => $data
        );
        echo json_encode($result);
        exit(); 
    }

    public function add($id)
    {
        $data = $this->o->getDataPemesananID($id);
        // var_dump($data);
        // die;
        echo json_encode($data);
    }

    public function tambahPenyetuju()
    {
        $this->_validation();
        $id = $this->input->post('id');

        $users = [
            'id_atasan' => $this->input->post('atasan'),    
        ];

        $this->o->addPenyetuju($users, $id);
        echo json_encode(['success' => true, 'message' => 'Data Berhasil Diperbarui!']);
    }

    private function status($status_order)
    {
        switch ($status_order) {
            case 404:
                return '<p class="badge bg-label-warning">Menungu</p>';
                break;
            case 200:
                return '<p class="badge bg-label-info">Diproses</p>';
                break;
            case 1:
                return '<p class="badge bg-label-success">Diterima</p>';
                break;
            case 0:
                return '<p class="badge bg-label-danger">Ditolak</p>';
                break;

            // default:
            //     return '<p class="fw-bolder text-danger">Denied</p>';
            //     break;
        }
    }

    
}