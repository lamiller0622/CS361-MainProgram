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
  <h1 class="mb-4">Add New Recipe</h1>
  <form action="{{ route('recipes.store') }}" method="POST">
      @csrf
      @include('modules.recipe-form')
      <button class="btn btn-primary">Save</button>
  </form>
</div>
@endsection
