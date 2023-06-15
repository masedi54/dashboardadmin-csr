<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Order', 'o');
        $this->load->model('M_Pegawai', 'p');
        $this->load->model('M_Auth', 'm_auth');
        $this->load->model('M_Sidebar', 'm_sidebar');

    }

    // MENU PEGAWAI
	public function index()
	{
        $currentUser = $this->m_auth->getCurrentUser();
        $menu = $this->m_sidebar->getSidebarMenu($currentUser['user_group_id']);
        $region = $this->p->getPenyetuju($currentUser['region']);

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
        $this->load->view('pegawai/orderKendaraan');
        $this->load->view('template/footer');
		
	}

    public function dataOrder()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $id_user = $this->session->userdata('id_user');
        $query = $this->o->getDataOrder($id_user);
        
        // var_dump($query->result());
        // die;

        $data = [];
        
        foreach($query->result() as $key => $r){          
            $data[] = array(
                'no' => $key+1,
                'id_kendaraan' => $r->no_polisi,
                'atasan' => ($r->id_atasan == "" ? '<p class="badge bg-label-warning">Menungu</p>' : $r->id_atasan),
                'kegiatan' => $r->kegiatan,
                'tujuan' => $r->tujuan,
                'mulai' => $r->mulai,
                'kembali' => $r->selesai,
                'biaya' => ($r->biaya == "0" ? '<p> - </p>' : $r->biaya),
                'bukti' => '<button type="button" class="btn btn-secondary">Upload</button>',
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

    public function requestOrder()
    {      
        $currentUser = $this->m_auth->getCurrentUser();  
        $id_pegawai = $this->o->getPegawaiId($currentUser['id']); 

        // $id_user = $this->session->userdata('id_user');

        $id_user = $this->db->insert_id();
        
        
        $data = [
            'id_kendaraan' => $this->input->post('id_kendaraan'),
            // 'id_admin' => 1,
            'id_pegawai' => $id_pegawai[0]->id_pegawai,
            'id_atasan' => $this->input->post('atasan'),
            'tgl_order' => date('d-m-Y  h:i:s a'),
            'kegiatan' => $this->input->post('kegiatan'),
            'tujuan' => $this->input->post('tujuan'),
            'mulai' => $this->input->post('mulai'),
            'selesai' => $this->input->post('selesai'),
            // 'status_order' => 404,
        ];

        // var_dump($data);
        // die;
        $this->o->pemesanan($data);
        echo json_encode(['success' =>true, 'message' => 'Request Kendaraan Berhasil Dikirim!']);
        
    }



    // MENU ATASAN
    public function acc()
    {
        $currentUser = $this->m_auth->getCurrentUser();
        $menu = $this->m_sidebar->getSidebarMenu($currentUser['user_group_id']);
        $region = $this->p->getPenyetuju($currentUser['region']);

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
        $this->load->view('atasan/accOrder');
        $this->load->view('template/footer'); 
    }

    public function dataOrderAtasan()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $currentUser = $this->m_auth->getCurrentUser();
        $id_atasan = $this->o->getAtasanId($currentUser['id']); 
        // var_dump($id_atasan);
        // die;
        $query = $this->o->getDataOrderAtasan($id_atasan[0]->id_atasan);
        
        // var_dump($query->result());
        // die;

        $data = [];
        
        foreach($query->result() as $key => $r){          
            $data[] = array(
                'no' => $key+1,
                'id_transaksi' => $r->no_polisi,
                'id_pegawai' => $r->id_pegawai,
                'kegiatan' => $r->kegiatan,
                'tujuan' => $r->tujuan,
                'mulai' => $r->mulai,
                'kembali' => $r->selesai,
                'status' =>  $this->status($r->status_order),
                // 'aksi' => '<a type="button" href="javascript:void(0)" onclick="changeStatus('.$r->id_order.','.$r->status_order.')" class="btn btn-primary">Terima</a>
                // <a type="button" href="javascript:void(0)" onclick="changeStatus('.$r->id_order.','.$r->status_order.')" class="btn btn-danger">Tolak</a>'
                'aksi' => ($r->status_order = 1  ? '
                <form class="w-25" action="" method="post">
                <div class="form-check form-switch w-50">
                <input class="form-check-input" type="checkbox" role="switch" checked>
                </div>
                </form>
                ' : '
                <form class="w-25" action="" method="post">
                <div class="form-check form-switch w-50">
                <input class="form-check-input" onclick=changeStatus(' . $r->id_order . ',' . $r->status_order . ') id="switchActive' . $r->id_order . '" type="checkbox" role="switch" ' . $this->isChecked($r->status_order) . '>
                </div>
                </form>
                '),
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

    private function isChecked($status_order)
    {
        if ($status_order == 1) {
            return 'checked';
        } elseif($status_order == 0) {
            return '';
        }
    }

    public function changeStatus()
    {
        $param = $this->input->post();

        if($param){
            $ubahStatus = $this->o->ubahStatus($param['status_order'], $param['id']);

            if($ubahStatus['success'] == true){
                return true;
            }
        }
    }

}