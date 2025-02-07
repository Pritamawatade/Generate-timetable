<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Generator System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
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
        
        body {
            background-image: url("/bg_image.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Add overlay to content sections */
        .content-overlay {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen relative">
   

    <!-- Hero Section -->
   <img src="bg_image.jpg" alt="bg" class="w-screen h-screen object-cover z-10 ">
   <section class="  pt-20 absolute top-0 left-0 w-full h-full z-20">
        <!-- Dark overlay -->
        <!-- <div class="absolute inset-0 bg-black/30"></div> -->

        <div class="container mx-auto text-center text-white relative   p-8 rounded-2xl ">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">TIMETABLE GENERATOR</h1>
            <p class="text-lg md:text-xl mb-8 animate-fade-in-delayed">Organize classes, manage schedules, and simplify your workflow with ease.</p>

            <div class="flex justify-center space-x-4 animate-fade-in-delayed-2">
                <a href="student.php" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition-all">Student Portal</a>
                <a href="teacher_login.php" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-lg transition-all">Teacher Dashboard</a>
                <a href="admin_login.php" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-lg transition-all">Admin Dashboard</a>
            </div>
        </div>
    </section>

    <script>
        gsap.fromTo("h1", { opacity: 0, y: -50 }, { opacity: 1, y: 0, duration: 1, delay: 0.5 });
        gsap.fromTo("p", { opacity: 0, y: 50 }, { opacity: 1, y: 0, duration: 1, delay: 1 });
        gsap.fromTo(".flex a", { opacity: 0, scale: 0.8 }, { opacity: 1, scale: 1, duration: 0.5, stagger: 0.2, delay: 1.5 });
    </script>
</body>
</html>