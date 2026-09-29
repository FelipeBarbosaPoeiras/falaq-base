@extends('layouts.app')

@section('title', 'Criar Evento — FalaQ')

@section('content')
<div class="max-w-2xl mx-auto bg-white text-gray-900 p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Criar evento</h1>
    <form action="{{ route('eventos.store') }}" method="POST" novalidate>
        @csrf
        <div class="mb-4">
            <label for="titulo" class="block text-sm font-medium mb-1">Título do evento</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required maxlength="255"
                class="w-full border p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @else border-gray-300 @enderror"
                aria-invalid="{{ $errors->has('titulo') ? 'true' : 'false' }}"
                @error('titulo') aria-describedby="titulo-error" @enderror>
            @error('titulo')
                <p id="titulo-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="descricao" class="block text-sm font-medium mb-1">Descrição do evento</label>
            <textarea name="descricao" id="descricao" rows="4" required
                class="w-full border p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @else border-gray-300 @enderror"
                aria-invalid="{{ $errors->has('descricao') ? 'true' : 'false' }}"
                @error('descricao') aria-describedby="descricao-error" @enderror>{{ old('descricao') }}</textarea>
            @error('descricao')
                <p id="descricao-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-6">
            <label for="data_evento" class="block text-sm font-medium mb-1">Data do evento (opcional)</label>
            <input type="date" name="data_evento" id="data_evento" value="{{ old('data_evento') }}"
                class="w-full border p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('data_evento') border-red-500 @else border-gray-300 @enderror"
                aria-invalid="{{ $errors->has('data_evento') ? 'true' : 'false' }}"
                @error('data_evento') aria-describedby="data_evento-error" @enderror>
            @error('data_evento')
                <p id="data_evento-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">Criar evento</button>
    </form>
</div>
@endsection
