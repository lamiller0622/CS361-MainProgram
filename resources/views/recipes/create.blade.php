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

    // Image generator
    const promptInput  = document.getElementById('image-prompt');
    const genBtn       = document.getElementById('btn-generate-image');
    const imgPrev      = document.getElementById('img-preview');
    const imgHidden    = document.getElementById('image_url');

    genBtn.onclick = async () => {
      const prompt = promptInput.value.trim();
      if (!prompt) {
        return alert('Please enter an image prompt.');
      }
      genBtn.disabled = true;
      genBtn.textContent = 'Generating…';
      try {
        const { data } = await axios.post('/ms/generate', { prompt });
        imgHidden.value       = data.url;
        imgPrev.src           = data.url;
        imgPrev.style.display = 'block';
      } catch (e) {
        console.error(e);
        alert('Failed to generate image.');
      } finally {
        genBtn.disabled   = false;
        genBtn.textContent = 'Generate Image';
      }
    };

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
