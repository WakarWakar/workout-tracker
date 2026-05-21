<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    @auth
    <p>Welcome, {{ auth()->user()->name }}!</p>
    <form action="/logout" method="POST">
        @csrf
        <button>Logout</button>
    </form>
    
    <div style="border: 1px solid black;">
        <h2>Create a new post</h2>
        <form action="/create-post" method="POST">
            @csrf
            <input name="title" type="text" placeholder="Post title">
            <textarea name="body" placeholder="body content..."></textarea>
            <button>Save Post</button>
        </form>
    </div>

    <div style="border: 1px solid black;">
        <h2>All posts</h2>
        @foreach($posts as $post)
        <div style="background-color: lightgray; margin: 10px; padding: 10px;">
            <h3>{{$post['title']}} by {{$post->user->name}}</h3>
            {{$post['body']}}
            <p><a href="/edit-post/{{$post->id}}">Edit</a></p>
            <form action="/delete-post/{{$post->id}}" method="POST">
                @csrf
                @method('DELETE')
                <button>Delete</button>
        </form>
        </div>
        @endforeach
    </div>


    @else
    <h2>Welcome to our website!</h2>

    <!-- Registration form -->
        <div style="border: 1px solid black;">
            <h2>Register</h2>
            <form action="/register" method="POST">
                @csrf <!-- This is a security feature in Laravel to prevent Cross-Site Request Forgery (CSRF) attacks. It generates a unique token for each form submission, which is then verified by the server to ensure that the request is coming from a trusted source. -->
                <input name="name" type="text" placeholder="Enter your name">
                <input name="email" type="text" placeholder="Enter your email">
                <input name="password" type="text" placeholder="Password">
                <button>Register</button>
            </form>
        </div>

    <!-- Login form -->
    <div style="border: 1px solid black;">
        <h2>Login</h2>
        <form action="/login" method="POST">
            @csrf
            <input name="loginname" type="text" placeholder="Enter your name">
            <input name="loginpassword" type="text" placeholder="Password" >
            <button>Login</button>
        </form>
    </div>

    @endauth



</body>
</html>