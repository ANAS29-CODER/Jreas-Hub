@extends('layouts.frontend')

@section('title', 'Hands on Workshops')

@section('content')
  <section class="courses-banner ">
      <div class="container">
        <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
          <div class="col-lg-12">
            <h1>Hands on Workshops</h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">Hands on Workshops</li>
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
                <a href="{{ route('workshops.index') }}" class="nav-link rounded-pill px-4 {{ $selectedCategory ? '' : 'active' }}">All</a>
              </li>
              @foreach ($categories as $category)
                <li class="nav-item">
                  <a href="{{ route('workshops.index', ['category' => $category->id]) }}" class="nav-link rounded-pill px-4 {{ $selectedCategory === $category->id ? 'active' : '' }}">
                    {{ $category->name }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="row g-4">
          @forelse ($workshops as $workshop)
            <div class="col-md-6 col-lg-3">
              <div class="card course-card border-0 shadow-sm rounded-4 p-3 h-100 position-relative">

                <div class="position-relative mb-3">
                  <img src="{{ $workshop->thumbnail ? asset('storage/' . $workshop->thumbnail) : asset('frontend-assets/img/service.png') }}" alt="{{ $workshop->title }}" class="card-img-top rounded-4 object-fit-cover" style="height: 180px;">
                  <span class="badge bg-white text-dark shadow-sm position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                    {{ ucfirst($workshop->type->value) }}
                  </span>
                </div>

                <div class="card-body p-0 d-flex flex-column justify-content-between">
                  <div>
                    <h5 class="card-title fw-bold text-dark mb-1 fs-5">{{ $workshop->title }}</h5>
                    <p class="card-text text-muted small mb-3">{{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags((string) $workshop->description)), 80) }}</p>

                    <div class="bg-light p-2.5 rounded-3 mb-3 d-flex justify-content-around align-items-center" style="font-size: 0.8rem;">
                      <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-calendar" style="color: #146666;"></i>
                        <div>
                          <div class="text-muted" style="font-size: 0.7rem;">Date</div>
                          <div class="fw-bold text-dark">{{ $workshop->date?->format('M j, Y') }}</div>
                        </div>
                      </div>
                      <div class="vr text-muted opacity-25"></div>
                      <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-clock text-teal" style="color: #146666;"></i>
                        <div>
                          <div class="text-muted" style="font-size: 0.7rem;">Duration</div>
                          <div class="fw-bold text-dark">{{ $workshop->duration }} hours</div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <a href="{{ route('workshops.show', $workshop) }}" class="text-decoration-none fw-semibold course-link d-flex align-items-center gap-2 mt-auto pt-2" style="color: #146666;">
                    Learn more <i class="fa-solid fa-arrow-right"></i>
                  </a>

                </div>
              </div>
            </div>
          @empty
            <p class="text-muted">No workshops available yet.</p>
          @endforelse
        </div>

      </div>
    </section>
@endsection
