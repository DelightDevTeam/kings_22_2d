@extends('admin_layouts.app')
@section('styles')

@endsection
@section('content')

          <div class="row mb-3">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
              <div class="card">
                <div class="card-header p-3 pt-2 bg-transparent">
                  <div class="icon icon-lg icon-shape bg-gradient-success shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
                    <i class="fas fa-wallet text-white"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">2D Daily Income</p>
                    <h4 class="mb-0">{{ number_format($dailyTotal) }} <small>MMK</small></h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
              <div class="card">
                <div class="card-header p-3 pt-2 bg-transparent">
                  <div class="icon icon-lg icon-shape bg-info shadow-primary shadow text-center border-radius-xl mt-n4 position-absolute">
                    <i class="fas fa-wallet"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">2D Weekly Income</p>
                    <h4 class="mb-0">{{ number_format($weeklyTotal) }} <small>MMK</small></h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
              <div class="card">
                <div class="card-header p-3 pt-2 bg-transparent">
                  <div class="icon icon-lg icon-shape bg-gradient-warning shadow-success text-center border-radius-xl mt-n4 position-absolute">
                    <i class="fas fa-wallet"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize ">2D Monthly Income</p>
                    <h4 class="mb-0 ">{{ number_format($monthlyTotal) }} <small>MMK</small></h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
              <div class="card">
                <div class="card-header p-3 pt-2 bg-transparent">
                  <div class="icon icon-lg icon-shape bg-gradient-danger shadow-info text-center border-radius-xl mt-n4 position-absolute">
                    <i class="fas fa-wallet"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize ">2D Yearly Income </p>
                    <h4 class="mb-0 ">{{ number_format($yearlyTotal) }} <small>MMK</small></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {{-- 2d income end  --}}
          
          
          {{-- second row start --}}
          <div class="row mt-5">
            {{-- session two reset start 1 --}}
            <div class="col-lg-6 col-md-6 col-sm-6 mb-5">
              {{-- <div class="card  mb-2 p-3">
                <div class="d-flex mt-n2">
                          <div class="avatar avatar-xl bg-info border-radius-xl p-2 mt-n4">
                              <i class="fas fa-rotate fa-2x"></i>   
                            </div>
                            <div class="ms-3 my-auto">
                                <h6 class="mb-0"> 2D Session Reset</h6>
                                <div class="avatar-group mt-4">
                                    <form action="{{ route('admin.SessionReset') }}" method="POST">
                                      @csrf
                                      <button class="btn btn-primary" type="submit">Reset</button>
                                  </form>
                                </div>
                            </div>
                        </div>

                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                  <p class="mb-0"><span class="text-success text-sm font-weight-bolder">ပွဲချိန်ပြီး တခုပြီးတိုင်း  </span>၁၅ မိနစ်အတွင်း လုပ်ပေးရပါမည်။</p>
                </div>
              </div> --}}
            </div>
            {{-- session reset 1 end --}}
          </div>
          
        
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{ asset('admin_app/assets/js/plugins/chartjs.min.js')}}"></script>
{{-- pie chart --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js">
</script>
<script src="{{ asset('admin_app/assets/js/dashboard.js')}}"></script>
<script src="{{ asset('admin_app/assets/js/v_1_dashboard.js')}}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    @if(session('SuccessRequest'))
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: '{{ session("SuccessRequest") }}',
      timer: 3000,
      showConfirmButton: false
    });
    @endif

    // If you want to show an error or other types of alerts, you can add more conditions here
    @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: '{{ session("error") }}'
    });
    @endif
});

// For the reset confirmation, you can replace the native confirm with SweetAlert
$('form').on('submit', function(e) {
    e.preventDefault(); // prevent the form from submitting immediately
    var form = this;
    Swal.fire({
        title: 'Are you sure you want to reset?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reset it!'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // submit the form if confirmed
        }
    });
});


</script>

{{-- first chart end --}}
@endsection
