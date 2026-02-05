@include('admin.header')

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .login-container {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 350px;
    }
    .logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .logo img {
        max-width: 150px;
    }
    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
    }
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    .remember-me {
        display: flex;
        align-items: center;
        margin-top: 1rem;
    }
    .remember-me input {
        margin-right: 0.5rem;
    }
    button {
        width: 100%;
        padding: 0.75rem;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    .footer {
        text-align: center;
        margin-top: 1rem;
        color: #777;
        font-size: 0.9rem;
    }

    /* Desktop styles */
    @media (min-width: 768px) {
        .login-container {
            max-width: 450px;
            padding: 3rem;
        }
        h1 {
            font-size: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            font-size: 1.1rem;
        }
        input[type="email"],
        input[type="password"] {
            padding: 0.75rem;
            font-size: 1.1rem;
        }
        .remember-me {
            font-size: 1.1rem;
        }
        button {
            padding: 1rem;
            font-size: 1.2rem;
        }
        .footer {
            font-size: 1rem;
        }
    }
</style>

<div class="login-container">
    <h1>{{__('admin.Login')}}</h1>
    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">{{__('admin.Email')}}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">{{__('admin.Password')}}</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="remember-me">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">{{__('admin.Remember Me')}}</label>
        </div>
        <button type="submit">{{__('admin.Login')}}</button>
    </form>
</div>

<div class="footer">
    {{ $setting->copyright }}
</div>

@include('admin.footer')