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
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
@endsection
@section('content')
<div class="row mt-4">
 <div class="col-12">
  <div class="card">
   <!-- Card header -->
   <div class="card-header pb-0">
    <div class="d-lg-flex">
     <div>
      <h5 class="mb-0">2D User's  Dashboards</h5>

     </div>
     <div class="ms-auto my-auto mt-lg-0 mt-4">
      <div class="ms-auto my-auto">
       {{-- <a href="{{ route('admin.roles.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; New Role</a> --}}
       <button class="btn btn-outline-primary btn-sm export mb-0 mt-sm-0 mt-1 py-1" data-type="csv" type="button"
        name="button">Export</button>
      </div>
     </div>
    </div>
   </div>
   <div class="table-responsive">
    <table class="table table-flush" id="roles-search">
     <thead class="thead-light">
            <tr>
                <th>User ID</th>
                <th>User Name</th>
                <th>User Phone</th>
                <th>Agent ID</th>
                <th>Creator</th>
                <th>Agent Phone</th>
                <th>2DLimit</th>
                <th>3DLimit</th>
                <th>2D Cor</th>
                <th>3D Cor</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->agent ? $user->agent->id : 'N/A' }}</td>
                    <td>{{ $user->agent ? $user->agent->name : 'N/A' }}</td>
                    <td>{{ $user->agent ? $user->agent->phone : 'N/A' }}</td>
                    <td>{{ $user->cor }}</td>
                    <td>{{ $user->limit3 }}</td>
                    <td>{{ $user->cor }}</td>
                    <td>{{ $user->cor3 }}</td>
                    <td>{{ $user->main_balance }}</td>
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
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
<script>
if (document.getElementById('roles-search')) {
 const dataTableSearch = new simpleDatatables.DataTable("#roles-search", {
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
$(document).ready(function() {
 $('.transparent-btn').on('click', function(e) {
  e.preventDefault();
  let form = $(this).closest('form');
  Swal.fire({
   title: 'Are you sure?',
   text: "You won't be able to revert this!",
   icon: 'warning',
   showCancelButton: true,
   confirmButtonText: 'Yes, delete it!',
   cancelButtonText: 'No, cancel!'
  }).then((result) => {
   if (result.isConfirmed) {
    form.submit();
   }
  });
 });
});
</script>
@if(session()->has('success'))
<script>
Swal.fire({
 icon: 'success',
 title: '{{ session('
 success ') }}',
 showConfirmButton: false,
 timer: 1500
})
</script>
@endif


@endsection
