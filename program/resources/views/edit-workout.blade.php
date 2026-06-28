@extends('layouts.app')

@section('title', 'Edit Workout | Workout Tracker')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit workout</h1>
        <a href="/" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    <form action="/edit-workout/{{ $workout->id }}" method="POST" class="card space-y-4">
        @csrf
        @method('PUT')
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="name">Workout name</label>
                <input id="name" name="name" type="text" value="{{ $workout->name }}" class="input">
            </div>
            <div>
                <label class="label" for="date">Date</label>
                <input id="date" name="date" type="date" value="{{ $workout->date?->format('Y-m-d') }}" class="input">
            </div>
        </div>

        <div>
            <span class="label">Sets</span>
            <div id="workout-set-rows" class="space-y-3">
                @foreach($workout->workoutSets as $index => $workoutSet)
                    <div class="workout-set-row grid items-end gap-3 sm:grid-cols-[2fr_1fr_1fr_auto]">
                        <select name="workout_sets[{{ $index }}][exercise_definition_id]" class="input">
                            <option value="">Select exercise</option>
                            @foreach($exerciseDefinitions as $exerciseDefinition)
                                <option value="{{ $exerciseDefinition->id }}" @selected($exerciseDefinition->id === $workoutSet->exercise_definition_id)>
                                    {{ $exerciseDefinition->name }} ({{ $exerciseDefinition->exerciseCategory?->name }})
                                </option>
                            @endforeach
                        </select>
                        <input name="workout_sets[{{ $index }}][weight]" type="number" min="0" step="0.5" value="{{ $workoutSet->weight }}" class="input" placeholder="Weight (kg)">
                        <input name="workout_sets[{{ $index }}][reps]" type="number" min="1" step="1" value="{{ $workoutSet->reps }}" class="input" placeholder="Reps">
                        <button type="button" class="remove-workout-set btn btn-danger btn-sm">Remove</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-workout-set" class="btn btn-secondary btn-sm mt-3">+ Add set</button>
        </div>

        <div class="flex justify-end border-t border-gray-100 pt-4">
            <button class="btn btn-primary">Update workout</button>
        </div>
    </form>
</div>

{{-- Template cloned by resources/js/app.js when adding a set --}}
<template id="workout-set-template">
    <div class="workout-set-row grid items-end gap-3 sm:grid-cols-[2fr_1fr_1fr_auto]">
        <select name="workout_sets[__INDEX__][exercise_definition_id]" class="input">
            <option value="">Select exercise</option>
            @foreach($exerciseDefinitions as $exerciseDefinition)
                <option value="{{ $exerciseDefinition->id }}">{{ $exerciseDefinition->name }} ({{ $exerciseDefinition->exerciseCategory?->name }})</option>
            @endforeach
        </select>
        <input name="workout_sets[__INDEX__][weight]" type="number" min="0" step="0.5" class="input" placeholder="Weight (kg)">
        <input name="workout_sets[__INDEX__][reps]" type="number" min="1" step="1" class="input" placeholder="Reps">
        <button type="button" class="remove-workout-set btn btn-danger btn-sm">Remove</button>
    </div>
</template>
@endsection
