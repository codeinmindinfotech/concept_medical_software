@props([
  'pageTitle',
  'breadcrumbs' => [],
  'backUrl' => null,
  'isListPage' => false, // New prop to determine if it's a list page
])

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-4 border-bottom">
  <div>
    <h1 class="h4 mb-2 mb-md-0">{{ $pageTitle }}</h1>
    @if(count($breadcrumbs))
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '›';">
          @foreach($breadcrumbs as $crumb)
            @if($loop->last)
              <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
            @else
              <li class="breadcrumb-item">
                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
              </li>
            @endif
          @endforeach
        </ol>
      </nav>
    @endif
  </div>

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
