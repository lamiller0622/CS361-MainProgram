@extends('layouts.app')
@section('content')
<section class="recipe-container">
  <h1 class="mb-4">{{ $recipe->title }}</h1>
  <b><h6 class="mb-4">{{ $recipe->description }}</h6></b>

  <div class="mb-4 sm-details">
    <p class="white-space-pre-wrap">{{ $recipe->prep }}</p>
    <p class="white-space-pre-wrap">{{ $recipe->servings }} Serving(s)</p>
  </div>

  <div class="white-wrap">
    <div class="mb-4 ql-editor">
      <h4 class="mb-3">Ingredients</h4>
      {!! $recipe->ingredients !!}
    </div>
  </div>

  <div class="white-wrap">
    <div class="mb-4 ql-editor">
      <h4 class="mb-3">Instructions</h4>
      {!! $recipe->instructions !!}
    </div>
  </div>
  
  <div class="d-flex gap-3 justify-content-between">
    <div class="d-block">
      <a href="{{ route('recipes.index') }}" class="btn btn-lg btn-primary">View All Recipes</a>
      <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-lg btn-secondary">Edit Recipe</a>
    </div>
    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST">
       @csrf
       @method('DELETE')
       <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete? (This action cannot be undone)')">Delete</button>
    </form>
  </div>
</section>
@endsection