<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Access Denied | SalesFlow CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-[#0f172a] min-h-screen flex items-center justify-center">
    <div class="text-center px-6">
        <div class="text-9xl font-bold text-white mb-4">403</div>
        <h1 class="text-3xl font-semibold text-white mb-4">Access Denied</h1>
        <p class="text-gray-400 mb-2 max-w-md mx-auto">{{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}</p>
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors mt-6">
            <i class="fas fa-arrow-left"></i>
            Go Back
        </a>
    </div>
</body>
</html>
