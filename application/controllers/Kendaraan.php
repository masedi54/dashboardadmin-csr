<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kendaraan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Kendaraan', 'k');
        $this->load->model('M_Auth', 'm_auth');
        $this->load->model('M_Sidebar', 'm_sidebar');
    }
	
	public function index()
	{
        $currentUser = $this->m_auth->getCurrentUser();
        $menu = $this->m_sidebar->getSidebarMenu($currentUser['user_group_id']);
        $data['menu'] = $menu;

        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar');
        $this->load->view('admin/dataKendaraan');
        $this->load->view('template/footer');
	}

	public function dataKendaraan()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $query = $this->k->getDataKendaraan();

        $data = [];
        
        foreach($query->result() as $key => $r){          
            $data[] = array(
                'no' => $key+1,
                'no_polisi' => $r->no_polisi,
                'tipe_angkut' => $r->tipe_angkut,
                'kepemilikan' => $r->kepemilikan,
                'status' => $r->status,
                'action' => '
                <a type="button" href="javascript:void(0)" onclick=" editKendaraan('."'".$r->id."'".')" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                <a href="#" data-delete-url="' . site_url("index.php/kendaraan/hapus/". $r->id). '" role="button" onclick="return deleteConfirm(this)" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>'
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

    public function tambahKendaraan()
    {          
        $data = [
            'no_polisi' => $this->input->post('no_polisi'),
            'tipe_angkut' => $this->input->post('angkutan'),
            'kepemilikan' => $this->input->post('kepemilikan'), 
            'status' => $this->input->post('status'), 
        ];

        $this->k->tambahKendaraan($data);
        echo json_encode(['success' =>true, 'message' => 'Data Kendaraan Berhasil Ditambahkan!']);
        
    }

    public function editKendaraan()
    {

        $id = $this->input->post('id');

        $data = [
            'no_polisi' => $this->input->post('no_polisi'),
            'tipe_angkut' => $this->input->post('angkutan'),
            'kepemilikan' => $this->input->post('kepemilikan'), 
            'status' => $this->input->post('status'), 
        ];
       
        $this->k->editKendaraan($data, $id);

        echo json_encode(['success' =>true, 'message' => 'Data Berhasil Diperbarui!']);
    }

    public function edit($id)
    {
        $data = $this->k->KendaraanId($id);
        echo json_encode($data);

    }

    public function hapus($id)
    {
        $this->k->hapusKendaraan($id);
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus!']); 
    }
}