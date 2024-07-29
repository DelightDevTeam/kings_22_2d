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
.table-custom {
            table-layout: fixed;
            width: 100%;
        }
        .table-custom th, .table-custom td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #000;
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
      <h5 class="mb-0">3D  လယ်ဂျာ  Dashboards
      </h5>

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
   <div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('admin.ThreedDefaultBreakupdate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="three_d_limit">Default Break Edit</label>
            <input type="number" name="three_d_limit" class="form-control" value="{{ $defaultBreak->three_d_limit }}" required min="0">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info btn-sm">Update Default Break</button>
        </div>
    </form>
</div>
   <div class="table-responsive">
    <table class="table table-flush table-custom table-bordered" id="roles-search">
     <thead class="thead-light">
             <tr>
                    @for ($i = 0; $i < 8; $i++)
                        <th>Number</th>
                        <th>Amount</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php 
                $limits = $defaultBreak->three_d_limit
                @endphp
                @forelse ($twoDigitData->chunk(8) as $chunk)
                    <tr>
                        @foreach ($chunk as $data)
                            <td>{{ $data['digit'] }}</td> 
                            <td>
                                @if($data['total_sub_amount'] >= $limits )
                                <p class="text-danger">
                                {{ $data['total_sub_amount'] }}
                                </p>
                                @else 
                                <p class="text-success">
                                {{ $data['total_sub_amount'] }}
                                </p>
                                @endif
                            </td>
                        @endforeach
                        @for ($i = $chunk->count(); $i < 8; $i++)
                            <td></td>
                            <td></td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="16" class="text-center">No data available</td> <!-- Adjusted colspan to 16 for 8 pairs of columns -->
                    </tr>
                @endforelse
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
        document.addEventListener('DOMContentLoaded', function() {
            const colors = ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', 
                            '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                            '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', 
                            '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
                            '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC', 
                            '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
                            '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680', 
                            '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
                            '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3', 
                            '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'];
            
            const headers = document.querySelectorAll('#roles-search thead th');
            headers.forEach(header => {
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                header.style.backgroundColor = randomColor;
            });
        });
    </script>
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
