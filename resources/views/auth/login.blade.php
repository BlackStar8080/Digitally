<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Login</title>

<link rel="stylesheet" href="{{ asset('css/transition.css') }}">

@php
    $currentRoute = Route::currentRouteName();
@endphp

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #ecf5ff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    body.fade-in {
    opacity: 1;
    }

    body.fade-out {
        opacity: 0;
    }

    .logo {
        width: 115px;
        margin-bottom: 20px;
    }
    .card {
        display: flex;
        width: 700px;
        height: 500px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .card-left {
        width: 50%;
        background: #007bff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-left img {
        width: 100%;
    }
    .card-right {
        width: 50%;
        display: flex;
        flex-direction: column;
    }
    .tab-buttons {
        display: flex;
        background: #f8f9fa;
    }
    .tab-button {
        flex: 1;
        padding: 15px 20px;
        background: #f8f9fa;
        border: none;
        color: #6c757d;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border-bottom: 3px solid transparent;
    }
    .tab-button.active {
        background: white;
        color: #007bff;
        border-bottom: 3px solid #007bff;
    }
    .tab-button:hover:not(.active) {
        background: #e9ecef;
        color: #495057;
    }
    form {
        text-align: center;
    }
    .form-content {
        padding: 40px;
        background: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .form-input {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }
    .form-input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }
    .form-submit-button {
        display: inline-block;
        text-align: center;
        padding: 10px 20px;
        background-color: #4c98f7;
        color: white;
        border-radius: 5px;
        font-size: 14px;
        transition: background-color 0.3s;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .form-submit-button:hover {
        background-color: #3678d9;
    }
</style>
</head>
<body>

<img src="{{ asset('images/adele wilsons.png') }}" class="logo" alt="Logo" />

<div class="card">
    <div class="card-left">
        <img src="{{ asset('images/f05945b3f8021150c0a3403a1cd2a004.png') }}" alt="Sports Image" />
    </div>
    <div class="card-right">
        <div class="tab-buttons">
            <a href="{{ route('login.form') }}" class="tab-button {{ $currentRoute == 'login.form' ? 'active' : '' }}">LOGIN</a>
            <a href="{{ route('register.form') }}" class="tab-button {{ $currentRoute == 'register.form' ? 'active' : '' }}">REGISTER</a>
        </div>
        
        <div class="form-content">
            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <input 
                    type="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="EMAIL ADDRESS" 
                    required
                    pattern="^[a-zA-Z0-9@.]+$"
                    title="No special characters allowed"
                >
                <input 
                    type="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="PASSWORD" 
                    required
                    pattern="^[a-zA-Z0-9]+$"
                    title="No special characters allowed"
                >
                <button type="submit" class="form-submit-button">LOGIN</button>
            </form>
            <script>
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const email = this.email.value;
                const password = this.password.value;
                const emailPattern = /^[a-zA-Z0-9@.]+$/;
                const passwordPattern = /^[a-zA-Z0-9]+$/;
                if (!emailPattern.test(email) || !passwordPattern.test(password)) {
                    alert('No special characters allowed in email or password.');
                    e.preventDefault();
                }
            });
            </script>
        </div>
    </div>
</div>

    <script>
        // Fade in on load
        document.addEventListener("DOMContentLoaded", function () {
            document.body.classList.add("fade-in");
        });

        // Apply fade-out when navigating away
        function navigateWithFade(url) {
            document.body.classList.remove("fade-in");
            document.body.classList.add("fade-out");
            setTimeout(function () {
                window.location.href = url;
            }, 400); // matches CSS transition time
        }
    </script>


</body>
</html>
