@extends('layouts.frontend')

@section('title', $settings['donation_page_title'])
@section('meta_description', $settings['donation_meta_description'] ?? ($settings['meta_description'] ?? ''))
@section('nav-donation', 'active')

@section('content')
  <section class="courses-banner" @include('frontend.donation._banner-style')>
    <div class="container">
      <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
        <div class="col-lg-12">
          <h1>{{ $settings['donation_page_title'] }}</h1>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
              <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">{{ $settings['donation_page_title'] }}</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </section>

  @if (!empty($settings['donation_mission_title']) || !empty($settings['donation_stat_1_number']))
    <div class="training-details-mission py-5">
      <div class="container">
        <div class="row align-items-center g-5">

          <div class="col-lg-6">
            <div class="training-mission-content">
              <h2 class="training-mission-title fw-bold text-dark mb-4">{{ $settings['donation_mission_title'] ?? 'Why Your Contribution Matters' }}</h2>
              @if (!empty($settings['donation_mission_text']))
                <p class="training-mission-desc text-muted mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                  {!! nl2br(e($settings['donation_mission_text'])) !!}
                </p>
              @endif
            </div>
          </div>

          <div class="col-lg-6">
            <div class="row g-3">
              @for ($i = 1; $i <= 4; $i++)
                @if (!empty($settings["donation_stat_{$i}_number"]))
                  <div class="col-sm-6">
                    <div class="training-mission-stat-card bg-white rounded-4 shadow-sm border p-4 h-100">
                      <h3 class="training-mission-stat-number fw-bold mb-1" style="color: #146666; font-size: 2.25rem;">{{ $settings["donation_stat_{$i}_number"] }}</h3>
                      <p class="training-mission-stat-text text-muted mb-0" style="font-size: 0.825rem;">{{ $settings["donation_stat_{$i}_label"] }}</p>
                    </div>
                  </div>
                @endif
              @endfor
            </div>
          </div>

        </div>
      </div>
    </div>
  @endif

  <section class="donation-section py-5 bg-white">
    <div class="container">
      <h2 class="fw-bold text-dark mb-4">{{ $settings['donation_campaigns_title'] }}</h2>

      <div class="row g-4">
        @forelse ($campaigns as $campaign)
          @php
            $goal = (float) $campaign->goal_amount;
            $current = (float) $campaign->current_amount;
            $percent = $goal > 0 ? min(100, round($current / $goal * 100)) : 0;
          @endphp
          <div class="col-md-6 col-lg-4">
            <div class="card course-card border-0 shadow-sm rounded-4 p-3 h-100">
              <div class="position-relative mb-3">
                <img src="{{ $campaign->thumbnail ? asset('storage/' . $campaign->thumbnail) : asset('frontend-assets/img/Frame 1597883364.png') }}" alt="{{ $campaign->title }}" class="card-img-top rounded-4 object-fit-cover" style="height: 180px;">
              </div>
              <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div>
                  <h5 class="card-title fw-bold text-dark mb-1 fs-5">{{ $campaign->title }}</h5>
                  <p class="card-text text-muted small mb-3">{{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags((string) $campaign->description)), 90) }}</p>

                  <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: #146666;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="d-flex justify-content-between small text-muted mb-3">
                    <span class="fw-bold text-dark">${{ number_format($current, 0) }} raised</span>
                    <span>of ${{ number_format($goal, 0) }}</span>
                  </div>
                </div>

                <a href="{{ route('donation.show', $campaign) }}" class="btn fw-bold py-2 rounded-pill text-white shadow-sm text-center" style="background-color: #146666; border: none;">
                  {{ $settings['donation_button_text'] }}
                </a>
              </div>
            </div>
          </div>
        @empty
          <p class="text-muted">{{ $settings['donation_campaigns_empty'] }}</p>
        @endforelse
      </div>
    </div>
  </section>
@endsection
