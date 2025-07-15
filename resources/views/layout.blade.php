<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">Library System</a>
                    <a href="{{ route('member.index') }}" class="text-gray-700 hover:text-blue-600 font-semibold">Member</a>
                    <a href="{{ route('member-card.index') }}" class="text-gray-700 hover:text-blue-600 font-semibold">Member Card</a>
                    <a href="{{ route('penerbit.index') }}" class="text-gray-700 hover:text-blue-600 font-semibold">Publisher</a>
                    <a href="{{ route('buku.index') }}" class="text-gray-700 hover:text-blue-600 font-semibold">Book</a>
                    <a href="{{ route('pinjaman.index') }}" class="text-gray-700 hover:text-blue-600 font-semibold">Loan</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-semibold">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="container mx-auto px-4">
        @yield('content')
    </main>
</body>
</html>