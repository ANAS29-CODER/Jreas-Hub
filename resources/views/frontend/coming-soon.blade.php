@extends('layouts.frontend')

@section('title', $title)

@section('content')
  <section class="py-5" style="margin-top: 100px;">
    <div class="container text-center py-5">
      <h1 class="fw-bold text-dark">{{ $title }}</h1>
      <p class="text-muted">This page is being converted from the static site next.</p>
      <a href="{{ route('home') }}" class="btn btn-teal px-4 py-2 rounded-pill fw-medium">Back to Home</a>
    </div>
  </section>
@endsection
