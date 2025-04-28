@extends('layouts.app')
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Ingredients editor
    const qi = new Quill('#editor-ingredients', { theme: 'snow' });
    const hiddenIng = document.getElementById('ingredients');
    qi.on('text-change', () => hiddenIng.value = qi.root.innerHTML);

    // Instructions editor
    const qs = new Quill('#editor-instructions', { theme: 'snow' });
    const hiddenIns = document.getElementById('instructions');
    qs.on('text-change', () => hiddenIns.value = qs.root.innerHTML);
  });
</script>
@endpush
@section('content')
<div class="form-container">
  <h1 class="mb-4">Edit Recipe</h1>
  <form action="{{ route('recipes.update', $recipe) }}" method="POST">
      @csrf
      @method('PUT')
      @include('modules.recipe-form')
      <div class="d-flex justify-content-between">
        <button class="btn btn-primary">Save</button>
        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete? (This action cannot be undone)')">Delete</button>
      </div>
  </form>
</div>
@endsection