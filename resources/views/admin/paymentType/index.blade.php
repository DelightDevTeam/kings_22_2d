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
            <h5 class="mb-0">PaymentType Listing</h5>

          </div>
          <div class="ms-auto my-auto mt-lg-0 mt-4">
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-flush" id="banners-search">
          <thead class="thead-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Image</th>
              <th>Banner</th>
              <th>Action</th>
            </tr>
          </thead>
<tbody>
    @if($paymentTypes && $paymentTypes->count() > 0)
        @foreach($paymentTypes as $type)
            <tr>
                <td class="text-sm font-weight-normal">{{ $loop->iteration }}</td>
                <td class="text-sm">{{ $type->paymentType->name ?? 'N/A' }}</td>
                <td>
                    <img src="{{ asset('assets/img/paymentType/' . ($type->paymentType->image ?? 'default.png')) }}" alt="" width="100px">
                </td>
                <td>
                    @foreach ($type->paymentImages ?? [] as $payment)
                        <img src="{{ asset('assets/img/paymentType/banners/' . ($payment->image ?? 'default.png')) }}" alt="" width="100px">
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('admin.paymentType.edit', $type->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Edit Bank">
                        <i class="material-icons-round text-secondary position-relative text-lg">mode_edit</i>
                    </a>
                    <form class="d-inline" action="{{ route('admin.paymentType.destroy', $type->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="transparent-btn" data-bs-toggle="tooltip" data-bs-original-title="Delete Banner">
                            <i class="material-icons text-secondary position-relative text-lg">delete</i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" class="text-center">No payment types found.</td>
        </tr>
    @endif
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
  if (document.getElementById('banners-search')) {
    const dataTableSearch = new simpleDatatables.DataTable("#banners-search", {
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
