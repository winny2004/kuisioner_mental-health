@extends('layouts.app')

@section('title', 'Pilih Kuisioner - Mental Health')

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-blue-700 mb-4">Pilih Jenis Kuisioner</h1>
            <p class="text-xl text-blue-600">Pilih kuisioner yang ingin Anda kerjakan</p>
        </div>

        <!-- Quiz Cards -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <a href="{{ route('quiz.start', 'family_social') }}" class="block bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition transform hover:scale-105 border-t-4 border-blue-500">
                <div class="text-center">
                    <div class="text-blue-500 text-6xl mb-4">👨‍👩‍👧‍👦</div>
                    <h2 class="text-3xl font-bold text-blue-700 mb-3">Family Social Factor</h2>
                    <p class="text-gray-600 mb-6">
                        Ukur seberapa besar dukungan dan hubungan sosial yang Anda dapatkan dari keluarga
                    </p>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <span class="mr-2">📝</span> 12 Pertanyaan Family Social dan 21 Pertanyaan DASS-21
                        </div>
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <span class="mr-2">⏱️</span> Estimasi 5-10 menit
                        </div>
                    </div>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                        Mulai Kuisioner
                    </button>
                </div>
            </a>

            <a href="{{ route('quiz.start', 'self_efficacy') }}" class="block bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition transform hover:scale-105 border-t-4 border-green-500">
                <div class="text-center">
                    <div class="text-green-500 text-6xl mb-4">💪</div>
                    <h2 class="text-3xl font-bold text-blue-700 mb-3">Self Efficacy Factor</h2>
                    <p class="text-gray-600 mb-6">
                        Ukur tingkat kepercayaan diri dan keyakinan terhadap kemampuan diri sendiri
                    </p>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <span class="mr-2">📝</span> 10 Pertanyaan Self-Efficacy dan 18 Pertanyaan Well-being
                        </div>
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <span class="mr-2">⏱️</span> Estimasi 5-10 menit
                        </div>
                    </div>
                    <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                        Mulai Kuisioner
                    </button>
                </div>
            </a>
        </div>

        <!-- History Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-blue-700">Riwayat Kuisioner</h2>
                <a href="{{ route('quiz.history') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Lihat Semua →
                </a>
            </div>
            <p class="text-gray-600">
                Lihat hasil kuisioner yang telah Anda selesaikan sebelumnya
            </p>
        </div>
    </div>
</div>
@endsection
