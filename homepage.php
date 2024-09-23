<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 60px; /* Space for fixed header */
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        body.light-theme {
            background-color: #f2f2f2;
            color: #333;
        }

        body.dark-theme {
            background-color: #333;
            color: #f2f2f2;
        }

        header {
            width: 100%;
            position: fixed; /* Fixed at the top */
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, color 0.3s;
            z-index: 1000; /* Ensure it stays on top */
        }

        body.dark-theme header {
            background-color: #444;
        }

        .logo {
            width: 100px;
        }

        #theme-toggle {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        #theme-toggle:hover {
            background-color: #0056b3;
        }

        nav {
            display: flex;
            align-items: center;
        }

        .nav-link {
            margin-right: 20px;
            text-decoration: none;
            color: inherit;
            font-size: 16px;
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .content h1 {
            font-size: 36px;
        }

        .content p {
            font-size: 18px;
            margin: 10px 0;
        }

        .accordion {
            margin-top: 20px;
            width: 300px;
            cursor: pointer;
            text-align: center;
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
        }

        .accordion:hover {
            background-color: #218838;
        }

        body.light-theme .accordion {
            background-color: #28a745;
            color: #fff;
        }

        body.dark-theme .accordion {
            background-color: #218838;
            color: #fff;
        }

        .panel {
            display: none;
            background-color: #f2f2f2;
            padding: 20px;
            margin-top: 10px;
            border-radius: 4px;
            text-align: center;
            width: 300px;
            color: #333;
        }

        body.dark-theme .panel {
            background-color: #444;
            color: #f2f2f2;
        }

        .cookies-notification {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
            display: none;
        }

        .cookies-notification button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
        }

        .cookies-notification button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <a href="#" class="logo">MySite</a>
        <button id="theme-toggle" onclick="toggleTheme()">🌙</button>
        <nav>
            <a href="login.php" class="nav-link">Login</a>
            <a href="signup.php" class="nav-link">Sign Up</a>
        </nav>
    </header>
    <div class="content">
        <h1>Welcome to MySite</h1>
        <p>Your one-stop destination for managing tasks and projects.</p>
        <button class="accordion">Why you should join us</button>
        <div class="panel">
            <p>In our site, you will be able to create and manage lists however you like when you create an account.</p>
        </div>
        <button class="accordion">Head to the sign-up to get started</button>
        <div class="panel">
            <p>Sign up now to enjoy all the features and start managing your tasks efficiently!</p>
        </div>
    </div>
    <div class="cookies-notification" id="cookies-notification">
        <p>This site uses cookies to improve your experience. By continuing to browse the site, you agree to our use of cookies.</p>
        <button onclick="acceptCookies()">Accept</button>
    </div>

    <script>
        function toggleTheme() {
            const body = document.body;
            body.classList.toggle('dark-theme');
            const themeToggleButton = document.getElementById('theme-toggle');
            const theme = body.classList.contains('dark-theme') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            themeToggleButton.textContent = theme === 'dark' ? '☀️' : '🌙';
        }

        function checkTheme() {
            const theme = localStorage.getItem('theme') || 'light';
            const themeToggleButton = document.getElementById('theme-toggle');
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
                themeToggleButton.textContent = '☀️';
            } else {
                themeToggleButton.textContent = '🌙';
            }
        }

        function acceptCookies() {
            document.getElementById('cookies-notification').style.display = 'none';
            localStorage.setItem('cookiesAccepted', 'true');
        }

        function checkCookies() {
            const cookiesAccepted = localStorage.getItem('cookiesAccepted');
            if (!cookiesAccepted) {
                document.getElementById('cookies-notification').style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            checkTheme();
            checkCookies();
        });

        // Accordion functionality
        document.querySelectorAll('.accordion').forEach((accordion) => {
            accordion.addEventListener('click', function() {
                this.classList.toggle('active');
                const panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        });
    </script>
</body>
</html>
