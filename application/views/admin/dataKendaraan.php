<!-- Form modal tambah kendaraan-->
<div class="modal" tabindex="-1" id="form" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                      <!-- <span aria-hidden="true">&times;</span></button> -->
                      <h4 class="modal-title">Tambah Kendaraan</h4>
                      <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="form-tambah">
                        <div class="modal-body">
                            <input class="" type="hidden" name="id">
                            <div class="mb-3">
                                <label for="">No. Polisi</label>
                                <input class="form-control" type="text" name="no_polisi">
                                <div class="invalid-feedback"></div>                                
                            </div>
                            <div class="mb-3">
                                <label for="">Tipe Angkutan</label>
                                <select class="form-select form-control" name="angkutan">
                                  <option value="">--pilih--</option>
                                  <option value="orang">Orang</option>
                                  <option value="barang">Barang</option> 
                                </select> 
                                <div class="invalid-feedback"></div>                                               
                            </div>
                            <div class="mb-3">
                                <label for="">Kepemilikan</label>
                                <select class="form-select form-control" name="kepemilikan">
                                  <option value="">--pilih--</option>
                                  <option value="perusahaan">Perusahaan</option>
                                  <option value="sewa">Sewa</option> 
                                </select> 
                                <div class="invalid-feedback"></div>                                               
                            </div>                            
                            <div class="mb-3">
                                <label for="">Status</label>
                                <select class="form-select form-control" name="status">
                                  <option value="">--pilih--</option>
                                  <option value="1">Aktif</option>
                                  <option value="0">Tidak Aktif</option> 
                                </select> 
                                <div class="invalid-feedback"></div>                                               
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-danger pull-left" data-dismiss="modal">Tutup</button>
                            <button type="submit" onclick="simpan()" id="btnSimpan" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>

<!-- Content wrapper -->
<div class="content-wrapper">
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
<!-- Bootstrap Table with Header - Dark -->
<div class="card" style="padding: 0px 20px;">
    <h5 class="card-header">Data Kendaraan</h5>
    <button style="float: left; width: 230px; margin-bottom: 10px;" type="button" onclick="tambah_kendaraan()" class="btn btn-primary" id="tombol-tambah" data-bs-toggle="modal" data-bs-target="#form" >
        <i class="fa fa-lg fa-fw fa-plus" aria-hidden="true"></i>Tambah Kendaraan
    </button>
        <div class="table-responsive text-nowrap">
            <table class="table" id="postsList" style="padding: 10px 0px; width= 100%; max-width: 100%;">
                <thead class="table-dark" >
                    <tr>
                        <th>NO</th>
                        <th>No. Polisi</th>
                        <th>Tipe Angkutan</th>
                        <th>Jenis Kepemilikan</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                </tbody>
            </table>
        </div>
    </div>
<!--/ Bootstrap Table with Header Dark -->
</div>

<!-- Data Table -->
<script type='text/javascript'>  
    var save_method;
     $(document).ready(function() {
        table = $('#postsList').DataTable({
        ajax: "<?= base_url("index.php/kendaraan/dataKendaraan")?>",
        columns: [{
            data: 'no'
            },
            {
            data: 'no_polisi'
            },
            {
            data: 'tipe_angkut'
            },
            {
            data: 'kepemilikan'
            },
            {
            data: 'status'
            },
            {
            data: 'action'
            },
            
        ],
        });

    });

    function editKendaraan(id)
    {
      save_method = 'update';
      $('#form-tambah')[0].reset(); // reset form modal
    
      $.ajax({
        url : "<?php echo site_url('index.php/kendaraan/edit')?>/" + id,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
            $("[name='no_polisi']").val(data.no_polisi);
            $('[name="angkutan"]').val(data.tipe_angkut);
            $('[name="kepemilikan"]').val(data.kepemilikan);
            $('[name="status"]').val(data.status);
            $('[name="id"]').val(id);
            
            $('#form').modal('show'); // Menampilkan modal
            $('.modal-title').text('Edit Kendaraan'); // Ubah title modal
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    }

    function tambah_kendaraan()
    {
      save_method = 'add';
      $('#form-tambah')[0].reset(); // reset form modal
      
      $('#form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Tambah Pelatihan'); // Ubah title modal
    }

    function simpan(){
      $('#btnSimpan').text('menyimpan...'); // Ubah tombol simpan
      $('#btnSimpan').attr('disabled',true); // Menonaktifkan tombol simpan
      
      var url;  
      if(save_method == 'add') {
        url = "<?php echo base_url('index.php/kendaraan/tambahKendaraan')?>";
      } else {
        url = "<?php echo base_url('index.php/kendaraan/editKendaraan')?>";
      }
      $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: $('#form-tambah').serialize(),
        success: function(data) {
          if (data.success) {
            $('#form').modal('hide');
            table.ajax.reload();
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              showCloseButton: true,
              timer: 5000,
              timeProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            });
            Toast.fire({
                icon: 'success',
                title: data.message
              });
          } 
          $('#btnSimpan').text('Simpan'); // Ubah tombol simpan
          $('#btnSimpan').attr('disabled',false); // Menonaktifkan tombol simpan 
        },
      });
    }
   
</script>
<!-- Fungsi Hapus -->
<script>
  function deleteConfirm(event) {
    Swal.fire({
      title: 'Konfirmasi Hapus Data!',
      text: 'Yakin ingin menghapus data ini?',
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: 'Tidak',
      confirmButtonText: 'Ya, Hapus',
      confirmButtonColor: 'red'
    }).then(dialog => {
      if (dialog.isConfirmed) {
        $.ajax({
          url: event.dataset.deleteUrl,
          type: 'GET',
          dataType: "JSON",
          error: function() {
            alert('Terjadi Kesalahan');
          },
          success: function(data) {
            if (data.success) {
              table.ajax.reload();
              const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 5000,
                timeProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })
              Toast.fire({
                icon: 'success',
                title: data.message
              });
            } else alert('Something is GJ')
          }
        })
      }
    })
  }
</script>