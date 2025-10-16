<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> {{ config('app.name') }} - VU FGB</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎯</text></svg>">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/api-landing.css') }}">

    </head>
    <body data-theme="dark">
        <div class="theme-switcher">
            <button class="theme-toggle"></button>
        </div>
        <div class="container">
            <div class="content">
                <h1 class="title">{{ config('app.name') }}</h1>
                <h2 class="subtitle">Faculty of Behavioural and Movement Sciences (FGB)</h2>
                
                <div class="description">
                    <p>{{ config('app.description') }}</p>
                </div>
                <div class="contact">
                    API Documentation</br><a href="{{ config('app.url') }}/docs">View</a>
                </div>
            </div>
        </div>
    </body>
    <footer class="fixed-footer">
        <div class="logo">
            <img src="{{ asset('/assets/vu-logo.png') }}" alt="VU Logo Light" class="logo-light">
            <img src="{{ asset('/assets/vu-logo-dark.png') }}" alt="VU Logo Dark" class="logo-dark">
        </div>
        <div class="footer-links">
            <a href="/.well-known/security.txt" class="footer-link">Security</a>
            <a href="{{ config('app.repo') }}" class="footer-link">Repository</a>
        </div>
        <div class="version-info">
            Laravel v{{ app()->version() }} </br>
            PHP v{{ phpversion() }} </br>
            API {{ config('app.api_version') }}
        </div>
    </footer>

    <script>
        const themeToggle = document.querySelector('.theme-toggle');
        const body = document.body;

        const logos = document.querySelectorAll('.logo img');

        // Find out if the system already has a preferred color scheme
        function applyTheme() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const savedTheme = localStorage.getItem('theme');
            
            if (savedTheme) {
                document.body.setAttribute('data-theme', savedTheme);
            } else {
                document.body.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
            }
            // Pick the right logo to display 
            const currentTheme = document.body.getAttribute('data-theme');
            document.querySelectorAll('.logo-light').forEach(logo => {
                logo.style.display = currentTheme === 'light' ? 'block' : 'none';
            });
            document.querySelectorAll('.logo-dark').forEach(logo => {
                logo.style.display = currentTheme === 'dark' ? 'block' : 'none';
            });

        }
        applyTheme(); // Immediately apply it

        // Event listener for when the system value changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyTheme);


        themeToggle.addEventListener('click', () => {
            const toggledTheme = document.body.dataset.theme === 'dark' ? 'light' : 'dark';
            document.body.dataset.theme = toggledTheme;
            localStorage.setItem('theme', toggledTheme);

            // When the theme toggle is clicked, the opposite logo is unhidden
            // The correct initial one has already been chosen in the applyTheme stage
            logos.forEach(logo => {
                if (logo.style.display === "none") {
                    logo.style.display = "block";
                } else {
                    logo.style.display = "none";
                };
            });
        });

    </script>


</html>
