@extends('admin_layouts.app')
@section('styles')
<style>
.transparent-btn {
 background: none;
 border: none;
 padding: 0;
 outline: none;
 cursor: pointer;
 box-shadow: none;
 appearance: none;
 /* For some browsers */
 
}
.user {
            display: flex;
            align-items: center;
        }
        .green-spotlight {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: green;
            margin-right: 10px;
        }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
@endsection
@section('content')
<div class="row mt-4">
 <div class="col-12">
  <div class="card">
   <!-- Card header -->
   <div class="card-header pb-0">
    <div class="d-lg-flex">
     <div>
      <h5 class="mb-0">Active Users Online Dashboards</h5>

     </div>
     <div class="ms-auto my-auto mt-lg-0 mt-4">
      <div class="ms-auto my-auto">
       {{-- <a href="{{ route('admin.users.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Create New
        User</a> --}}
       <button class="btn btn-outline-primary btn-sm export mb-0 mt-sm-0 mt-1" data-type="csv" type="button"
        name="button">Export</button>
      </div>
     </div>
    </div>
   </div>
   {{-- <div class="alert">
     @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Error Alert -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
   </div> --}}
   <div class="table-responsive">
    <table class="table table-flush" id="users-search">
     <thead class="thead-light">
      <th>AccountID</th>
      <th>UserName</th>
      <th>Phone</th>
      <th>WalletBlance</th>
      <th>MainBalance</th>
      <th>Create Date</th>
     </thead>
     <tbody>
      @foreach($activeUsers as $user)
      <tr>
        <td class="user">
                <div class="green-spotlight"></div>
                {{ $user->user_name }}
           </td>
       <td>{{ $user->name }}</td>
           <td>{{ $user->phone }}</td>
           <td>{{ $user->balanceFloat }}</td>
           <td>{{ $user->main_balance }}</td>
          <td class="text-sm font-weight-normal">{{ $user->created_at->format('F j, Y') }}
          </td>

      </tr>
            
        @endforeach
     </tbody>
    </table>
   </div>
  </div>
 </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    @if(session('toast_success'))
    Swal.fire({
      icon: 'success',
      title: 'Success! အကောင့်ဖွင့်ပေးမှူ့အောင်မြင်ပါသည်!',
      text: '{{ session('
      toast_success ') }}',
      timer: 3000,
      showConfirmButton: false
    });
    @endif
  });
</script>
{{-- <script>
    const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
      searchable: true,
      fixedHeight: true
    });
  </script> --}}
<script>
if (document.getElementById('users-search')) {
 const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
  searchable: true,
  fixedHeight: false,
  perPage: 7
 });

 document.querySelectorAll(".export").forEach(function(el) {
  el.addEventListener("click", function(e) {
   var type = el.dataset.type;

   var data = {
    type: type,
    filename: "material-" + type,
   };

   if (type === "csv") {
    data.columnDelimiter = "|";
   }

   dataTableSearch.export(data);
  });
 });
};
</script>
<script>
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
 return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>

<script>
  var errorMessage = @json(session('error'));
  var successMessage = @json(session('success'));
  var url = 'https://tttgamingmm.com/login';
  var name = @json(session('username'));
  var phone = @json(session('phone'));
  var pw = @json(session('password'));

  @if(session()->has('success'))
  Swal.fire({
    title: successMessage,
    icon: "success",
    showConfirmButton: false,
    showCloseButton: true,
    html: `
      <table class="table table-bordered" style="background:#eee;">
        <tbody>
          <tr>
            <td>username</td>
            <td id="tusername"> ${name}</td>
          </tr>
          <tr>
            <td>username</td>
            <td id="tuserphone"> ${phone}</td>
          </tr>
          <tr>
            <td>pw</td>
            <td id="tpassword"> ${pw}</td>
          </tr>
          <tr>
            <td>url</td>
            <td id=""> ${url}</td>
          </tr>
          <tr>
            <td></td>
            <td><a href="#" onclick="copy()" class="btn btn-sm btn-primary">copy</a></td>
          </tr>
        </tbody>
      </table>
    `
  });
  @elseif(session()->has('error'))
  Swal.fire({
    icon: 'error',
    title: errorMessage,
    showConfirmButton: false,
    timer: 1500
  })
  @endif

  function copy() {
    var username = $('#tusername').text();
     var userphone = $('#tuserphone').text();
    var password = $('#tpassword').text();
    var copy = "url : " + url + "\nusername : " + username +  "\nusername : " + userphone + "\npw : " + password;
    copyToClipboard(copy);
  }

  function copyToClipboard(v) {
    var $temp = $("<textarea>");
    $("body").append($temp);
    var html = v;
    $temp.val(html).select();
    document.execCommand("copy");
    $temp.remove();
  }
</script>

@endsection