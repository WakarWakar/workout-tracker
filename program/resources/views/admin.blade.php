<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
</head>
<body>

    @auth
    <p>Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})!</p>
    <form action="/logout" method="POST">
        @csrf
        <button>Logout</button>
    </form>

    <h1>Admin</h1>

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
                        @method('PUT')
                        <input name="name" type="text" value="{{ $muscleWorked->name }}" placeholder="Muscles worked name">
                        <button>Update</button>
                    </form>
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
                        @method('PUT')
                        <input name="name" type="text" value="{{ $category->name }}" placeholder="Category name">
                        <button>Update</button>
                    </form>
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
                <p>Add muscles worked:</p>
                <div style="display: flex; flex-direction: column; gap: 6px; max-height: 160px; overflow: auto; padding: 4px; border: 1px solid #ddd;">
                    @foreach($muscleWorkedOptions as $muscleWorked)
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="muscle_worked_ids[]" value="{{ $muscleWorked->id }}">
                            <span>{{ $muscleWorked->name }}</span>
                        </label>
                    @endforeach
                </div>
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
                        <div style="display: flex; flex-direction: column; gap: 6px; max-height: 160px; overflow: auto; padding: 4px; border: 1px solid #ddd;">
                            @foreach($muscleWorkedOptions as $muscleWorked)
                                <label style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="muscle_worked_ids[]" value="{{ $muscleWorked->id }}" @checked($exerciseDefinition->musclesWorked->contains($muscleWorked->id))>
                                    <span>{{ $muscleWorked->name }}</span>
                                </label>
                            @endforeach
                        </div>
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
            <h2>Access denied</h2>
            <p>You do not have permission to view this page.</p>
        </div>
    @endif

    @endauth

</body>
</html>