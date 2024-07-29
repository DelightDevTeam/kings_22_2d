@extends('admin_layouts.app')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" rel="stylesheet">
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

    .custom-form-group {
        margin-bottom: 20px;
    }

    .custom-form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    .custom-form-group input,
    .custom-form-group select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e1e1e1;
        border-radius: 5px;
        font-size: 16px;
        color: #333;
    }

    .custom-form-group input:focus,
    .custom-form-group select:focus {
        border-color: #d33a9e;
        box-shadow: 0 0 5px rgba(211, 58, 158, 0.5);
    }

    .submit-btn {
        background-color: #d33a9e;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
    }

    .submit-btn:hover {
        background-color: #b8328b;
    }
</style>
@endsection
@section('content')
<div class="row mt-4">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h5>2D InterNet Prize Digit Create</h5>
            </div>

            @php
            use Carbon\Carbon;
            $currentTime = Carbon::now();
            $start9Time = Carbon::parse('8:00');
            $end12Time = Carbon::parse('8:10'); //close time 11:45AM
            $start2Time = Carbon::parse('16:30');
            $end4Time = Carbon::parse('23:45'); //close time 3:45PM
            @endphp
            <form action="{{ route('admin.internet-2d-digits.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="custom-form-group ms-3 mx-3">
                            <label for="prize_no">InterNet Morning Prize Number</label>
                            <input type="text" name="internet_digit" id="internet_digit" class="form-control" placeholder="InterNet Digit">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col ms-4">
                        {{-- button --}}
                        <div class="form-group">
                            {{-- @if ($currentTime->between($start9Time, $end12Time)) --}}
                            <button type="submit" class="btn btn-primary">Create</button>
                            {{-- @else
                            ထွက်ဂဏန်းထဲ့၍ မရသေးပါ | မနက်ပိုင်း Modern ထွက်ဂဏန်းထဲ့ရန် အချိန် 9:30 - မှ - 10 - 00 မိနစ်အတွင်း ထဲ့ရပါမည်
                            @endif --}}
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card mt-3">
            <!-- Card header -->
            <div class="card-header pb-0">
                <div>
                    <h5 class="mb-0">2D Internet Morning Prize Digit Create Dashboards</h5>
                </div>
                <div class="d-lg-flex mt-2">
                    {{-- <div class="ms-auto my-auto mt-lg-0">
                        <div class="ms-auto my-auto">
                            <a href="{{ route('admin.users.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Create New</a>
                            <button class="btn btn-outline-primary btn-sm export mb-0 mt-sm-0 mt-1" data-type="csv" type="button" name="button">Export</button>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-flush" id="twod-search">
                    <thead class="thead-light">
                        <th>#</th>
                        {{-- <th>Lottery ID</th> --}}
                        <th>InterNet Prize Number</th>
                        <th>Session</th>
                        <th>OpenTime</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @if($morningData)
                        <tr>
                            <td>{{ $morningData->id }}</td>
                            <td>{{ $morningData->internet_digit }}</td>
                            <td>{{ $morningData->session }}</td>
                            <td>{{ $morningData->open_time }}</td>
                            <td>{{ $morningData->created_at->format('F j, Y') }}</td>
                             <td>
                             <form class="d-inline" action="{{ route('admin.internet-2d-digits.destroy', $morningData->id) }}" method="POST">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="transparent-btn" data-bs-toggle="tooltip" data-bs-original-title="Delete Role">
                              <i class="material-icons text-secondary position-relative text-lg">delete</i>
                             </button>
                            </form>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="4" class="text-center">No Data Found</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h5>2D InterNet Evening Prize Digit Create</h5>
            </div>
            <form action="{{ route('admin.internet-2d-digits.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="custom-form-group ms-3 mx-3">
                            <label for="prize_no">InterNet Evening Prize Number</label>
                            <input type="text" name="internet_digit" id="internet_digit" class="form-control" placeholder="Internet Digit">
                        </div>
                        {{-- <input type="hidden" name="session" value="evening"> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col ms-4">
                        {{-- button --}}
                        <div class="form-group">
                            @if ($currentTime->between($start2Time, $end4Time))
                            <button type="submit" class="btn btn-primary">Create</button>
                            @else
                            ထွက်ဂဏန်းထဲ့၍ မရသေးပါ | ညနေပိုင်း ထွက်ဂဏန်းထဲ့ရန် အချိန် 2:00 - မှ - 2 - 30 မိနစ်အတွင်း ထဲ့ရပါမည်
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card mt-3">
            <div class="card-header pb-0">
                <div>
                    <h5 class="mb-0">2D InterNet Evening Prize Digit Create Dashboards</h5>
                </div>
                {{-- <div class="d-lg-flex mt-2">
                    <div class="ms-auto my-auto mt-lg-0">
                        <div class="ms-auto my-auto">
                            <a href="{{ route('admin.users.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Create New</a>
                            <button class="btn btn-outline-primary btn-sm export mb-0 mt-sm-0 mt-1" data-type="csv" type="button" name="button">Export</button>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="table-responsive">
                <table class="table table-flush" id="search">
                    <thead class="thead-light">
                        <th>#</th>
                        {{-- <th>Lottery ID</th> --}}
                        <th>Modern Prize Number</th>
                        <th>Session</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @if($eveningData)
                        <tr>
                            <td>{{ $eveningData->id }}</td>
                            <td>{{ $eveningData->internet_digit }}</td>
                            <td>{{ $eveningData->session }}</td>
                             <td>{{ $eveningData->created_at->format('F j, Y') }}</td>
                             <td>
                             <form class="d-inline" action="{{ route('admin.internet-2d-digits.destroy', $eveningData->id) }}" method="POST">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="transparent-btn" data-bs-toggle="tooltip" data-bs-original-title="Delete Role">
                              <i class="material-icons text-secondary position-relative text-lg">delete</i>
                             </button>
                            </form>
                            </td>

                        </tr>
                        @else
                        <tr>
                            <td colspan="4" class="text-center">No Data Found</td>
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
<script src="{{ asset('admin_app/assets/js/plugins/datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    });
@endif
<script>
    if (document.getElementById('twod-search')) {
        const dataTableSearch = new simpleDatatables.DataTable("#twod-search", {
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
    if (document.getElementById('search')) {
        const dataTableSearch = new simpleDatatables.DataTable("#search", {
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
@endsection