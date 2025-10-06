<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Makerere Colleges</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($colleges as $college)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $college->image ? asset('storage/' . $college->image) : asset('images/default-college.jpg') }}');"></div>
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $college->name }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($college->description, 100) }}</p>
                    <a href="{{ route('colleges.show', $college) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        View College
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
