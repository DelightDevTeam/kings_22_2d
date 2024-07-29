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
            <h5 class="mb-0">Agent List Dashboards</h5>
          </div>
          <div class="ms-auto my-auto mt-lg-0 mt-4">
            <div class="ms-auto my-auto">
              <a href="{{ route('admin.agent.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Create 
                Agent</a>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-flush" id="users-search">
          <thead class="thead-light">
            <th>#</th>
            <th>AgentName</th>
            <th>Name</th>
            <th>Status</th>
            <th>Main Balance</th>
            <th>Note</th>
            <th>Action</th>
            <th>Transfer</th>
          </thead>
          <tbody>\
            {{-- kzt --}}
            @if(isset($users))
            @if(count($users)>0)
            @foreach ($users as $user)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>
                <span class="d-block">{{ $user->name }}</span>

              </td>
              <td>{{$user->user_name}}</td>
              <td>
              <small class="badge bg-gradient-{{ $user->status == 1 ? 'success' : 'danger' }}">{{ $user->status == 1 ? "active" : "inactive" }}</small>
            
              </td>
              <td>{{ number_format($user->main_balance) }}</td>
              <td>{{ $user->note}}</td>
              <td>
              @if ($user->status == 1)
                <a href="{{ route('admin.agent.getBan', $user->id)}}" class="me-2" href="#" data-bs-toggle="tooltip" data-bs-original-title="Active Player">
                  <i class="fas fa-user-check text-success" style="font-size: 20px;"></i>
                </a>
                @else
                <a onclick="event.preventDefault(); document.getElementById('banUser-{{ $user->id }}').submit();" class="me-2" href="#" data-bs-toggle="tooltip" data-bs-original-title="InActive Player">
                  <i class="fas fa-user-slash text-danger" style="font-size: 20px;"></i>
                </a>
                @endif
                <form class="d-none" id="banUser-{{ $user->id }}" action="{{ route('admin.agent.makeBan', $user->id) }}" method="post">
                  @csrf
                </form>
                <a class="me-1" href="{{ route('admin.agent.getChangePassword', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Change Password">
                  <i class="fas fa-lock text-info" style="font-size: 20px;"></i>
                </a>
                <a class="me-1" href="{{ route('admin.agent.edit', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Edit Agent">
                  <i class="fas fa-pen-to-square text-info" style="font-size: 20px;"></i>
                </a>
              </td>
              <td>
                <a href="{{ route('admin.agent.getCashIn', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Deposit To Agent" class="btn btn-info btn-sm">
                <i class="fas fa-plus text-white me-1"></i>Dep
                </a>
                <a href="{{ route('admin.agent.getCashOut', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="WithDraw To Agent" class="btn btn-info btn-sm">
                <i class="fas fa-minus text-white me-1"></i>
                  WDL
                </a>
                <a href="{{ route('admin.logs', $user->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Agent logs" class="btn btn-info btn-sm">
                  <i class="fas fa-right-left text-white me-1"></i>
                  Logs
                </a>

              </td>
            </tr>
            @endforeach
            @else
            <tr>
              <td col-span=8>
                There was no Agents.
              </td>
            </tr>
            @endif
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

<script>
  if (document.getElementById('users-search')) {
    const dataTableSearch = new simpleDatatables.DataTable("#users-search", {
      searchable: true,
      fixedHeight: false,
      perPage: 7
    });

  };
</script>

</script>
@endsection