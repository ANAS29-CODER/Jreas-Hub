@extends('layouts.frontend')

@section('title', $contactPage?->meta_title ?: 'Contact Us')
@section('meta_description', $contactPage?->meta_description ?: ($settings['meta_description'] ?? ''))
@section('nav-contact', 'active')

@section('content')
   <section class="courses-banner ">
      <div class="container">
        <div class="row align-items-center justify-content-between rounded-4 position-relative overflow-hidden">
          <div class="col-lg-12">
            <h1>Contact Us</h1>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-teal fw-semibold" style="color: #146666;" aria-current="page">Contact</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
   </section>

  @include('frontend.partials.contact-section')
@endsection
