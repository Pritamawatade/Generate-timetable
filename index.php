<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">Timetable Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="student.php">Student Portal</a></li>
                    <li class="nav-item"><a class="nav-link" href="teacher_dashboard.php">Teacher Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-screen overflow-hidden flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
        <!-- Background Animation -->
        <div class="absolute inset-0 z-0">
            <div class="absolute bg-white opacity-10 w-[500px] h-[500px] rounded-full animate-pulse blur-3xl top-20 left-10"></div>
            <div class="absolute bg-white opacity-10 w-[400px] h-[400px] rounded-full animate-pulse blur-3xl bottom-10 right-10"></div>
        </div>

        <div class="container mx-auto text-center text-white relative z-10">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">Effortless Timetable Management</h1>
            <p class="text-lg md:text-xl mb-8 animate-fade-in-delayed">Organize classes, manage schedules, and simplify your workflow with ease.</p>

            <div class="flex justify-center space-x-4 animate-fade-in-delayed-2">
                <a href="student.php" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg">Student Portal</a>
                <a href="teacher_login.php" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-lg">Teacher Dashboard</a>
                <a href="admin_login.php" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg">Admin Dashboard</a>
            </div>
        </div>
    </section>

    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 1.5s ease-out;
        }

        .animate-fade-in-delayed {
            animation: fadeIn 2s ease-out;
        }

        .animate-fade-in-delayed-2 {
            animation: fadeIn 2.5s ease-out;
        }
    </style>

    <script>
        gsap.fromTo("h1", { opacity: 0, y: -50 }, { opacity: 1, y: 0, duration: 1, delay: 0.5 });
        gsap.fromTo("p", { opacity: 0, y: 50 }, { opacity: 1, y: 0, duration: 1, delay: 1 });
        gsap.fromTo(".flex a", { opacity: 0, scale: 0.8 }, { opacity: 1, scale: 1, duration: 0.5, stagger: 0.2, delay: 1.5 });
    </script>
</body>
</html>
