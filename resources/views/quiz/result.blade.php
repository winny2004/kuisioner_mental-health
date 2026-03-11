@extends('layouts.app')

@section('title', 'Hasil Kuisioner - Mental Health')

@php
// Define helper function directly in view
if (!function_exists('getDASS21Label')) {
    function getDASS21Label($score, $type) {
        $labels = [
            'depression' => [
                [0, 9, 'Normal'],
                [10, 13, 'Mild (Ringan)'],
                [14, 20, 'Moderate (Sedang)'],
                [21, 27, 'Severe (Berat)'],
                [28, 42, 'Extremely Severe (Sangat Berat)'],
            ],
            'anxiety' => [
                [0, 7, 'Normal'],
                [8, 9, 'Mild (Ringan)'],
                [10, 14, 'Moderate (Sedang)'],
                [15, 19, 'Severe (Berat)'],
                [20, 42, 'Extremely Severe (Sangat Berat)'],
            ],
            'stress' => [
                [0, 14, 'Normal'],
                [15, 18, 'Mild (Ringan)'],
                [19, 25, 'Moderate (Sedang)'],
                [26, 33, 'Severe (Berat)'],
                [34, 42, 'Extremely Severe (Sangat Berat)'],
            ],
        ];

        $ranges = $labels[$type] ?? [];
        foreach ($ranges as $range) {
            if ($score >= $range[0] && $score <= $range[1]) {
                return $range[2];
            }
        }

        return 'Unknown';
    }
}
@endphp

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-700 mb-4">Hasil Kuisioner</h1>
            <p class="text-xl text-blue-600">
                {{ $type === 'family_social' ? 'Family Social Factor' : 'Self Efficacy' }}
            </p>
        </div>

        <!-- Score Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="text-center">
                @if($type === 'family_social' && $result->ml_prediction && is_array($result->ml_prediction) && isset($result->ml_prediction['prediction']))
                    <!-- ML Prediction Result -->
                    @php
                        $prediction = $result->ml_prediction['prediction'] ?? 'Unknown';
                        $confidence = $result->ml_prediction['confidence'] ?? 0;
                        $probabilities = $result->ml_prediction['probabilities'] ?? [];
                    @endphp

                    <div class="text-6xl mb-4">
                        @if($prediction === 'Normal')
                            😊
                        @elseif($prediction === 'Depression')
                            😔
                        @elseif($prediction === 'Anxiety')
                            😰
                        @elseif($prediction === 'Stress')
                            😫
                        @else
                            📊
                        @endif
                    </div>

                    <div class="mb-6">
                        <p class="text-gray-600 mb-2">Hasil Analisis AI</p>
                        <p class="text-5xl font-bold
                            {{ $prediction === 'Normal' ? 'text-green-600'
                                : ($prediction === 'Depression' ? 'text-red-600'
                                : ($prediction === 'Anxiety' ? 'text-orange-600'
                                : 'text-yellow-600')) }}">
                            {{ $prediction }}
                        </p>
                        @if($confidence > 0)
                            <p class="text-lg text-gray-500 mt-2">
                                Akurasi: {{ round(($confidence * 100), 2) }}%
                            </p>
                        @endif
                    </div>

                    @if(!empty($probabilities) && is_array($probabilities))
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-700 mb-3">Probabilitas Klasifikasi</h3>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($probabilities as $label => $prob)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-sm text-gray-600">{{ $label }}</p>
                                        <p class="text-xl font-bold text-blue-600">{{ round(($prob * 100), 1) }}%</p>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $prob * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Regular Score Result -->
                    <div class="text-6xl mb-4">
                        {{ $result->category === 'tinggi' ? '🎉' : ($result->category === 'sedang' ? '👍' : '💪') }}
                    </div>
                    <div class="mb-6">
                        <p class="text-gray-600 mb-2">Skor Total Anda</p>
                        <p class="text-5xl font-bold text-blue-700">
                            {{ $result->total_score }} / {{ $result->max_score }}
                        </p>
                    </div>

                    <div class="mb-8">
                        <p class="text-gray-600 mb-2">Kategori</p>
                        <span class="inline-block px-8 py-3 rounded-full text-2xl font-bold
                            {{ $result->category === 'tinggi' ? 'bg-green-100 text-green-700'
                                : ($result->category === 'sedang' ? 'bg-yellow-100 text-yellow-700'
                                : 'bg-red-100 text-red-700') }}">
                            {{ $result->getCategoryLabel() }}
                        </span>
                    </div>
                @endif

                @if($sectionBreakdown)
                    <!-- Section Breakdown -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Rincian per Bagian</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Family Social Section -->
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                <p class="text-sm text-gray-600 mb-1">Family Social</p>
                                <p class="text-2xl font-bold text-blue-700">
                                    {{ $sectionBreakdown['family_social']['score'] }} / {{ $sectionBreakdown['family_social']['max'] }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $sectionBreakdown['family_social']['count'] }} pertanyaan</p>
                                <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $sectionBreakdown['family_social']['percentage'] }}%"></div>
                                </div>
                            </div>

                            <!-- DASS-21 Section -->
                            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                <p class="text-sm text-gray-600 mb-1">DASS-21</p>
                                <p class="text-2xl font-bold text-green-700">
                                    {{ $sectionBreakdown['dass21']['score'] }} / {{ $sectionBreakdown['dass21']['max'] }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $sectionBreakdown['dass21']['count'] }} pertanyaan</p>
                                <div class="w-full bg-green-200 rounded-full h-2 mt-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $sectionBreakdown['dass21']['percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($type === 'family_social' && $result->dass21_depression !== null)
                    <!-- DASS-21 Detailed Breakdown -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Analisis DASS-21</h3>
                        <div class="space-y-4">
                            <!-- Depression -->
                            <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm text-gray-700 font-semibold">Depresi</p>
                                    <span class="text-2xl font-bold text-red-600">{{ $result->dass21_depression }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-2">{{ getDASS21Label($result->dass21_depression, 'depression') }}</p>
                                <div class="w-full bg-red-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ min(($result->dass21_depression / 42) * 100, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Anxiety -->
                            <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm text-gray-700 font-semibold">Kecemasan</p>
                                    <span class="text-2xl font-bold text-orange-600">{{ $result->dass21_anxiety }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-2">{{ getDASS21Label($result->dass21_anxiety, 'anxiety') }}</p>
                                <div class="w-full bg-orange-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ min(($result->dass21_anxiety / 42) * 100, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Stress -->
                            <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm text-gray-700 font-semibold">Stres</p>
                                    <span class="text-2xl font-bold text-yellow-600">{{ $result->dass21_stress }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-2">{{ getDASS21Label($result->dass21_stress, 'stress') }}</p>
                                <div class="w-full bg-yellow-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min(($result->dass21_stress / 42) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <p class="text-lg text-blue-800">
                        {{ $result->feedback }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <p class="text-gray-700 mb-3 text-center">Persentase Pencapaian</p>
            <div class="w-full bg-gray-200 rounded-full h-6">
                <div class="h-6 rounded-full text-white text-center font-bold flex items-center justify-center
                    {{ $result->category === 'tinggi' ? 'bg-green-500' 
                        : ($result->category === 'sedang' ? 'bg-yellow-500' 
                        : 'bg-red-500') }}"
                    style="width: {{ ($result->total_score / $result->max_score) * 100 }}%">
                    {{ number_format(($result->total_score / $result->max_score) * 100, 1) }}%
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid md:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('quiz.start', $type) }}" class="block bg-blue-500 hover:bg-blue-600 text-white text-center font-bold py-4 rounded-xl shadow-lg transition transform hover:scale-105">
                Kerjakan Lagi
            </a>
            <a href="{{ route('quiz.index') }}" class="block bg-white hover:bg-gray-50 text-blue-600 text-center font-bold py-4 rounded-xl shadow-lg border-2 border-blue-500 transition transform hover:scale-105">
                Pilih Kuisioner Lain
            </a>
        </div>

        <!-- Tips Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <h3 class="text-xl font-bold text-blue-700 mb-4">💡 Rekomendasi</h3>
            @if($result->category === 'tinggi')
                <ul class="space-y-2 text-gray-700">
                    <li>✅ Pertahankan kondisi {{ $type === 'family_social' ? 'hubungan keluarga' : 'kepercayaan diri' }} Anda yang baik</li>
                    <li>✅ Terus berkomunikasi dengan {{ $type === 'family_social' ? 'keluarga' : 'orang terdekat' }} }</li>
                    <li>✅ Bagikan pengalaman positif Anda dengan orang lain</li>
                </ul>
            @elseif($result->category === 'sedang')
                <ul class="space-y-2 text-gray-700">
                    <li>📈 Tingkatkan {{ $type === 'family_social' ? 'komunikasi' : 'keyakinan diri' }} Anda secara bertahap</li>
                    <li>💬 Diskusikan dengan {{ $type === 'family_social' ? 'keluarga' : 'teman dekat' } } tentang perasaan Anda</li>
                    <li>🎯 Tetapkan target kecil yang dapat dicapai</li>
                </ul>
            @else
                <ul class="space-y-2 text-gray-700">
                    <li>🤗 Jangan ragu untuk meminta bantuan profesional</li>
                    <li>📱 Hubungi hotline dukungan psikologis jika diperlukan</li>
                    <li>👨‍⚕️ Konsultasikan dengan konselor atau psikolog</li>
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
