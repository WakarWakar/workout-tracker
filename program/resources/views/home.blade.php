<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Workout Tracker</title>
</head>
<body>

    @auth
    <p>Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})!</p>
    <form action="/logout" method="POST">
        @csrf
        <button>Logout</button>
    </form>

    <h1>Workout Tracker</h1>

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

    @if(auth()->user()->isAdmin())
        <div style="margin-top: 16px; border: 1px solid black; padding: 10px;">
            <h2>Create muscles worked</h2>
            <form action="/muscles-worked" method="POST" style="display: flex; gap: 8px; flex-wrap: wrap;">
                @csrf
                <input name="name" type="text" placeholder="Muscles worked name">
                <button>Create muscles worked</button>
            </form>
        </div>

        <div style="border: 1px solid black; margin-top: 16px; padding: 10px;">
            <h2>Manage muscles worked</h2>
            @foreach($muscleWorkedOptions as $muscleWorked)
                <div style="background-color: lightgray; margin: 10px 0; padding: 10px; display: flex; justify-content: space-between; gap: 12px; align-items: center;">
                    <span>{{ $muscleWorked->name }}</span>
                    <form action="/muscles-worked/{{ $muscleWorked->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button>Delete muscles worked</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 16px; border: 1px solid black; padding: 10px;">
            <h2>Create categories</h2>
            <form action="/categories" method="POST" style="display: flex; gap: 8px; flex-wrap: wrap;">
                @csrf
                <input name="name" type="text" placeholder="Category name">
                <button>Create category</button>
            </form>
        </div>

        <div style="border: 1px solid black; margin-top: 16px; padding: 10px;">
            <h2>Manage categories</h2>
            @foreach($categoryOptions as $category)
                <div style="background-color: lightgray; margin: 10px 0; padding: 10px; display: flex; justify-content: space-between; gap: 12px; align-items: center;">
                    <span>{{ $category->name }}</span>
                    <form action="/categories/{{ $category->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button>Delete category</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 16px; border: 1px solid black; padding: 10px;">
            <h2>Create exercise definition</h2>
            <form action="/exercise-definitions" method="POST">
                @csrf
                <input name="name" type="text" placeholder="Exercise name">
                <select name="muscle_worked_id">
                    <option value="">Select muscles worked</option>
                    @foreach($muscleWorkedOptions as $muscleWorked)
                        <option value="{{ $muscleWorked->id }}">{{ $muscleWorked->name }}</option>
                    @endforeach
                </select>
                <select name="category_id">
                    <option value="">Select category</option>
                    @foreach($categoryOptions as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button>Create exercise</button>
            </form>
        </div>

        <div style="border: 1px solid black; margin-top: 16px; padding: 10px;">
            <h2>Manage exercise definitions</h2>
            @foreach($exerciseDefinitions as $exerciseDefinition)
                <div style="background-color: lightgray; margin: 10px 0; padding: 10px;">
                    <form action="/exercise-definitions/{{ $exerciseDefinition->id }}" method="POST" style="display: grid; gap: 8px; grid-template-columns: 2fr 2fr 1fr auto; align-items: end;">
                        @csrf
                        @method('PUT')
                        <input name="name" type="text" value="{{ $exerciseDefinition->name }}" placeholder="Exercise name">
                        <select name="muscle_worked_id">
                            @foreach($muscleWorkedOptions as $muscleWorked)
                                <option value="{{ $muscleWorked->id }}" @selected($exerciseDefinition->muscle_worked_id === $muscleWorked->id)>{{ $muscleWorked->name }}</option>
                            @endforeach
                        </select>
                        <select name="category_id">
                            @foreach($categoryOptions as $category)
                                <option value="{{ $category->id }}" @selected($exerciseDefinition->category_id === $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button>Update</button>
                    </form>

                    <form action="/exercise-definitions/{{ $exerciseDefinition->id }}" method="POST" style="margin-top: 8px;">
                        @csrf
                        @method('DELETE')
                        <button>Delete exercise</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div style="margin-top: 16px; border: 1px solid black; padding: 10px;">
            <h2>Log a workout</h2>
            <form action="/create-workout" method="POST" id="workout-form">
                @csrf
                <input name="name" type="text" placeholder="Workout name">
                <input name="date" type="date">

                <div id="workout-set-rows">
                    <div class="workout-set-row" style="display: grid; gap: 8px; grid-template-columns: 2fr 1fr 1fr auto; margin-bottom: 10px; align-items: end;">
                        <select name="workout_sets[0][exercise_definition_id]">
                            <option value="">Select exercise</option>
                            @foreach($exerciseDefinitions as $exerciseDefinition)
                                <option value="{{ $exerciseDefinition->id }}">{{ $exerciseDefinition->name }} ({{ $exerciseDefinition->exerciseCategory?->name }})</option>
                            @endforeach
                        </select>
                        <input name="workout_sets[0][weight]" type="number" min="0" step="0.5" placeholder="Weight">
                        <input name="workout_sets[0][reps]" type="number" min="1" step="1" placeholder="Reps">
                        <button type="button" class="remove-workout-set">Remove set</button>
                    </div>
                </div>

                <button type="button" id="add-workout-set">Add set</button>
                <button>Save workout</button>
            </form>
        </div>

        <div style="border: 1px solid black; margin-top: 16px; padding: 10px;">
            <h2>Saved workouts</h2>
            @foreach($workouts as $workout)
            <div style="background-color: lightgray; margin: 10px 0; padding: 10px;">
                <h3>{{ $workout['name'] }} on {{ $workout['date']?->format('d-m-Y') }}</h3>
                <ul>
                    @foreach($workout->workoutSets as $workoutSet)
                        <li>
                            {{ $workoutSet->exerciseDefinition->name }} ({{ $workoutSet->exerciseDefinition->exerciseCategory?->name }}) - {{ $workoutSet->weight }} kg - {{ $workoutSet->reps }} reps
                        </li>
                    @endforeach
                </ul>
                <p><a href="/edit-workout/{{ $workout->id }}">Edit</a></p>
                <form action="/delete-workout/{{ $workout->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Delete workout</button>
                </form>
            </div>
            @endforeach
        </div>
    @endif

    @else
    <h2>Welcome to our workout tracker!</h2>

    <div style="border: 1px solid black;">
        <h2>Register</h2>
        <form action="/register" method="POST">
            @csrf
            <input name="name" type="text" placeholder="Enter your name">
            <input name="email" type="text" placeholder="Enter your email">
            <input name="password" type="text" placeholder="Password">
            <button>Register</button>
        </form>
    </div>

    <div style="border: 1px solid black; margin-top: 16px;">
        <h2>Login</h2>
        <form action="/login" method="POST">
            @csrf
            <input name="loginname" type="text" placeholder="Enter your name">
            <input name="loginpassword" type="text" placeholder="Password">
            <button>Login</button>
        </form>
    </div>

    @endauth

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