@extends('layouts.app')

@section('title', 'Admin | Workout Tracker')

@section('content')
@if(auth()->user()->isAdmin())
    <div class="space-y-10">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Admin</h1>
            <p class="mt-1 text-sm text-gray-500">Manage muscles, categories and exercise definitions.</p>
        </div>

        {{-- Muscles worked --}}
        <section class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Muscles worked</h2>
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="card">
                    <h3 class="card-title text-base">Add muscle</h3>
                    <form action="/muscles-worked" method="POST" class="mt-4 flex gap-2">
                        @csrf
                        <input name="name" type="text" class="input" placeholder="Muscle name">
                        <button class="btn btn-primary shrink-0">Add</button>
                    </form>
                </div>
                <div class="card">
                    <h3 class="card-title text-base">Manage</h3>
                    <div class="mt-4 space-y-2">
                        @forelse($muscleWorkedOptions as $muscleWorked)
                            <div class="flex flex-wrap items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 p-2">
                                <form action="/muscles-worked/{{ $muscleWorked->id }}" method="POST" class="flex flex-1 gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input name="name" type="text" value="{{ $muscleWorked->name }}" class="input">
                                    <button class="btn btn-secondary btn-sm shrink-0">Save</button>
                                </form>
                                <form action="/muscles-worked/{{ $muscleWorked->id }}" method="POST" onsubmit="return confirm('Delete this muscle?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">None yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- Categories --}}
        <section class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Categories</h2>
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="card">
                    <h3 class="card-title text-base">Add category</h3>
                    <form action="/categories" method="POST" class="mt-4 flex gap-2">
                        @csrf
                        <input name="name" type="text" class="input" placeholder="Category name">
                        <button class="btn btn-primary shrink-0">Add</button>
                    </form>
                </div>
                <div class="card">
                    <h3 class="card-title text-base">Manage</h3>
                    <div class="mt-4 space-y-2">
                        @forelse($categoryOptions as $category)
                            <div class="flex flex-wrap items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 p-2">
                                <form action="/categories/{{ $category->id }}" method="POST" class="flex flex-1 gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input name="name" type="text" value="{{ $category->name }}" class="input">
                                    <button class="btn btn-secondary btn-sm shrink-0">Save</button>
                                </form>
                                <form action="/categories/{{ $category->id }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">None yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- Exercise definitions --}}
        <section class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Exercise definitions</h2>

            <div class="card">
                <h3 class="card-title text-base">Add exercise</h3>
                <form action="/exercise-definitions" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label">Name</label>
                            <input name="name" type="text" class="input" placeholder="Exercise name">
                        </div>
                        <div>
                            <label class="label">Category</label>
                            <select name="category_id" class="input">
                                <option value="">Select category</option>
                                @foreach($categoryOptions as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <span class="label">Muscles worked</span>
                        <div class="grid max-h-40 grid-cols-2 gap-2 overflow-auto rounded-lg border border-gray-200 p-3 sm:grid-cols-3">
                            @forelse($muscleWorkedOptions as $muscleWorked)
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="muscle_worked_ids[]" value="{{ $muscleWorked->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-300">
                                    <span>{{ $muscleWorked->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">Add a muscle first.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button class="btn btn-primary">Create exercise</button>
                    </div>
                </form>
            </div>

            <div class="space-y-3">
                @forelse($exerciseDefinitions as $exerciseDefinition)
                    <div class="card">
                        <form action="/exercise-definitions/{{ $exerciseDefinition->id }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="label">Name</label>
                                    <input name="name" type="text" value="{{ $exerciseDefinition->name }}" class="input">
                                </div>
                                <div>
                                    <label class="label">Category</label>
                                    <select name="category_id" class="input">
                                        @foreach($categoryOptions as $category)
                                            <option value="{{ $category->id }}" @selected($exerciseDefinition->category_id === $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <span class="label">Muscles worked</span>
                                <div class="grid max-h-40 grid-cols-2 gap-2 overflow-auto rounded-lg border border-gray-200 p-3 sm:grid-cols-3">
                                    @foreach($muscleWorkedOptions as $muscleWorked)
                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                            <input type="checkbox" name="muscle_worked_ids[]" value="{{ $muscleWorked->id }}" @checked($exerciseDefinition->musclesWorked->contains($muscleWorked->id)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-300">
                                            <span>{{ $muscleWorked->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-end border-t border-gray-100 pt-4">
                                <button class="btn btn-secondary btn-sm">Save changes</button>
                            </div>
                        </form>

                        <form action="/exercise-definitions/{{ $exerciseDefinition->id }}" method="POST" onsubmit="return confirm('Delete this exercise?');" class="mt-2 flex justify-end">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete exercise</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No exercises yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@else
    <div class="card mx-auto max-w-md text-center">
        <h2 class="card-title">Access denied</h2>
        <p class="mt-2 text-sm text-gray-500">You do not have permission to view this page.</p>
    </div>
@endif
@endsection
