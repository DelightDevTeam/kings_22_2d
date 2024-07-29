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
                            <h5 class="mb-0">
                                2D ပေါက်သူများစာရင်း Dashboards
                            </h5>

                        </div>
                        <div class="ms-auto my-auto mt-lg-0 mt-4">
                            <div class="ms-auto my-auto">
                                {{-- <a href="{{ route('admin.users.create') }}"
                                    class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Create New
                                    User</a> --}}
                                <button class="btn btn-outline-primary btn-sm export mb-0 mt-sm-0 mt-1" data-type="csv"
                                    type="button" name="button">Export</button>
                            </div>
                        </div>
                    </div>
                </div>
    @if (isset($error))
    <div class="alert alert-danger">
        {{ $error }}
    </div>
@else
        <div class="card mt1">
            <div class="card-header">
        <p style="color: #f5bd02" class="text-center">Total Prize Amount: {{ $totalPrizeAmount }}</p>

            </div>
        </div>
    <div class="table-responsive">

        <table class="table table-flush" id="users-search">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>User Phone</th>
                    <th>Bet Digit</th>
                    <th>Res Date</th>
                    <th>Res Time</th>
                    <th>Sub Amount</th>
                    <th>Win Amount</th>
                    <th>Prize Sent</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $index => $result)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $result->user_name }}</td>
                        <td>{{ $result->user_phone }}</td>
                        <td>{{ $result->bet_digit }}</td>
                        <td>{{ $result->res_date }}</td>
                        <td>{{ $result->res_time }}</td>
                        <td>{{ $result->sub_amount }}</td>
                        <td>{{ $result->sub_amount * 85 }}</td>
                        <td>{{ $result->prize_sent ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
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
@endsection
