<?php

namespace App\Http\Controllers;

use App\Models\ExerciseCategory;
use App\Models\ExerciseDefinition;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function ensureAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/');
        }

        return null;
    }

    private function success(string $message)
    {
        return redirect('/')->with('status', $message);
    }

    private function failure(string $message)
    {
        return redirect('/')->withErrors(['submission' => $message])->withInput();
    }

    public function create(Request $request)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $incomingFields = $request->validate([
            'name' => 'required',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);

        if (ExerciseCategory::where('name', $incomingFields['name'])->exists()) {
            return $this->failure('The category name "' . $incomingFields['name'] . '" already exists.');
        }

        ExerciseCategory::create($incomingFields);

        return $this->success('Category "' . $incomingFields['name'] . '" created successfully.');
    }

    public function update(Request $request, ExerciseCategory $category)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $incomingFields = $request->validate([
            'name' => 'required',
        ]);

        $incomingFields['name'] = strip_tags($incomingFields['name']);

        if (ExerciseCategory::where('name', $incomingFields['name'])->where('id', '!=', $category->id)->exists()) {
            return $this->failure('The category name "' . $incomingFields['name'] . '" already exists.');
        }

        $category->update($incomingFields);

        return $this->success('Category "' . $incomingFields['name'] . '" updated successfully.');
    }

    public function delete(ExerciseCategory $category)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        if (ExerciseDefinition::where('category_id', $category->id)->exists()) {
            return $this->failure('Cannot delete category "' . $category->name . '" because it is used by an exercise definition.');
        }

        $categoryName = $category->name;
        $category->delete();

        return $this->success('Category "' . $categoryName . '" deleted successfully.');
    }
}