<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1>{{ $breadcrumb->title }}</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          @if (isset($breadcrumb->list) && is_array($breadcrumb->list))
            @foreach ($breadcrumb->list as $key => $value)
              @if (isset($value['title']) && is_string($value['title'])) 
                <li class="breadcrumb-item {{ $key == count($breadcrumb->list) - 1 ? 'active' : '' }}">
                  {{ htmlspecialchars($value['title'], ENT_QUOTES, 'UTF-8') }}
                </li>
              @endif
            @endforeach
          @else
            <li class="breadcrumb-item">No breadcrumbs available</li>
          @endif
        </ol>
      </div>
    </div>
  </div>
</section>