@extends('layouts.app')

@section('title', 'Workout Tracker')

@section('content')
@auth
    @if (auth()->user()->isUser())
        <div class="space-y-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Your workouts</h1>
                <p class="mt-1 text-sm text-gray-500">Log a session and track your progress over time.</p>
            </div>

            {{-- Log a workout --}}
            <section class="card">
                <h2 class="card-title">Log a workout</h2>
                <form action="/create-workout" method="POST" id="workout-form" class="mt-4 space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="name">Workout name</label>
                            <input id="name" name="name" type="text" class="input" placeholder="e.g. Push day">
                        </div>
                        <div>
                            <label class="label" for="date">Date</label>
                            <input id="date" name="date" type="date" class="input">
                        </div>
                    </div>

                    <div>
                        <span class="label">Sets</span>
                        <div id="workout-set-rows" class="space-y-3">
                            <div class="workout-set-row grid items-end gap-3 sm:grid-cols-[2fr_1fr_1fr_auto]">
                                <select name="workout_sets[0][exercise_definition_id]" class="input">
                                    <option value="">Select exercise</option>
                                    @foreach($exerciseDefinitions as $exerciseDefinition)
                                        <option value="{{ $exerciseDefinition->id }}">{{ $exerciseDefinition->name }} ({{ $exerciseDefinition->exerciseCategory?->name }})</option>
                                    @endforeach
                                </select>
                                <input name="workout_sets[0][weight]" type="number" min="0" step="0.5" class="input" placeholder="Weight (kg)">
                                <input name="workout_sets[0][reps]" type="number" min="1" step="1" class="input" placeholder="Reps">
                                <button type="button" class="remove-workout-set btn btn-danger btn-sm">Remove</button>
                            </div>
                        </div>
                        <button type="button" id="add-workout-set" class="btn btn-secondary btn-sm mt-3">+ Add set</button>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-4">
                        <button class="btn btn-primary">Save workout</button>
                    </div>
                </form>
            </section>

            {{-- Saved workouts --}}
            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Saved workouts</h2>

                @forelse($workouts as $workout)
                    <article class="card">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">{{ $workout['name'] ?: 'Untitled workout' }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $workout['date']?->format('D, d M Y') ?? 'No date' }}
                                    · {{ $workout->workoutSets->count() }} {{ Str::plural('set', $workout->workoutSets->count()) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="/edit-workout/{{ $workout->id }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="/delete-workout/{{ $workout->id }}" method="POST" onsubmit="return confirm('Delete this workout?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>

                        @if($workout->workoutSets->isNotEmpty())
                            <div class="mt-4 overflow-hidden rounded-lg border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-100 text-sm">
                                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        <tr>
                                            <th class="px-3 py-2">Exercise</th>
                                            <th class="px-3 py-2">Category</th>
                                            <th class="px-3 py-2 text-right">Weight</th>
                                            <th class="px-3 py-2 text-right">Reps</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($workout->workoutSets as $workoutSet)
                                            <tr>
                                                <td class="px-3 py-2 font-medium text-gray-900">{{ $workoutSet->exerciseDefinition->name }}</td>
                                                <td class="px-3 py-2 text-gray-500">{{ $workoutSet->exerciseDefinition->exerciseCategory?->name }}</td>
                                                <td class="px-3 py-2 text-right text-gray-700">{{ $workoutSet->weight !== null ? $workoutSet->weight.' kg' : '—' }}</td>
                                                <td class="px-3 py-2 text-right text-gray-700">{{ $workoutSet->reps ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="card text-center">
                        <p class="text-sm text-gray-500">No workouts yet. Log your first session above. 🏋️</p>
                    </div>
                @endforelse
            </section>
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
    @endif
@else
    {{-- Guest landing --}}
    <div class="mx-auto max-w-md">
        <div class="mb-8 text-center">
            <div class="text-4xl">💪</div>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Workout Tracker</h1>
            <p class="mt-1 text-sm text-gray-500">Log your sessions, track your sets, see your progress.</p>
        </div>

        <div class="card">
            <h2 class="card-title">Create an account</h2>
            <form action="/register" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label" for="reg-name">Name</label>
                    <input id="reg-name" name="name" type="text" class="input" placeholder="Enter your name">
                </div>
                <div>
                    <label class="label" for="reg-email">Email</label>
                    <input id="reg-email" name="email" type="email" class="input" placeholder="Enter your email">
                </div>
                <div>
                    <label class="label" for="reg-password">Password</label>
                    <input id="reg-password" name="password" type="password" class="input" placeholder="Password">
                </div>
                <button class="btn btn-primary w-full">Register</button>
            </form>
        </div>

        <div class="card mt-4">
            <h2 class="card-title">Log in</h2>
            <form action="/login" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label" for="login-name">Name</label>
                    <input id="login-name" name="loginname" type="text" class="input" placeholder="Enter your name">
                </div>
                <div>
                    <label class="label" for="login-password">Password</label>
                    <input id="login-password" name="loginpassword" type="password" class="input" placeholder="Password">
                </div>
                <button class="btn btn-primary w-full">Login</button>
            </form>
        </div>
    </div>
@endauth
@endsection
