<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-purple-600 text-white h-screen fixed">
        <div class="p-4 text-center">
            <h1 class="text-lg font-bold">Admin Dashboard</h1>
        </div>
        <nav class="mt-6">
            <ul>
                <li class="p-3 hover:bg-purple-500"><a href="#">Dashboard</a></li>
                <li class="p-3 hover:bg-purple-500"><a href="#">Users</a></li>
                <li class="p-3 hover:bg-purple-500"><a href="#">Settings</a></li>
                <li class="p-3 hover:bg-purple-500"><a href="#">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6">
        <!-- Header -->
        <header class="mb-6">
            <h2 class="text-2xl font-bold">Welcome, Admin</h2>
        </header>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">Total Users</h3>
                <p class="text-2xl font-bold text-purple-600">1,245</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">Active Sessions</h3>
                <p class="text-2xl font-bold text-purple-600">150</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">New Orders</h3>
                <p class="text-2xl font-bold text-purple-600">32</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-600">Revenue</h3>
                <p class="text-2xl font-bold text-purple-600">$12,430</p>
            </div>
        </div>

        <!-- Table -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Recent Activity</h3>
            <table class="w-full bg-white shadow rounded-lg overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="text-left p-3 text-gray-700 font-medium">User</th>
                        <th class="text-left p-3 text-gray-700 font-medium">Action</th>
                        <th class="text-left p-3 text-gray-700 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-3 border-t text-gray-600">John Doe</td>
                        <td class="p-3 border-t text-gray-600">Logged in</td>
                        <td class="p-3 border-t text-gray-600">2025-01-03</td>
                    </tr>
                    <tr>
                        <td class="p-3 border-t text-gray-600">Jane Smith</td>
                        <td class="p-3 border-t text-gray-600">Added a post</td>
                        <td class="p-3 border-t text-gray-600">2025-01-03</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
