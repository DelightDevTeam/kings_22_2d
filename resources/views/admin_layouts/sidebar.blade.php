<div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">

  <ul class="navbar-nav">
    {{-- kzt --}}
    <li class="nav-item active">
      <a class="nav-link text-white " href="{{ route('home') }}" style="font-szie:large;">
        <span class="sidenav-mini-icon"> <i class="material-icons-round opacity-10">dashboard</i> </span>
        @if(Auth::user()->hasRole('Admin'))
        <span class="sidenav-normal ms-2 ps-1">Admin Dashboard</span>
        @elseif(Auth::user()->hasRole('Agent'))
        <span class="sidenav-normal ms-2 ps-1">Agent Dashboard</span>
        @elseif(Auth::user()->hasRole('Player'))
        <span class="sidenav-normal ms-2 ps-1">Player Dashboard</span>
        @endif
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('admin.profile.index')}}">
        <span class="sidenav-mini-icon"> <i class="fa-solid fa-user"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1"> Profile </span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('admin.report.index')}}">
        <span class="sidenav-mini-icon"> <i class="fa-solid fa-chart-column"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1"> Win/lose Report </span>
      </a>
    </li>
    @can('agent_index')
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('admin.agent.index')}}">
        <span class="sidenav-mini-icon"> <i class="fa-solid fa-user"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1">Agent List</span>
      </a>
    </li>
    @endcan
    @can('player_index')
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('admin.player.index')}}">
        <span class="sidenav-mini-icon"> <i class="fa-solid fa-user"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1">Player List</span>
      </a>
    </li>
    @endcan
    @can('payment_type')
        <li class="nav-item ">
            <a class="nav-link text-white " href="{{ route('admin.userPayment.index') }}">
              <span class="sidenav-mini-icon"> <i class="fa-solid fa-panorama"></i> </span>
              <span class="sidenav-normal  ms-2  ps-1">Bank Account</span>
            </a>
          </li>
    @endcan
    @can('withdraw_requests')
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('admin.agent.withdraw')}}">
        <span class="sidenav-mini-icon"> <i class="fa-solid fa-user"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1">WithDraw Requests</span>
      </a>
    </li>
    @endcan
    @can('deposit_requests')
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('admin.agent.deposit')}}">
        <span class="sidenav-mini-icon"> <i class="fa-solid fa-user"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1">Deposit Requests</span>
      </a>
    </li>
    @endcan
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('admin.transferLog')}}">
        <span class="sidenav-mini-icon"> <i class="fas fa-right-left"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1">Transfer Log</span>
      </a>
    </li>
    @can('admin_access')
      <li class="nav-item">
      <a class="nav-link text-white " href="{{ url('admin/active-user-online')}}">
        <span class="sidenav-mini-icon"> <i class="fas fa-right-left"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1">ActiveUserOnline</span>
      </a>
    </li>
    @endcan
        @can('admin_access')
      <li class="nav-item">
      <a class="nav-link text-white " href="{{ url('admin/user-logs')}}">
        <span class="sidenav-mini-icon"> <i class="fas fa-right-left"></i> </span>
        <span class="sidenav-normal  ms-2  ps-1">UserLogInLog</span>
      </a>
    </li>
    @endcan
    <hr class="horizontal light mt-0">
    @can('admin_access')
    <li class="nav-item">
      <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link text-white " aria-controls="dashboardsExamples" role="button" aria-expanded="false">
        <i class="material-icons py-2">settings</i>
        <span class="nav-link-text ms-2 ps-1">General Setup</span>
      </a>
      <div class="collapse " id="dashboardsExamples">
        <ul class="nav ">
        <li class="nav-item ">
            <a class="nav-link text-white " href="{{ route('admin.paymentType.index') }}">
              <span class="sidenav-mini-icon"> <i class="fa-solid fa-panorama"></i> </span>
              <span class="sidenav-normal  ms-2  ps-1"> Payment Type </span>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ route('admin.banners.index') }}">
              <span class="sidenav-mini-icon"> <i class="fa-solid fa-panorama"></i> </span>
              <span class="sidenav-normal  ms-2  ps-1"> Banner </span>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ route('admin.text.index') }}">
              <span class="sidenav-mini-icon"> <i class="fa-solid fa-panorama"></i> </span>
              <span class="sidenav-normal  ms-2  ps-1"> Banner Text </span>
            </a>
          </li>

          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ route('admin.promotions.index') }}">
              <span class="sidenav-mini-icon"> <i class="fas fa-gift"></i> </span>
              <span class="sidenav-normal  ms-2  ps-1"> Promotions </span>
            </a>
          </li>
          @can('3d_admin_access')
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ route('admin.gametypes.index') }}">
              <span class="sidenav-mini-icon">G</span>
              <span class="sidenav-normal  ms-2  ps-1"> GameType </span>
            </a>
          </li>
          @endcan
        </ul>
      </div>
    </li>
    @endcan
    @can('two_d_access')
    <li class="nav-item">
      <a data-bs-toggle="collapse" href="#profileExample" class="nav-link text-white" aria-controls="pagesExamples" role="button" aria-expanded="false">
        <i class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">settings</i>
        <span class="nav-link-text ms-2 ps-1">2D Control</span>
      </a>
      <div class="collapse show" id="pagesExamples">
        <ul class="nav">
          <li class="nav-item ">
            <div class="collapse " id="profileExample">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2-d-dashboard')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> Dashboard </span>
                  </a>
                </li>
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-users-with-agents')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> ထိုးသားများစီမံရန် </span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/two-d-settins') }}">
                    <span class="sidenav-mini-icon">2D</span>
                    <span class="sidenav-normal  ms-2  ps-1">Setting</span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                 <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/two-d-more-settings') }}">
                    <span class="sidenav-mini-icon">2D</span>
                    <span class="sidenav-normal  ms-2  ps-1">MoreSetting</span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2-d-all-winner')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1">ပေါက်သူများ </span>
                  </a>
                </li>
                @endcan

                @can('two_d_all_win')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2-d-agent-all-winner')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1">ပေါက်သူများ </span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                 <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-default-limit')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> DefaultLimit-သတ်မှတ်ရန် </span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-users-limit-cor')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> ထိုးသားဘရိတ်/ကော်-သတ်မှတ်ရန် </span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/close-2d-digit')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> စိတ်ကြိုက်ဂဏန်းပိတ်ရန် </span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/close-head-2d-digit')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> ထိပ်စီးသုံးလုံးပိတ်ရန် </span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ route('admin.moderns-digits.index')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> Modern ထွက်ဂဏန်းထဲ့ရန် </span>
                  </a>
                </li>
                @endcan
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ route('admin.internet-2d-digits.index')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> InterNet ထွက်ဂဏန်းထဲ့ရန် </span>
                  </a> 
                </li>
                @endcan
                @can('admin_acess')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-morning-history')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 12:1-မှတ်တမ်း </span>
                  </a>
                </li>
                @endcan
                @can('two_d_history')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-agent-morning-history')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 12:1-မှတ်တမ်း </span>
                  </a>
                </li>
                @endcan
            @can('admin_access')
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-morning-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">(12:1) Slip မှတ်တမ်း </span>
            </a>
          </li>
          @endcan
          @can('two_d_agent_slip_access')
           <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-morning-agent-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">(12:1) Slip မှတ်တမ်း </span>
            </a>
          </li>
          @endcan

          @can('two_d_agent_slip_access')
           <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-agentmorning-all-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">(12:1)All Slip မှတ်တမ်း </span>
            </a>
          </li>
          @endcan
          @can('admin_access')
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-morning-all-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">(12:1) AllSlip မှတ်တမ်း </span>
            </a>
          </li>
          @endcan
            @can('two_d_history')
            <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-agentmorning-legar')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 12:1-လယ်ဂျာ </span>
                  </a>
                </li>
            @endcan
            @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-morning-legar')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 12:1-လယ်ဂျာ </span>
                  </a>
                </li>
              @endcan
              @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2-d-morning-winner')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 12:1-ပေါက်သူများ </span>
                  </a>
                </li>
                @endcan
                 @can('morning_win')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2-d-agent-morning-winner')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 12:1-ပေါက်သူများ </span>
                  </a>
                </li>
                @endcan
                @can('two_d_history')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-agent-evening-history')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 4:30-မှတ်တမ်း </span>
                  </a>
                </li>
                @endcan
              @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-evening-history')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 4:30-မှတ်တမ်း </span>
                  </a>
                </li>
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-evening-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">  (4:30) Slip မှတ်တမ်း </span>
            </a>
          </li>
          
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-evening-all-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">  (4:30) AllSlip မှတ်တမ်း </span>
            </a>
          </li>
          @endcan
          @can('two_d_agent_slip_access')
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-agent-evening-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">  (4:30) Slip မှတ်တမ်း </span>
            </a>
          </li>
          
          <li class="nav-item ">
            <a class="nav-link text-white " href="{{ url('admin/2d-agent-evening-all-slip') }}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1">  (4:30) AllSlip မှတ်တမ်း </span>
            </a>
          </li>
          @endcan
          @can('admin_access')
          <li class="nav-item">
            <a class="nav-link text-white " href="{{ url('admin/2d-evening-legar')}}">
              <span class="sidenav-mini-icon"> 2D </span>
              <span class="sidenav-normal  ms-2  ps-1"> 4:30-လယ်ဂျာ </span>
            </a>
          </li>
          @endcan
                @can('two_d_history')
            <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2d-agentevening-legar')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 4:30-လယ်ဂျာ </span>
                  </a>
                </li>
            @endcan
            @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2-d-evening-winner')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 4:30-ပေါက်သူများ </span>
                  </a>
                </li>
            @endcan
                @can('evening_win')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/2-d-agent-evening-winner')}}">
                    <span class="sidenav-mini-icon"> 2D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> 4:30-ပေါက်သူများ </span>
                  </a>
                </li>
                @endcan

              </ul>
            </div>
          </li>
        </ul>
      </div>
    </li>
    @endcan
    
    
    @can('3d_admin_access')
    <li class="nav-item">
      <a data-bs-toggle="collapse" href="#three_d" class="nav-link text-white" aria-controls="pagesExamples" role="button" aria-expanded="false">
        <i class="material-icons-round {% if page.brand == 'RTL' %}ms-2{% else %} me-2{% endif %}">settings</i>
        <span class="nav-link-text ms-2 ps-1">3D Control</span>
      </a>
      <div class="collapse show" id="pagesExamples">
        <ul class="nav">
          <li class="nav-item ">
            <div class="collapse " id="three_d">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3-d-dashboard')}}">
                    <span class="sidenav-mini-icon"> 3D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> Dashboard </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-users-with-agents')}}">
                    <span class="sidenav-mini-icon"> 3D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> ထိုးသားများစီမံရန် </span>
                  </a>
                </li>
                @can('admin_access')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-settings') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">Setting</span>
                  </a>
                </li>
                
                 <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-more-setting') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">MoreSetting</span>
                  </a>
                </li>
                 <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-reports') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">Reports</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-users-limit-cor')}}">
                    <span class="sidenav-mini-icon"> 3D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> ထိုးသားဘရိတ်/ကော်-သတ်မှတ်ရန် </span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-close-digit') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">စိတ်ကြိုက်ဂဏန်းပိတ်ရန်</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-default-limits') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">Default-ဘရိတ်သတ်မှတ်ရန်</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-one-week-records') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">တပါတ်မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-all-history') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">All မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-one-week-slip') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">တပါတ်တွင်း Slip</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-slip-history') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">All Slip</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-first-winner') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">FirstPrize</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-first-prize') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">ဒဲ့ပေါက်</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-second-winner') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">SecondPrize</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-second-prize') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">ပတ်လယ်ပေါက်</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-third-winner') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">ThirdPrize</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-third-prize') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">သွဋ်ပေါက်</span>
                  </a>
                </li>

                @endcan
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-year-match-times')}}">
                    <span class="sidenav-mini-icon"> 3D </span>
                    <span class="sidenav-normal  ms-2  ps-1"> ပွဲစဉ်များ </span>
                  </a>
                </li>
                @can('three_d_agent_histroy')
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-one-week-records') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">တပါတ်မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-all-history') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">All-မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-one-week-slip') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">တပါတ် Slip-မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-slip-history') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">All Slip-မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-all-first-winner') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">All Win-မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-first-winner') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">ဒဲ့ပေါက်-မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-second-winner') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">ပတ်လယ်ပေါက်-မှတ်တမ်း</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white " href="{{ url('admin/3d-agent-third-winner') }}">
                    <span class="sidenav-mini-icon">3D</span>
                    <span class="sidenav-normal  ms-2  ps-1">သွဋ်ပေါက်-မှတ်တမ်း</span>
                  </a>
                </li>
                @endcan
                

              </ul>
            </div>
          </li>
        </ul>
      </div>
    </li>
    @endcan

    <li class="nav-item">
      <a href="{{ route('logout') }}" onclick="event.preventDefault();
      document.getElementById('logout-form').submit();" class="nav-link text-white">
        <span class="sidenav-mini-icon"> <i class="fas fa-right-from-bracket text-white"></i> </span>
        <span class="sidenav-normal ms-2 ps-1">Logout</span>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </li>
  </ul>