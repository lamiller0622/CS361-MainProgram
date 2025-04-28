@extends('layouts.app')
@section('content')
<div class="container">
  <h1 class="mb-3 mt-5">Your Recipes</h1>
  <p>View or make changes to your recipes or create a new one!</p>
  <div class="d-block mb-4">
    <a href="{{ route('recipes.create') }}" class="btn btn-primary">Add New Recipe</a> 
    <button data-bs-toggle="modal" data-bs-target="#helpModal" class="btn btn-secondary">How Does it Work?</button>
  </div>
  <div class="row yellow-cards-wrapper mt-5">
      @foreach ($recipes as $recipe)
        <div class="col-lg-4 col-sm-6 card-wrapper">
            <div class="yellow-card d-flex justify-content-around flex-column">
              <h2 class="yellow-card-text">{{ $recipe->title }}</h2>
              <div class="buttons d-flex gap-3 justify-content-evenly">
                <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-sm btn-primary">View</a>
                <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-sm btn-secondary">Edit</a>
                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST">
                   @csrf
                   @method('DELETE')
                   <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete? (This action cannot be undone)')">Delete</button>
                </form>
              </div>
            </div>
        </div>
      @endforeach
      <div class="col-lg-4 col-sm-6 card-wrapper">
          <a href="{{ route('recipes.create') }}" class="yellow-card bold-yellow d-flex justify-content-around flex-column">
            <h1 class="yellow-card-text">Add New <br>Recipe<br><span style="font-size: 64px">+</span></h1>
          </a>
      </div>
    </div>
  </div>
</div>
@include('modules.help-modal')
@endsection