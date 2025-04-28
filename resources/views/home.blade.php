@extends('layouts.app')
@section('content')
<section class="home-section">
  <div class="container d-flex align-items-center mt-5">
    <div class="row ">
      <div class="col-lg-2"></div>
      <div class="col-lg-8">
        <h1>Personal Recipe Library</h1>
        <h2>Keep all your favorite recipes in one place!</h2>
    </div>
    <div class="row yellow-cards-wrapper">
      <div class="col-lg-2"></div>
      <div class="col-lg-4 card-wrapper">
        <div class="d-flex gap-3 mt-5">
          <a href="{{ route('recipes.create') }}" class="yellow-card create-card">
            <img src="{{ asset('images/veggies.png') }}" alt="">
            <h2 class="yellow-card-text">Add New Recipe</h2>
          </a>
        </div>
      </div>
      <div class="col-lg-4 card-wrapper">
        <div class="d-flex gap-3 justify-content-center mt-5">
          <a href="{{ route('recipes.index') }}" class="yellow-card view-card">
            <img src="{{ asset('images/pasta.png') }}" alt="">
            <h2 class="yellow-card-text">View All Recipes</h2>
          </a>
        </div>
      </div>
    </div>
    <div class="row ">
      <div class="col-lg-12 mt-4 d-flex justify-content-center">
        <button data-bs-toggle="modal" data-bs-target="#helpModal" class="btn btn-primary btn-lg create-card">How Does it Work?</button>
      </div>
    </div>
    </div>
  </div>
</section>

@include('modules.help-modal')
@endsection