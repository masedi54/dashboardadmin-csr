<!-- Form modal tambah kendaraan-->
<div class="modal" tabindex="-1" id="form" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                      <!-- <span aria-hidden="true">&times;</span></button> -->
                      <h4 class="modal-title">Form Order Kendaraan</h4>
                      <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="form-tambah">
                        <div class="modal-body">
                            <!-- <input class="" type="hidden" name="id_pegawai">
                            <input class="" type="hidden" name="id"> -->
                            <div class="mb-3">
                                <label for="">Kendaraan</label>
                                <select class="form-select form-control" name="id_kendaraan">
                                <option value="">--pilih--</option>
                                  <?php foreach ($kendaraan as $k):?>
                                    <option value="<?php echo $k->id?>"><?php echo $k->no_polisi ?></option>
                                  <?php endforeach;?>
                                </select> 
                              </div>
                            <div class="mb-3">
                                <!-- <label for="">Nama</label> -->
                                <input class="form-control" type="hidden" name="nama_pegawai" value="<?= $nama ?>">                                
                            </div>
                            <!-- <div class="mb-3">
                                <label for="">Penyetuju</label>
                                <select class="form-select form-control" name="atasan">
                                <option value="">--pilih--</option>
                                  <?php foreach ($penyetuju as $p):?>
                                    <option value="<?php echo $p->id_pool?>"><?php echo $p->nama ?></option>
                                  <?php endforeach;?>
                                </select>                                 
                            </div> -->
                            <!-- <div class="mb-3">
                                <label for="">Unit</label>
                                <input class="form-control" type="text" name="unit" value="<?= $region ?>">                                                                               
                            </div> -->
                            <div class="mb-3">
                                <label for="">Kegiaatan</label>
                                <input class="form-control" type="text" name="kegiatan">                                                                                
                            </div>                            
                            <div class="mb-3">
                                <label for="">Tujuan</label>
                                <input class="form-control" type="text" name="tujuan">                                                                               
                            </div>
                            <div class="mb-3">
                                <label for="">Tanggal Peminjaman</label>
                                <input class="form-control" type="date" name="mulai">                                                                               
                            </div>
                            <div class="mb-3">
                                <label for="">Tanggal Pengembalian</label>
                                <input class="form-control" type="date" name="selesai">                                                                               
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
    <h5 class="card-header">Data Order Kendaraan</h5>
    <button style="float: left; width: 230px; margin-bottom: 10px;" type="button" onclick="tambah_order()" class="btn btn-primary" id="tombol-tambah" data-bs-toggle="modal" data-bs-target="#form" >
        <i class="fa fa-lg fa-fw fa-plus" aria-hidden="true"></i>Order Kendaraan
    </button>
        <div class="table-responsive text-nowrap">
            <table class="table" id="postsList" style="padding: 10px 0px; width= 100%; max-width: 100%;">
                <thead class="table-dark" >
                    <tr>
                        <th>NO</th>
                        <th>ID Kendaraan</th>
                        <th>Penyetuju</th>
                        <th>Kegiatan</th>
                        <th>Tujuan</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Biaya BBM</th>
                        <th>Bukti Nota</th>
                        <th>Status</th>
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
        ajax: "<?= base_url("index.php/order/dataOrder")?>",
        columns: [{
            data: 'no'
            },
            {
            data: 'id_kendaraan'
            },
            {
            data: 'atasan'
            },
            {
            data: 'kegiatan'
            },
            {
            data: 'tujuan'
            },
            {
            data: 'mulai'
            },
            {
            data: 'kembali'
            },
            {
            data: 'biaya'
            },
            {
            data: 'bukti'
            },
            {
            data: 'status'
            },
            
        ],
        });

    });

    function editPegawai(id_pegawai)
    {
      save_method = 'update';
      $('#form-tambah')[0].reset(); // reset form modal
    
      $.ajax({
        url : "<?php echo site_url('index.php/pegawai/edit')?>/" + id_pegawai,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
            $("[name='id_pegawai']").val(data.id_pegawai);
            $('[name="nama"]').val(data.nama);
            $('[name="hp"]').val(data.no_tlp);
            $('[name="unit"]').val(data.region);
            $('[name="email"]').val(data.email);
            $('[name="username"]').val(data.username);
            $('[name="password"]').val(data.password);
            $('[name="id"]').val(data.id_user);
            
            $('#form').modal('show'); // Menampilkan modal
            $('.modal-title').text('Edit Pegawai'); // Ubah title modal
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    }

    function tambah_order()
    {
      save_method = 'add';
      $('#form-tambah')[0].reset(); // reset form modal
      
      $('#form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Tambah Pegawai'); // Ubah title modal
    }

    function simpan(){
      $('#btnSimpan').text('menyimpan...'); // Ubah tombol simpan
      $('#btnSimpan').attr('disabled',true); // Menonaktifkan tombol simpan
      
      var url;  
      if(save_method == 'add') {
        url = "<?php echo base_url('index.php/order/requestOrder')?>";
      } else {
        url = "<?php echo base_url('index.php/pegawai/editPegawai')?>";
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