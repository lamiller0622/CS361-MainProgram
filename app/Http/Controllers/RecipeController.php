<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::latest()->get();
        return view('recipes.index', compact('recipes'));
    }

    public function create()
    {
        return view('recipes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string|max:255',
            'prep' => 'string|max:255',
            'servings' => 'string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'image_url'    => 'nullable|url',
        ]);
        $recipe = Recipe::create($validated);
        return redirect()->route('recipes.show', compact('recipe'))->with('success', 'Recipe added!');
    }

    public function edit(Recipe $recipe)
    {
        return view('recipes.edit', compact('recipe'));
    }

    public function show(Recipe $recipe)
    {
        return view('recipes.show', compact('recipe'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string|max:255',
            'prep' => 'string|max:255',
            'servings' => 'string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'image_url'    => 'nullable|url',
        ]);
        $recipe->update($validated);
        return redirect()->route('recipes.show', compact('recipe'))->with('success', 'Recipe updated!');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted!');
    }
}
