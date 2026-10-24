@extends('layouts.frontend')

@section('title', $initiative->title)

@section('content')
   <section class="courses-banner ">
      <div class="container">
        <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
          <div class="col-lg-12">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('initiatives.index') }}" class="text-decoration-none text-muted">Initiatives</a></li>
                <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">{{ $initiative->title }}</li>
              </ol>
            </nav>
            <h1>{{ $initiative->title }}</h1>
          </div>
        </div>
      </div>
   </section>

   <section class="training-details py-5">
      <div class="container">
        <div class="row g-4 align-items-start">

          <div class="col-lg-8">
            <div class="training-details-overview mb-5">
              <h2 class="training-overview-title fw-bold text-dark mb-3">About This Initiative</h2>
              <div class="training-overview-text text-muted lh-lg">
                {!! $initiative->description !!}
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="training-details p-3 rounded-4 shadow-sm bg-white border">

              <div class="training-details-img-wrapper position-relative mb-3">
                <img src="{{ $initiative->thumbnail ? asset('storage/' . $initiative->thumbnail) : asset('frontend-assets/img/service.png') }}" alt="{{ $initiative->title }}" class="img-fluid rounded-4 w-100 object-fit-cover" style="height: 200px;">
              </div>

              <h4 class="fw-bold text-dark fs-5 mb-1">{{ $initiative->title }}</h4>
              <p class="text-muted small mb-3">{{ ucfirst($initiative->type->value) }} initiative</p>

              <div class="p-3 mb-3 rounded-3 bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-user text-muted fs-6"></i>
                  <div>
                    <div class="text-muted" style="font-size: 0.7rem;">Manager</div>
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $initiative->manager_name }}</div>
                  </div>
                </div>
              </div>

              @if ($initiative->start_date)
                <div class="p-3 mb-3 rounded-3 bg-light d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center gap-2">
                    <i class="fa-regular fa-calendar text-muted fs-6"></i>
                    <div>
                      <div class="text-muted" style="font-size: 0.7rem;">Starts</div>
                      <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $initiative->start_date?->format('M j, Y') }}</div>
                    </div>
                  </div>
                </div>
              @endif

              <a href="{{ route('contact') }}" class="btn w-100 text-white rounded-3 py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #146666;">
                Get involved <i class="fa-solid fa-arrow-right"></i>
              </a>

            </div>
          </div>

        </div>
      </div>
    </section>
@endsection
