<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agent Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="flex w-full min-h-screen">
        <!-- Left: Login Card -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-white">
            <div class="w-full max-w-md p-8">
                <!-- Logo/Company Info -->
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-bold text-gray-800 uppercase">KPN CORP</h1>
                    <p class="text-sm text-gray-600 tracking-wide">Downstream - Priscolin</p>
                    <p class="text-xs text-gray-500">E-logsheet Reporting</p>
                </div>

                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-800 mb-1 text-center">Login</h2>
                <p class="text-sm text-gray-500 mb-6 text-center">Please enter your details</p>

                <!-- Error Messages -->
                @if (session('error'))
                    <div class="mb-4 px-4 py-2 bg-red-100 border border-red-300 text-red-700 rounded-md text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 px-4 py-2 bg-red-100 border border-red-300 text-red-700 rounded-md text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                            placeholder="username">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300"
                            placeholder="********">
                    </div>

                    <!-- Business Unit -->
                    <div>
                        <label for="business_unit" class="block text-sm font-medium text-gray-600 mb-1">Business
                            Unit</label>
                        <select name="business_unit" id="business_unit" required
                            class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700">
                            <option value="" disabled selected class="text-gray-400 italic">Pilih Business Unit
                            </option>
                            @foreach ($businessUnits as $bu)
                                <option value="{{ $bu->bu_code }}">
                                    {{ $bu->bu_code }} - {{ $bu->bu_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Plant -->
                    <div>
                        <label for="plant" class="block text-sm font-medium text-gray-600 mb-1">Plant</label>
                        <select name="plant" id="plant" required
                            class="w-full px-4 py-2 rounded-md bg-gray-100 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700">
                            <option value="" disabled selected class="text-gray-400 italic">Pilih Plant</option>
                            @foreach ($plants as $plant)
                                <option value="{{ $plant->plant_code }}">{{ $plant->plant_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Forgot password -->
                    {{-- <div class="flex justify-end">
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div> --}}

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-md transition">
                        Sign in
                    </button>
                    <!-- Register Link -->
                    <p class="text-center text-sm text-gray-500 mt-6">
                        Are you new?
                        <a href="#" class="text-blue-600 hover:underline">Create an Account</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Right: Background Image -->
        <div class="hidden md:block md:w-1/2 bg-cover bg-center"
            style="background-image: url('{{ asset('images/bg-login.jpg') }}');">
        </div>
    </div>

</body>

</html>
