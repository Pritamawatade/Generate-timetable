<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Admin Login</title>
</head>
<body class="flex items-center justify-center h-screen bg-gray-200">
    <form class="bg-white p-6 rounded shadow-md" action="admin_dashboard.php" method="POST">
        <h2 class="text-2xl mb-6 text-center">Admin Login</h2>
        <div class="mb-4">
            <label class="block text-gray-700" for="username">Username</label>
            <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="text" id="username" name="username" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700" for="password">Password</label>
            <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="password" id="password" name="password" required>
        </div>
        <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600" type="submit">Login</button>
    </form>
</body>
</html>