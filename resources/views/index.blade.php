<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- jsquery -->   
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
</head>
<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>crud product with datables</h2>
                </div>
                <div class="pull-right mb-2">
                    <a class="btn btn-success" onclick="add()" href="javascript:void(0)">Input Product</a>
                </div>
            </div>
        </div>
        @if ($messege = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $messege }}</p>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered" id="ajax-crud-datatable" >
            <thead>
                <tr>
                    <th>id</th>
                    <th>nama</th>
                    <th>desc</th>
                    <th>harga</th>
                    <th>created at</th>
                    <th>action</th>
                </tr>
            </thead>
            </table>
        </div>
    </div>

    <!-- input data modal -->

    <!-- Modal -->
<div class="modal fade" id="product-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Input data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="javascript:void(0)" id="InputForm" name="InputForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Nama</label>
                            <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" maxlength="50" required="">
                        </div>
                    </div>  
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-12">
                            <input type="text" class="form-control" id="desc" name="desc" placeholder="Enter Description" maxlength="50" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Harga</label>
                            <div class="col-sm-12">
                            <input type="text" class="form-control" id="harga" name="harga" placeholder="Enter Harga" required="">
                        </div>
                    </div>
                   
                    <button type="submit" class="btn btn-primary mt-3 " id="btn-save">Save changes</button>
      
                </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" >
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#ajax-crud-datatable').DataTable({
            processing: true,
            serverside: true,
            ajax: "{{url('ajax-crud-datatable') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'desc', name: 'desc' },
                { data: 'harga', name: 'harga' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable:false },
            ],
             order: [[0, 'asc']]
        });
    });

    function add()
    {
        $('#InputForm').trigger("reset");
        $('ProductModal').html("input data")
        $('#product-modal').modal('show');
        $('id').val('');
    }

    function editFunc(id){
    $.ajax({
        type:"POST",
        url: "{{ url('edit') }}",
        data: { id: id },
        dataType: 'json',
        success: function(res){
            console.log(res)
            $('#ProductModal').html("Edit Data");
            $('#product-modal').modal('show');
            $('#id').val(res.id);
            $('#name').val(res.name);
            $('#desc').val(res.desc);
            $('#harga').val(res.harga);
        }
    });
} 

function deleteFunc(id){
    if (confirm("Delete Record?") == true) {
        var id = id;
        // ajax
        $.ajax({
            type:"POST",
            url: "{{ url('delete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                var oTable = $('#ajax-crud-datatable').dataTable();
                oTable.fnDraw(false);
            }
        });
    }
}
 

    

$('#InputForm').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: "{{ url('store')}}",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            $("#product-modal").modal('hide');
            var oTable = $('#ajax-crud-datatable').dataTable();
            oTable.fnDraw(false);
            $("#btn-save").html('Submit');
            $("#btn-save"). attr("disabled", false);
        },
        error: function(data){
            console.log(data);
        }
    });
});
</script>

</body>
</html>