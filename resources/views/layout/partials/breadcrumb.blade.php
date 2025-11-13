@props([
'pageTitle',
'breadcrumbs' => [],
'backUrl' => null,
'isListPage' => false, // New prop to determine if it's a list page
])
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-4 border-bottom">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">{{ $pageTitle }}</h3>
                @if(count($breadcrumbs))
                <ul class="breadcrumb">
                    @foreach($breadcrumbs as $crumb)
                    @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
                    @else
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                    </li>
                    @endif
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    @if($isListPage)
    <a href="{{ $backUrl }}" class="btn btn-success btn-sm mt-2 mt-md-0">
        <i class="fa fa-plus me-1"></i> Add New
    </a>
    @elseif($backUrl)
    <a href="{{ $backUrl }}" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
        <i class="fa fa-arrow-left me-1"></i> Back
    </a>
    @endif
</div>

