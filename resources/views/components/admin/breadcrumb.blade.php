{{-- <!-- Breadcrumb -->
<div class="breadcrumb-bar-two">
    <div class="container">
        <div class="row align-items-center inner-banner">
            <div class="col-md-12 col-12 text-center">
                <h2 class="breadcrumb-title">{{ $title }}</h2>
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">{{ $li_1 }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb --> --}}

<!-- Breadcrumb -->
<div class="breadcrumb-bar" style="padding: 70px 0 2px;">
    <div class="container">
        <div class="row align-items-center inner-banner">
            <div class="col-md-12 col-12 text-center">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('index')}}"><i class="isax isax-home-15"></i></a></li>
                        @if(!Route::is(['about-us','calendar','components','contact-us','faq','invoice-view','pricing','privacy-policy','terms-condition','video-call','voice-call','speciality','hospitals']))
                        <li class="breadcrumb-item" aria-current="page">{{ $title }}</li>
                        @endif
                        <li class="breadcrumb-item active">{{ $li_1 }}</li>
                    </ol>
                   
                    <h2 class="breadcrumb-title">{{ $li_2 }}</h2>
                  
                </nav>
            </div>
        </div>
    </div>
    <div class="breadcrumb-bg">
        <img src="{{URL::asset('assets/img/bg/breadcrumb-bg-01.png')}}" alt="img" class="breadcrumb-bg-01">
        <img src="{{URL::asset('assets/img/bg/breadcrumb-bg-02.png')}}" alt="img" class="breadcrumb-bg-02">
        <img src="{{URL::asset('assets/img/bg/breadcrumb-icon.png')}}" alt="img" class="breadcrumb-bg-03">
        <img src="{{URL::asset('assets/img/bg/breadcrumb-icon.png')}}" alt="img" class="breadcrumb-bg-04">
    </div>
</div>
<!-- /Breadcrumb -->