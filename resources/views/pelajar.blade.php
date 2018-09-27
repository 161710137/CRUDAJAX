<!DOCTYPE html>
<html>
  <head>
    <title>Datatables</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>       
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  </head>
  <body>
    <div class="container">
      <br />
      <h3 align="center">Datatables Server Side Processing in Laravel</h3>
      <br />
      <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Tambah</button>
      </div>
      <br />
      <table id="pelajar_table" class="table table-bordered" style="width:100%">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Eskul</th>
            <th>Foto</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>
    <!-- modal -->
    <div id="pelModal" class="modal fade" role="dialog" data-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post" id="pel_form" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              {{csrf_field()}}
              <span id="form_output"></span>
              <div class="form-group">
                <input type="hidden" id="id" name="id">
                <label>Nama</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukan nama siswa/siswi" />
                <span class="help-block has-error nama_error "></span>
              </div>
              <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" id="kelas" class="form-control" placeholder="Masukan data kelas" />
                <span class="help-block has-error kelas_error"></span>
              </div>
              <div class="form-group">
                <label>Jenis Kelamin</label><br>
                <input name="jk" type="radio" value="Laki-Laki" id="lk">Laki-Laki
                <input type="radio" name="jk" value="Perempuan" id="pr">Perempuan
                <span class="help-block has-error jk_error"></span>
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukan data alamat"></textarea>
                <span class="help-block has-error alamat_error "></span>
              </div>
              <div class="form-group">
                <label>Eskul</label><br>
                <input type="checkbox" name="eskul[]" value="Karawitan">Karawitan<br>
                <input type="checkbox" name="eskul[]" value="Seni Tari">Seni Tari<br>
                <input type="checkbox" name="eskul[]" value="Futsal">Futsal<br>
                <input type="checkbox" name="eskul[]" value="Basket">Basket<br>
                <input type="checkbox" name="eskul[]" value="Voli">Voli<br>
                <input type="checkbox" name="eskul[]" value="Rohis">Rohis<br>
                <input type="checkbox" name="eskul[]" value="Band">Band<br>
                <input type="checkbox" name="eskul[]" value="TAC">TAC<br>
                <input type="checkbox" name="eskul[]" value="Karate">Karate<br>
                <input type="checkbox" name="eskul[]" value="Taekwondo">Taekwondo
                <span class="help-block has-error eskul_error"></span>
              </div>
              <div class="form-group">
                <label>Foto</label>
                <input type="file"  name="foto" id="foto" class="form-control" placeholder="Masukan data foto">
                <span class="help-block has-error foto_error "></span>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="button_action" id="button_action" value="insert" />
                <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="{{asset('loading-overlay/dist/loadingoverlay.min.js')}}"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $.LoadingOverlay('show');
          //get ke dataTable
          $('#pelajar_table').DataTable({
              "processing": true,
              "serverSide": true,
              "ajax":'{{route('a')}}',
              "columns":[
                  { "data": "nama" },
                  { "data": "kelas" },
                  { "data": "jk"},
                  { "data": "alamat"},
                  { "data": "eskul"},
                  { "data": "show_photo"},
                  { "data": "action"}
              ]
          });
          $.LoadingOverlay('hide');
          //Tambah data
          $('#add_data').click(function(){
          $('#pelModal').modal('show');
          $('#pel_form')[0].reset();
          $('#action').val('Tambah');
          $('.modal-title').text('Tambah Data');
          state = "insert";
          });

          $('#pelModal').on('hidden.bs.modal',function(e){
            $(this).find('#pel_form')[0].reset();
            $('span.has-error').text('');
            $('.form-group.has-error').removeClass('has-error');
          });

          $('#pel_form').submit(function(e){
              $.ajaxSetup({
                  headers:{
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                  }
              });
              e.preventDefault();
              var eskul = $("input[name='eskul[]']").serializeArray();
              if (eskul.length>0) {
                if (state == 'insert') {
                    $.ajax({
                      type:"POST",
                      url:"{{url('siswas')}}",
                      // data:$('#pel_form').serialize(),
                      data: new FormData(this),
                      contentType:false,
                      processData:false,
                      dataType:'json',
                      success:function(data){
                          console.log(data);
                          $('#pelModal').modal('hide');
                          $('#pelajar_table').DataTable().ajax.reload();
                          swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '3500'
                            })
                      },
                      error:function(data){
                          $('input').on('keydown keypress keyup click change',function(){
                              $(this).parent().removeClass('has-error');
                              $(this).next('.help-block').hide()
                          });
                          var coba = new Array();
                          console.log(data.responseJSON.errors);
                          $.each(data.responseJSON.errors, function(name,value){
                              coba.push(name);
                              $('input[name='+name+']').parent().addClass('has-error');
                              $('input[name='+name+']').next('.help-block').show().text(value);
                          });
                          $('input[name='+coba[0]+']').focus();         
                      }
                    });
                }else{
                  $.ajax({
                  type:"POST",
                  url:"{{url('siswa/edit')}}"+'/'+$('#id').val(),
                  // data:$('#pel_form').serialize(),
                  data: new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType:'json',
                  success:function(data){
                      console.log(data);
                          $('#pelModal').modal('hide');
                          $('#pelajar_table').DataTable().ajax.reload();
                      },
                      error:function(data){
                          <!--error message -->
                              $('input').on('keydown keypress keyup click change',function(){
                                  $(this).parent().removeClass('has-error');
                                  $(this).next('.help-block').hide()
                              });
                              var coba = new Array();
                              console.log(data.responseJSON.errors);
                              $.each(data.responseJSON.errors, function(name,value){
                                  coba.push(name);
                                  $('input[name='+name+']').parent().addClass('has-error');
                                  $('input[name='+name+']').next('.help-block').show().text(value);
                              });
                              $('input[name='+coba[0]+']').focus();         
                  }
                    });
                }       
              }
              else{
                alert('pilih salah 1 eskul')
              }
         
              
          });
          //edit
          $(document).on('click', '.edit', function(){
              var edit = $(this).data('id');
              $('#form_output').html('');
              $.ajax({
                  url:"{{url('siswa/getEdit')}}"+'/'+ edit,
                  method:'get',
                  data:{id:edit},
                  dataType:'json',
                  success:function(data)
                  {
                        $('#action').val('Edit');
                        $('.modal-title').text('Edit Data');
                      console.log(data);
                      var eskul = data.eskul;
                      var eskul_array = eskul.split(',');
                      console.log(eskul);
                      state = "update";
                      $('#id').val(data.id);
                      $('#nama').val(data.nama);
                      $('#kelas').val(data.kelas);
                      if(data.jk == 'Laki-Laki'){
                          $('#lk').prop('checked',true);
                      }else{
                          $('#pr').prop('checked',true);
                      }
                      $('#alamat').val(data.alamat);
                      var i =0;
                      while(i<eskul_array.length){
                        var checkbox = $("input[type='checkbox'][value='"+eskul_array[i]+"']");
                        checkbox.prop("checked",true);
                        i++;
                      }
                      // $('#foto').val(data.foto);
                      $('#siswa_id').val(id);
                      $('#pelModal').modal('show');
                      $('#action').val('Edit');
                  }
              });
          });
          $(document).on('hide.bs.modal','#pelModal',function(){
            $('#pelajar_table').DataTable().ajax.reload();
          });
          $(document).on('click', '.delete', function(){
              var id = $(this).attr('id');
              if(confirm("Anda yakin ingin menghapus data ini?"))
              {
                  $.ajax({
                      url:"{{route('ajaxdata.removedata')}}",
                      mehtod:"get",
                      data:{id:id},
                      success:function(data)
                      {
                          alert(data);
                          $('#pelajar_table').DataTable().ajax.reload();
                      }
                  })
              }
              else
              {
                  return false;
              }
          });
      });
    </script>
  </body>
</html>