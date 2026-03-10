@extends('layouts.app')

@section('title', 'Selamat Datang - Mental Health Kuisioner')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-4xl w-full">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-blue-700 mb-4">
                Mental Health Kuisioner
            </h1>
            <p class="text-xl text-blue-600 mb-8">
                Cek kesehatan mental Anda dengan kuisioner sederhana
            </p>
            <div class="flex justify-center space-x-4">
                <a href="/login" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105">
                    Login
                </a>
                <a href="/register" class="bg-white hover:bg-gray-50 text-blue-600 font-bold py-3 px-8 rounded-lg shadow-lg border-2 border-blue-500 transition transform hover:scale-105">
                    Register
                </a>
            </div>
        </div>

        <!-- Features Section -->
        <div class="grid md:grid-cols-3 gap-6 mt-16">
            <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                <div class="text-blue-500 text-4xl mb-4">🧠</div>
                <h3 class="text-xl font-bold text-blue-700 mb-2">Kuisioner Terstandar</h3>
                <p class="text-gray-600">Pertanyaan berbasis standar kesehatan mental</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                <div class="text-blue-500 text-4xl mb-4">📊</div>
                <h3 class="text-xl font-bold text-blue-700 mb-2">Hasil Instan</h3>
                <p class="text-gray-600">Dapatkan hasil analisis kesehatan mental Anda</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                <div class="text-blue-500 text-4xl mb-4">🔒</div>
                <h3 class="text-xl font-bold text-blue-700 mb-2">Privasi Terjamin</h3>
                <p class="text-gray-600">Data Anda aman dan kerahasiaan terjaga</p>
            </div>
        </div>

        <!-- Quiz Types Section -->
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-blue-700 text-center mb-8">Pilih Jenis Kuisioner</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <a href="/login" class="block bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:scale-105 border-t-4 border-blue-500">
                    <div class="text-center">
                        <div class="text-blue-500 text-5xl mb-4">👨‍👩‍👧‍👦</div>
                        <h3 class="text-2xl font-bold text-blue-700 mb-2">Family Social Factor</h3>
                        <p class="text-gray-600 mb-4">Ukur dukungan dan hubungan sosial dalam keluarga</p>
                        <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-semibold">12 Pertanyaan Family Social</span><br><br>
                        <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-semibold">10 Pertanyaan Social Support</span>
                    </div>
                </a>
                
                <a href="/login" class="block bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:scale-105 border-t-4 border-green-500">
                    <div class="text-center">
                        <div class="text-green-500 text-5xl mb-4">💪</div>
                        <h3 class="text-2xl font-bold text-blue-700 mb-2">Self Efficacy Factor</h3>
                        <p class="text-gray-600 mb-4">Ukur tingkat kepercayaan diri dan kemampuan diri</p>
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-semibold">10 Pertanyaan Self-Efficacy</span><br><br>
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-semibold">18 Pertanyaan Well-Being</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
