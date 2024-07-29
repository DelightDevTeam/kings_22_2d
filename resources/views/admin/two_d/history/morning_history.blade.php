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
      <h5 class="mb-0">2D (12:1) မှတ်တမ်း Dashboards</h5>
      {{-- <p class="text-sm mb-0">
                    A lightweight, extendable, dependency-free javascript HTML table plugin.
                  </p> --}}
     </div>
     <div class="ms-auto my-auto mt-lg-0 mt-4">
      <div class="ms-auto my-auto">
       {{-- <a href="" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; New Product</a> --}}
       {{-- <button type="button" class="btn btn-outline-primary btn-sm mb-0 py-2" data-bs-toggle="modal"
        data-bs-target="#import">
        +&nbsp; New Permission
       </button> --}}
       
       <button class="btn btn-outline-primary btn-sm export mb-0 mt-sm-0 mt-1 py-2" data-type="csv" type="button"
        name="button">Export</button>
      </div>
     </div>
    </div>
   </div>
   <div class="table-responsive">
    <table class="table table-flush" id="permission-search">
      <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Bet Digit</th>
                    <th>Sub Amount</th>
                    <th>Session</th>
                    <th>Match Status</th>
                    <th>PlayDate</th>
                    <th>PlayTime</th>
                    {{-- <th>User Phone</th> --}}
                    {{-- <th>Lottery Slip No</th> --}}
                    {{-- <th>Lottery Total Amount</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item['user_name'] }}</td>
                        <td>{{ $item['bet_digit'] }}</td>
                        <td>{{ $item['sub_amount'] }}</td>
                        <td>{{ $item['session'] }}</td>
                        <td>{{ $item['match_status'] }}</td>
                        <td>{{ $item['play_date'] }}</td>
                        <td>{{ $item['play_time'] }}</td>
                        

                        {{-- <td>{{ $item['user_phone'] }}</td> --}}
                        {{-- <td>{{ $item['lottery_slip_no'] }}</td> --}}
                        {{-- <td>{{ $item['lottery_total_amount'] }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
    </table>
   </div>
   
  </div>
  <div class="card mt-2">
    <div class="card-header">
     <p class="text-center">Total Amount for Morning</p>
    </div>
    <div class="card-body">
     <div>
           <h4 class="text-center">Total Sub Amount: {{ $totalSubAmount }}</h4>
     </div>
    </div>
   </div>
 </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
{{-- <script>
    const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
      searchable: true,
      fixedHeight: true
    });
  </script> --}}
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
