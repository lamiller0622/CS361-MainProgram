<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $recipe->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <input type="text" name="description" class="form-control" value="{{ old('title', $recipe->description ?? '') }}" required>
</div>
<div class="mb-3 d-flex gap-3 justify-content-between">
  <div class="col-lg-5">
    <label class="form-label">Prep Time</label>
    <input type="text" name="prep" class="form-control" value="{{ old('title', $recipe->prep ?? '') }}">
  </div>
  <div class="col-lg-5">
    <label class="form-label">Number of Servings</label>
    <input type="text" name="servings" class="form-control" value="{{ old('title', $recipe->servings ?? '') }}">
  </div>
</div>
<div class="mb-3">
    <label class="form-label">Ingredients</label>
    <input id="ingredients" type="hidden" name="ingredients"
           value="{{ old('ingredients', $recipe->ingredients ?? '') }}">
    <div id="editor-ingredients" class="bg-white border rounded" style="min-height:200px;">
      {!! old('ingredients', $recipe->ingredients ?? '') !!}
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Instructions</label>
    <input id="instructions" type="hidden" name="instructions"
           value="{{ old('instructions', $recipe->instructions ?? '') }}">
    <div id="editor-instructions" class="bg-white border rounded" style="min-height:200px;">
      {!! old('instructions', $recipe->instructions ?? '') !!}
    </div>
</div>