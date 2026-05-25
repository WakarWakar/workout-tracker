<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Workout | Workout Tracker</title>
</head>
<body>
    <h1>Edit Workout</h1>

    @if (session('status'))
        <div style="margin: 16px 0; padding: 12px; border: 1px solid #1f7a1f; background: #e9f8ea; color: #1f7a1f;">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="margin: 16px 0; padding: 12px; border: 1px solid #b42318; background: #fdecec; color: #b42318;">
            <strong>Submission failed.</strong>
            <ul style="margin: 8px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/edit-workout/{{$workout->id}}" method="POST">
        @csrf
        @method('PUT')
        <input name="name" type="text" value="{{$workout->name}}">
        <input name="date" type="date" value="{{$workout->date?->format('Y-m-d')}}">

        <div style="margin-top: 16px;">
            <h2>Sets</h2>
            <div id="workout-set-rows">
                @foreach($workout->workoutSets as $index => $workoutSet)
                    <div class="workout-set-row" style="display: grid; gap: 8px; grid-template-columns: 2fr 1fr 1fr auto; margin-bottom: 10px; align-items: end;">
                        <select name="workout_sets[{{ $index }}][exercise_definition_id]">
                            <option value="">Select exercise</option>
                            @foreach($exerciseDefinitions as $exerciseDefinition)
                                <option value="{{ $exerciseDefinition->id }}" @selected($exerciseDefinition->id === $workoutSet->exercise_definition_id)>
                                    {{ $exerciseDefinition->name }} ({{ $exerciseDefinition->exerciseCategory?->name }})
                                </option>
                            @endforeach
                        </select>
                        <input name="workout_sets[{{ $index }}][weight]" type="number" min="0" step="0.5" value="{{ $workoutSet->weight }}" placeholder="Weight">
                        <input name="workout_sets[{{ $index }}][reps]" type="number" min="1" step="1" value="{{ $workoutSet->reps }}" placeholder="Reps">
                        <button type="button" class="remove-workout-set">Remove set</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-workout-set">Add set</button>
        </div>
        <button>Update workout</button>
    </form>

    <template id="workout-set-template">
        <div class="workout-set-row" style="display: grid; gap: 8px; grid-template-columns: 2fr 1fr 1fr auto; margin-bottom: 10px; align-items: end;">
            <select name="workout_sets[__INDEX__][exercise_definition_id]">
                <option value="">Select exercise</option>
                @foreach($exerciseDefinitions as $exerciseDefinition)
                    <option value="{{ $exerciseDefinition->id }}">{{ $exerciseDefinition->name }} ({{ $exerciseDefinition->exerciseCategory?->name }})</option>
                @endforeach
            </select>
            <input name="workout_sets[__INDEX__][weight]" type="number" min="0" step="0.5" placeholder="Weight">
            <input name="workout_sets[__INDEX__][reps]" type="number" min="1" step="1" placeholder="Reps">
            <button type="button" class="remove-workout-set">Remove set</button>
        </div>
    </template>

    <script>
        const workoutSetRows = document.getElementById('workout-set-rows');
        const workoutSetTemplate = document.getElementById('workout-set-template');
        const addWorkoutSetButton = document.getElementById('add-workout-set');

        if (workoutSetRows && workoutSetTemplate && addWorkoutSetButton) {
            let nextWorkoutSetIndex = workoutSetRows.querySelectorAll('.workout-set-row').length;

            addWorkoutSetButton.addEventListener('click', () => {
                const templateHtml = workoutSetTemplate.innerHTML.replaceAll('__INDEX__', String(nextWorkoutSetIndex));
                workoutSetRows.insertAdjacentHTML('beforeend', templateHtml);
                nextWorkoutSetIndex += 1;
            });

            workoutSetRows.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-workout-set')) {
                    event.target.closest('.workout-set-row')?.remove();
                }
            });
        }
    </script>
</body>
</html>