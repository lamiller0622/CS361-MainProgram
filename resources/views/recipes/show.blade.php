@extends('layouts.app')
@section('content')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.btn-add-grocery').forEach(btn => {
    btn.addEventListener('click', async () => {
      const ingredient = btn.dataset.ingredient;
      btn.disabled = true;
      try {
        const { data } = await axios.post('/ms/grocery', {
          command: 'Add',
          ingredient
        });
        if (data.status === 'Success') {
          alert(`✓ "${ingredient}" added to grocery list`);
        } else if (data.status === 'Fail') {
          alert(`⚠️ "${ingredient}" was not added`);
        } else {
          alert(`⚠️ ${data.status}`);
        }
      } catch (err) {
        console.error(err);
        alert('Error talking to grocery service');
      } finally {
        btn.disabled = false;
      }
    });
  });
});
</script>
@endpush

<section class="recipe-container">
  <div class="row">
    <div class="col-lg-6">
      <h1 class="mb-4">{{ $recipe->title }}</h1>
      <b><h6 class="mb-4">{{ $recipe->description }}</h6></b>
      <div class="mb-4 sm-details">
        <p class="white-space-pre-wrap">{{ $recipe->prep }}</p>
        <p class="white-space-pre-wrap">{{ $recipe->servings }} Serving(s)</p>
      </div>
    </div>
    <div class="col-lg-6">
      @if($recipe->image_url)
        <div class="mb-4 text-center">
          <img src="{{ $recipe->image_url }}"
               alt="Image for {{ $recipe->title }}"
               class="img-fluid rounded shadow"
               style="max-height: 300px; width: auto;">
        </div>
      @endif
    </div>
  </div>
  

  <div class="white-wrap">
    <div class="mb-4 ql-editor">
      <h4 class="mb-3">Ingredients</h4>
      @php
        // split on p and strip tags
        $lines = preg_split('/<\/p>/', $recipe->ingredients);
      @endphp

      @foreach ($lines as $line)
        @php $text = trim(strip_tags($line)); @endphp
        @if ($text)
          <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="mb-0">{{ $text }}</p>
            <button
              class="btn btn-sm btn-outline-primary btn-add-grocery"
              data-ingredient="{{ $text }}">
              Add to Grocery List
            </button>
          </div>
        @endif
      @endforeach
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