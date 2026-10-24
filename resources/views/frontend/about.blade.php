@extends('layouts.frontend')

@section('title', $aboutPage?->meta_title ?: 'About Us')
@section('meta_description', $aboutPage?->meta_description ?: ($settings['meta_description'] ?? ''))
@section('nav-about', 'active')

@section('content')
   <section class="courses-banner ">
      <div class="container">
        <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
          <div class="col-lg-12">
            <h1>About Us</h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">About Us</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
   </section>

  <section class="about-top py-5">
    <div class="container">
      <div class="row align-items-center justify-content-between g-5">

        <div class="col-lg-6">
          <h1 class="about-title fw-bold display-5 mb-3">
            {{ $settings['about_heading'] }}
          </h1>
          <div class="about-description mb-4">
            @if ($aboutPage && $aboutPage->content)
              {!! $aboutPage->content !!}
            @else
              <p>Jreas Hub is a dynamic educational platform that bridges the gap between traditional education and job market requirements by providing students with high-impact workshops, training courses, and practical professional experiences.</p>
            @endif
          </div>

          <ul class="about-features list-unstyled mb-4 d-flex flex-column gap-3">
            @foreach (preg_split('/\R/', $settings['about_points'] ?? '', -1, PREG_SPLIT_NO_EMPTY) as $point)
              <li class="d-flex align-items-center gap-3">
                <span class="icon-check d-flex align-items-center justify-content-center rounded-circle text-white flex-shrink-0">
                  <i class="fa-solid fa-check"></i>
                </span>
                <span class="feature-text fw-medium small">{{ $point }}</span>
              </li>
            @endforeach
          </ul>

          <div>
            <a href="{{ route('initiatives.index') }}" class="btn about-explore-btn text-white px-4 py-2 rounded-pill fw-medium shadow-sm">
              Explore our initiatives
            </a>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="about-image-wrapper position-relative">
            <img src="{{ !empty($settings['about_image']) ? asset('storage/' . $settings['about_image']) : asset('frontend-assets/img/Frame 1597883364.png') }}" alt="Team working together" class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover">
          </div>
        </div>

      </div>
    </div>
  </section>

  <section class="card-about py-5 bg-light bg-opacity-25">
    <div class="container">

      <div class="mb-5">
        <h2 class="fw-bold text-dark mb-2">{{ $settings['about_features_title'] }}</h2>
        <p class="text-muted" style="max-width: 600px; font-size: 0.95rem;">
          {{ $settings['about_features_text'] }}
        </p>
      </div>

      <div class="row g-4">
        @foreach (['fa-lightbulb', 'fa-users', 'fa-award', 'fa-chart-line'] as $icon)
          <div class="col-md-6 col-lg-3">
            <div class="feature-card bg-white rounded-4 p-4 border h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px; background-color: #e6f2f2; color: #146666;">
                  <i class="fa-solid {{ $icon }} fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2 fs-6">{{ $settings['about_card_' . $loop->iteration . '_title'] ?? '' }}</h5>
                <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.6;">
                  {{ $settings['about_card_' . $loop->iteration . '_text'] ?? '' }}
                </p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
