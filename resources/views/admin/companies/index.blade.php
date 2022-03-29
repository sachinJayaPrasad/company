@extends('admin.layouts.common')
@section('content')
<div class="container" style="margin-left:200px;">
    @if (Session::has('message'))
        <div class="alert alert-{{ Session::get('status') }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row pt-5">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <h3 class="col-md-6">COMPANIES LIST</h3>
                <div class="text-right col-md-6">
                    <button class="btn-sm btn-success" data-toggle="modal" data-target="#addModal" >
                        ADD NEW
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-striped data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>IMAGE</th>
                        <th>WEBSITE LINK</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- Add Modal  --}}
  <div class="modal fade" id="addModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center tx-primary" id="myModalLabel">Add Company</h4>
            </div>
            <form id="add-company" action="javascript:;">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Company Name:<span class="tx-danger">*</span></label>
                        <input type="hidden" name="company_id" id="company_id">
                        <input class="form-control" id="name" name="name" type="text" placeholder="Enter Company Name"/>
                    </div>
                     <div class="form-group row">
                        <label>Image: <span class="tx-danger">*</span></label>
                        <div class="form-group image-class" style="display: none;">
                            <img class="img-thumbnail image-display" src="#" alt="theme" width="100">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-control" id="file" name="file">
                            <label class="text-primary">image resolution is 100 x 100</label>
                            <input type="hidden" id="current_image" name="current_image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Website Link:<span class="tx-danger">*</span></label>
                        <input class="form-control" id="website_link" name="website_link" type="text" placeholder="Enter Website Link"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-info bg-purple tx-white closeBtn" onclick="window.location.reload();" > Close </button>
                    <button type="submit" class="btn-sm btn-success addBtn">Submit</button>
                </div>
            </form>
        </div>
      </div>
  </div>
{{-- End Add Modal  --}}
<!-- Reject Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content delete-popup">
                <div class="modal-header">
                    <h4 class="modal-title tx-danger">Delete Company</h4>
                </div>
                <form action="javascript:;" id="delete-form">
                    <div class="modal-body">
                        Are you sure you want to delete this Company?
                        <input type="hidden" name="company_id" id="companyId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default bg-purple tx-white closeBtn" onclick="window.location.reload();">Cancel</button>
                        <button type="button" id="deleteBtn" class="btn btn-danger reject-class">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- End Reject Modal -->    
@endsection
@section('scripts')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment-with-locales.min.js"></script>
<script type="text/javascript">
    $(function () {

      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: "{{ route('companies') }}",
            data: function (d) {
                  d.search = $('input[type="search"]').val()
              }
          },
          columns: [
              {"data": 'DT_RowIndex',orderable: false,searchable: false},
              {data: 'name', name: 'name'},
              {data: 'logo', name: 'logo'},
              {data: 'website', name: 'website'},
              {data: 'action', name: 'action'},
          ]
      });

      $('.filter').change(function(){
          table.draw();
      });
    });
</script>
<script>
$(document).ready(function(){
    const url = '{{url("/")}}';
    //Delete Function
    $('body').on('click','.openDelModal',function(){
        let id = $(this).attr('data-id');
        $('#companyId').val(id);
        $('#deleteModal').modal()
    });
    $('body').on('click','#deleteBtn',function(){
        let id = $('#companyId').val();
        $.ajax({
            url : url+"/delete-company/"+id,
            type : "GET",
            processData : false,
            async : true,
            header : {
                "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
            },
            success : function(data){
                if(data.status === true){
                    toastr["success"](data.response)
                    setTimeout(() => {
                        window.location.href=""
                    },500);
                }else{
                    toastr["error"](data.response);
                }
            }
        })
        return false
    })
    //Edit  Funcion
    $('body').on('click','.edit-button',function(){
        let dataId = $(this).attr("data-id") ;
        $.ajax({
            url : url+"/edit-company/"+dataId,
            type : "GET",
            processData : false,
            async : true,
            header : {
                "X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr('content')
            },
            success : function(data){
                if(data.status === true){
                    $('#myModalLabel').text('Edit Company');
                    $('#company_id').val(data.response.id);
                    $('#name').val(data.response.name);
                    $('#website_link').val(data.response.website);
                    $(".image-class").css("display","block");
                    $('.image-display').attr('src',url+data.response.logo);
                    $('#addModal').modal();
                }else{
                    toastr["error"](data.response);
                }
            }
        })
        return false
    })
    //Adding function
    $('#add-company').validate({
        normalizer : function(value){
            return $.trim(value)
        },
        ignore: [],
        rules : {
            name : {
                required : true,
                maxlength : 50
            },
            website_link : {
                required : true,
            },
        },
        messages : {
            name : {
                required : "Enter Company Name",
                maxlength : "Company Name Must be not more than 50 characters"
            },
            website_link : {
                required : "Please provide a website link",
            },
        },
        submitHandler : function(form){
            var form = document.getElementById('add-company');
            var formData = new FormData(form)
            $('.closeBtn').prop('disabled', true)
            $('.addBtn').prop('disabled', true)
            $.ajax({
                url  : '{{ route("add_companies") }}',
                type : "POST",
                data : formData,
                processData: false,
                dataType: "json",
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                async: true,
                headers : {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(".addBtn").text("Processing..");
                },
                success : function(data){
                    $('.addBtn').text("Submit")
                    $('.closeBtn').prop('disabled', false)
                    $('.addBtn').prop('disabled', false)
                    if(data.status == 1){
                        toastr["success"](data.response)
                        setTimeout(() => {
                            window.location.href=""
                        },1000);
                    }else{
                        var html =""
                        $.each(data.response,function(key,value){
                            html += value + '</br>';
                        });
                        toastr["error"](html);
                    }
                }
            })
        }
    })
})
</script>
@endsection