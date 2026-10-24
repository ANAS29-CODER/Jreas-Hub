@extends('layouts.frontend')

@section('title', 'University Trainings')

@section('content')
  <section class="courses-banner ">
      <div class="container">
        <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
          <div class="col-lg-12">
            <h1>University Trainings</h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">University Trainings</li>
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
              <a href="{{ route('trainings.index') }}" class="nav-link rounded-pill px-4 {{ $selectedCategory ? '' : 'active' }}">All</a>
            </li>
            @foreach ($categories as $category)
              <li class="nav-item">
                <a href="{{ route('trainings.index', ['category' => $category->id]) }}" class="nav-link rounded-pill px-4 {{ $selectedCategory === $category->id ? 'active' : '' }}">
                  {{ $category->name }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="row g-4">
        @forelse ($trainings as $training)
          <div class="col-md-6 col-lg-3">
            <div class="card course-card border-0 shadow-sm rounded-4 p-3 h-100 position-relative">
              <div class="position-relative mb-3">
                <img src="{{ $training->thumbnail ? asset('storage/' . $training->thumbnail) : asset('frontend-assets/img/service.png') }}" alt="{{ $training->title }}" class="card-img-top rounded-4 object-fit-cover" style="height: 180px;">
                <span class="badge bg-white text-success shadow-sm position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill fw-semibold d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                  <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> {{ $training->available_seats > 0 ? 'Open for Enrollment' : 'Enrollment Closed' }}
                </span>
              </div>
              <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div>
                  <h5 class="card-title fw-bold text-dark mb-1 fs-5">{{ $training->title }}</h5>
                  <p class="card-text text-muted small mb-3">{{ $training->university }} &middot; {{ $training->department }}</p>

                  <div class="bg-light p-2.5 rounded-3 mb-3 d-flex justify-content-around align-items-center" style="font-size: 0.8rem;">
                    <div class="d-flex align-items-center gap-2">
                      <i class="fa-solid fa-chair" style="color: #146666;"></i>
                      <div>
                        <div class="text-muted" style="font-size: 0.7rem;">Seats</div>
                        <div class="fw-bold text-dark">{{ $training->available_seats }}</div>
                      </div>
                    </div>
                    <div class="vr text-muted opacity-25"></div>
                    <div class="d-flex align-items-center gap-2">
                      <i class="fa-regular fa-calendar" style="color: #146666;"></i>
                      <div>
                        <div class="text-muted" style="font-size: 0.7rem;">Starts</div>
                        <div class="fw-bold text-dark">{{ $training->start_date?->format('M j') }}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <a href="{{ route('trainings.show', $training) }}" class="text-decoration-none fw-semibold course-link d-flex align-items-center gap-2 mt-auto pt-2" style="color: #146666;">
                  Learn more <i class="fa-solid fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        @empty
          <p class="text-muted">No trainings available yet.</p>
        @endforelse
      </div>
    </div>
  </section>
@endsection
