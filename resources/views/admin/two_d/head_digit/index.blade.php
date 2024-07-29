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
@endsection
@section('content')
<div class="row mt-4">
 <div class="col-12">
  <div class="card">
   <!-- Card header -->
   <div class="card-header pb-0">
    <div class="d-lg-flex">
     <div>
      <h5 class="mb-0">2D ထိပ်စီးသုံးလုံးပိတ်ရန် -  Dashboards</h5>

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
   <div class="card-body">
    <div class="ms-auto my-auto mt-lg-0 mt-4">
      <div class="ms-auto my-auto">
      
       <form class="multisteps-form__form" action="{{ route('admin.HeadClosestore') }}" method="post">
            @csrf
            <div class="input-group input-group-dynamic mb-3">
             <label class="form-label" for="one">Head Digit One</label>
             <input type="text" class="form-control" name="digit_one" onfocus="focused(this)" onfocusout="defocused(this)">
            </div>
            <div class="input-group input-group-dynamic mb-3">
             <label class="form-label" for="two">Head Digit two</label>
             <input type="text" class="form-control" name="digit_two" onfocus="focused(this)" onfocusout="defocused(this)">
            </div>
            <div class="input-group input-group-dynamic mb-3">
             <label class="form-label" for="three">Head Digit three</label>
             <input type="text" class="form-control" name="digit_three" onfocus="focused(this)" onfocusout="defocused(this)">
            </div>
            <div class="modal-footer">
             <button type="submit" class="btn bg-gradient-primary btn-sm">Save Head Digit</button>
            </div>
           </form>
      
      </div>
     </div>

     
   </div>
   <div class="table-responsive">
    <table class="table table-flush" id="permission-search">
     <thead class="thead-light">
      <tr>
       <th>#</th>
       <th>Head Digit One</th>
        <th>Head Digit Two</th>
         <th>Head Digit Three</th>
       <th>Created At</th>
       <th>Action</th>
      </tr>
     </thead>
     <tbody>
      @foreach($digits as $key => $digit)
      <tr>
       <td class="text-sm font-weight-normal">{{ ++$key }}</td>
       <td class="text-sm font-weight-normal">{{ $digit->digit_one }}</td>
        <td class="text-sm font-weight-normal">{{ $digit->digit_two }}</td>
         <td class="text-sm font-weight-normal">{{ $digit->digit_three }}</td>
       <td class="text-sm font-weight-normal">{{ $digit->created_at->format('F j, Y') }}</td>
       <td>
        <form class="d-inline" action="{{ route('admin.digit-2-close-head.destroy', $digit->id) }}" method="POST">
         @csrf
         @method('DELETE')
         <button type="submit" class="transparent-btn" data-bs-toggle="tooltip"
          data-bs-original-title="Delete Head Digit">
          <i class="material-icons text-secondary position-relative text-lg">delete</i>
         </button>

        </form>
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

<script>
if (document.getElementById('permission-search')) {
 const dataTableSearch = new simpleDatatables.DataTable("#permission-search", {
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

@endsection