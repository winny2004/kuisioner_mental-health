@extends('layouts.app')

@section('title', 'Riwayat Kuisioner - Mental Health')

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-blue-700 mb-2">Riwayat Kuisioner</h1>
                <p class="text-gray-600">Lihat semua hasil kuisioner yang telah Anda selesaikan</p>
            </div>
            <a href="{{ route('quiz.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition">
                ← Kembali
            </a>
        </div>

        <!-- Results Table -->
        @if($results->count() > 0)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-left font-semibold">Jenis Kuisioner</th>
                                <th class="px-6 py-4 text-left font-semibold">Skor</th>
                                <th class="px-6 py-4 text-left font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($results as $result)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        {{ $result->completed_at->format('d M Y') }}
                                        <br>
                                        <span class="text-sm text-gray-500">{{ $result->completed_at->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($result->quiz_type === 'family_social')
                                            <span class="inline-flex items-center">
                                                <span class="mr-2">👨‍👩‍👧‍👦</span>
                                                Family Social Factor
                                            </span>
                                        @else
                                            <span class="inline-flex items-center">
                                                <span class="mr-2">💪</span>
                                                Self Efficacy Factor
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-blue-700">
                                            {{ $result->total_score }} / {{ $result->max_score }}
                                        </span>
                                        <br>
                                        <span class="text-sm text-gray-500">
                                            {{ number_format(($result->total_score / $result->max_score) * 100, 1) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            {{ $result->category === 'tinggi' ? 'bg-green-100 text-green-700' 
                                                : ($result->category === 'sedang' ? 'bg-yellow-100 text-yellow-700' 
                                                : 'bg-red-100 text-red-700') }}">
                                            {{ $result->getCategoryLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('quiz.result', $result->quiz_type) }}" 
                                           class="text-blue-600 hover:text-blue-700 font-semibold">
                                            Lihat Detail →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($results->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $results->appends(['page' => request()->page])->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div class="text-6xl mb-4">📝</div>
                <h2 class="text-2xl font-bold text-blue-700 mb-4">Belum Ada Riwayat</h2>
                <p class="text-gray-600 mb-6">Anda belum menyelesaikan kuisioner apapun</p>
                <a href="{{ route('quiz.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                    Mulai Kuisioner Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
