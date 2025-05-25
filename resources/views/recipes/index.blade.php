@extends('layouts.app')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

  // When the user changes sort order
  document.getElementById('sort-select').onchange = async e => {
      const [by, dir] = e.target.value.split(':');
      try {
        const { data } = await axios.post('/ms/sort', { by, dir });
        redrawCards(data);                 // replace DOM with new order
      } catch (err) {
        alert('Sort failed');
        console.error(err);
      }
  };

  function redrawCards(recipes) {
     const grid = document.getElementById('recipe-grid');
     grid.innerHTML = '';                  // wipe
     recipes.forEach(r => {
        const bgStyle = r.image_url
          ? `style="background-image:url(${r.image_url}); background-size:cover; border:none;"`
          : '';
        grid.insertAdjacentHTML('beforeend', `
        <div class="col-lg-4 col-sm-6 card-wrapper mb-4">
          <div class="yellow-card d-flex justify-content-around flex-column" ${bgStyle}>
            <h2 class="yellow-card-text" ${r.image_url ? 'style="background:white;"':''}>${r.title}</h2>
            <div class="buttons d-flex gap-3 justify-content-evenly mb-1">
              <a href="/recipes/${r.id}"        class="btn btn-sm btn-primary">View</a>
              <a href="/recipes/${r.id}/edit"   class="btn btn-sm btn-secondary">Edit</a>
              <form action="/recipes/${r.id}" method="POST" onsubmit="return confirm('Delete?')">
                 @csrf @method('DELETE')
                 <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>`);
     });

     // keep the static “Add New” card last
     grid.insertAdjacentHTML('beforeend', `
       <div class="col-lg-4 col-sm-6 card-wrapper">
         <a href="{{ route('recipes.create') }}"
            class="yellow-card bold-yellow d-flex justify-content-around flex-column">
           <h1 class="yellow-card-text">Add New <br>Recipe<br><span style="font-size:64px">+</span></h1>
         </a>
       </div>`);
  }

  // Grocery list view
  const loadGrocery = async () => {
    const loading = document.getElementById('grocery-loading');
    const list    = document.getElementById('grocery-items');
    loading.classList.remove('d-none');
    list.classList.add('d-none');
    list.innerHTML = '';

    try {
      const { data } = await axios.get('/ms/grocery/list');
      if (data.items && data.items.length) {
        data.items.forEach(item => {
          // <li class="list-group-item d-flex justify-content-between">
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-center';

          // item text
          const span = document.createElement('span');
          span.textContent = item;
          li.append(span);

          // delete button
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'btn btn-sm btn-outline-danger delete-btn';
          btn.innerHTML = '<i class="bi bi-trash"></i>';
          li.append(btn);

          // click handler: call your remove endpoint, then refresh
          btn.addEventListener('click', async () => {
            try {
              const { data: res } = await axios.post('/ms/grocery', {
                command: 'Remove',
                ingredient: item
              });
              if (res.status === 'Success') {
                loadGrocery();    // re-fetch the list
              } else {
                alert(`Couldn’t remove “${item}”`);
              }
            } catch (err) {
              console.error(err);
              alert('Error removing item');
            }
          });

          list.append(li);
        });
      } else {
        list.innerHTML = '<li class="list-group-item text-muted">No items</li>';
      }
    } catch (e) {
      list.innerHTML = '<li class="list-group-item text-danger">Error loading list</li>';
      console.error(e);
    } finally {
      loading.classList.add('d-none');
      list.classList.remove('d-none');
    }
  };

  // Load when modal shown
  const modalEl = document.getElementById('groceryModal');
  modalEl.addEventListener('shown.bs.modal', loadGrocery);

  // Refresh button
  document.getElementById('btn-refresh-grocery')
          .addEventListener('click', loadGrocery);

  // Cooking Tip Bar
  const tipText = document.getElementById('tip-text');
  const btn      = document.getElementById('new-tip-btn');

  async function loadTip() {
    tipText.textContent = 'Loading tip…';
    try {
      const { data } = await axios.get('/ms/tip');
      tipText.textContent = data.tip;
    } catch (e) {
      tipText.textContent = 'Error loading tip';
      console.error(e);
    }
  }

  // initial load
  loadTip();

  // on-click for new tip
  btn.addEventListener('click', loadTip);
});
</script>
@endpush

@section('content')
<div class="container">
  <h1 class="mb-3 mt-5">Your Recipes</h1>

  <div class="mb-3">
    <label class="me-2 fw-bold">Sort by:</label>
    <select id="sort-select" class="form-select d-inline-block w-auto me-3">
      <option value="created_at:desc">Newest</option>
      <option value="created_at:asc">Oldest</option>
      <option value="title:asc">Title A→Z</option>
      <option value="title:desc">Title Z→A</option>
    </select>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#groceryModal">
      View Grocery List
    </button>
  </div>

  <div class="row yellow-cards-wrapper mt-4 mb-5 pb-5" id="recipe-grid">
    @foreach ($recipes as $recipe)
      <div class="col-lg-4 col-sm-6 card-wrapper mb-4">
            <div class="yellow-card d-flex justify-content-around flex-column"
            @if($recipe->image_url)
              style="background-image: url({{ $recipe->image_url }}); background-size: cover; border: none;"
            @endif>
              <h2 class="yellow-card-text"
              @if($recipe->image_url)
                style="background: white"
              @endif>{{ $recipe->title }}</h2>
              <div class="buttons d-flex gap-3 justify-content-evenly mb-1">
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
        <h1 class="yellow-card-text">Add New <br>Recipe<br><span style="font-size:64px">+</span></h1>
      </a>
    </div>
  </div>
  <div id="tip-banner"
     class="alert alert-info d-flex justify-content-between align-items-center fixed-bottom m-0">
    <span id="tip-text">Loading tip…</span>
    <button id="new-tip-btn" class="btn btn-sm btn-light">New Tip</button>
  </div>
</div>

<div class="modal fade" id="groceryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Your Grocery List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="grocery-loading">Loading…</div>
        <ul id="grocery-items" class="list-group d-none"></ul>
      </div>
      <div class="modal-footer">
        <button id="btn-refresh-grocery" class="btn btn-primary">Refresh</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>
@endsection


