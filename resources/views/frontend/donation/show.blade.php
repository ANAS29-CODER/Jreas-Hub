@extends('layouts.frontend')

@section('title', $campaign->title)

@section('nav-donation', 'active')

@section('content')
  <section class="courses-banner" @include('frontend.donation._banner-style')>
    <div class="container">
      <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
        <div class="col-lg-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('donation.index') }}" class="text-decoration-none text-muted">{{ $settings['donation_page_title'] }}</a></li>
              <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">{{ $campaign->title }}</li>
            </ol>
          </nav>
          <h1>{{ $campaign->title }}</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="donation-section py-5 bg-white">
    <div class="container">
      <div class="row align-items-center g-5">

        <div class="col-lg-6">
          <div class="donation-img-wrapper rounded-4 overflow-hidden shadow-sm border">
            <img src="{{ $campaign->thumbnail ? asset('storage/' . $campaign->thumbnail) : asset('frontend-assets/img/Frame 1597883364.png') }}" alt="{{ $campaign->title }}" class="w-100 object-fit-cover" style="height: 400px;">
          </div>
        </div>

        <div class="col-lg-6">
          @php
            $goal = (float) $campaign->goal_amount;
            $current = (float) $campaign->current_amount;
            $percent = $goal > 0 ? min(100, round($current / $goal * 100)) : 0;
          @endphp

          <div class="donation-desc text-muted mb-4 lh-lg" style="font-size: 0.95rem;">
            {!! $campaign->description !!}
          </div>

          <div class="progress mb-2" style="height: 10px;">
            <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: #146666;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="d-flex justify-content-between mb-4">
            <span class="fw-bold text-dark">${{ number_format($current, 0) }} raised</span>
            <span class="text-muted">of ${{ number_format($goal, 0) }} goal</span>
          </div>

          <div class="d-flex flex-column flex-sm-row gap-3">
            <a href="{{ $donateUrl }}" class="btn fw-bold py-3 px-5 rounded-pill text-white shadow-sm" style="background-color: #146666; border: none;">
              {{ $settings['donation_button_text'] }}
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>
@endsection
