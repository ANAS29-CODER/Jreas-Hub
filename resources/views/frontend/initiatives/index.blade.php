@extends('layouts.frontend')

@section('title', 'Initiatives')

@section('content')
  <section class="courses-banner ">
      <div class="container">
        <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
          <div class="col-lg-12">
            <h1>Initiatives</h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">Initiatives</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
   </section>

  <section class="courses-section py-5">
    <div class="container">

      <div class="row align-items-center justify-content-between mb-4 g-3">
        <div class="col-lg-8">
          <ul class="nav nav-pills custom-filter-tabs gap-3">
            <li class="nav-item">
              <a href="{{ route('initiatives.index') }}" class="nav-link rounded-pill px-4 {{ $selectedType ? '' : 'active' }}">All</a>
            </li>
            @foreach ($types as $type)
              <li class="nav-item">
                <a href="{{ route('initiatives.index', ['type' => $type->value]) }}" class="nav-link rounded-pill px-4 {{ $selectedType === $type->value ? 'active' : '' }}">
                  {{ ucfirst($type->value) }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="row g-4">
        @forelse ($initiatives as $initiative)
          <div class="col-md-6 col-lg-4">
            <div class="card course-card border-0 shadow-sm rounded-4 p-3 h-100 position-relative">
              <div class="position-relative mb-3">
                <img src="{{ $initiative->thumbnail ? asset('storage/' . $initiative->thumbnail) : asset('frontend-assets/img/service.png') }}" alt="{{ $initiative->title }}" class="card-img-top rounded-4 object-fit-cover" style="height: 180px;">
                <span class="badge bg-white text-dark shadow-sm position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                  {{ ucfirst($initiative->type->value) }}
                </span>
              </div>
              <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div>
                  <h5 class="card-title fw-bold text-dark mb-1 fs-5">{{ $initiative->title }}</h5>
                  <p class="card-text text-muted small mb-3">{{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags((string) $initiative->description)), 90) }}</p>
                  <p class="text-muted small mb-3"><i class="fa-solid fa-user"></i> {{ $initiative->manager_name }}</p>
                </div>

                <a href="{{ route('initiatives.show', $initiative) }}" class="text-decoration-none fw-semibold course-link d-flex align-items-center gap-2 mt-auto pt-2" style="color: #146666;">
                  Learn more <i class="fa-solid fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        @empty
          <p class="text-muted">No initiatives available yet.</p>
        @endforelse
      </div>
    </div>
  </section>
@endsection
