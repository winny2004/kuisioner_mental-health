@extends('layouts.app')

@section('title', 'Hasil Kuisioner - Mental Health')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto flex flex-col gap-6">
        <section class="flex flex-col bg-sky-800 text-white rounded-lg overflow-hidden shadow-sm">
            <section class="flex  rounded-md  flex-col ">
                <p class="px-6 py-2 text-xs flex items-center bg-sky-900">Summary Kuisioner</p>
                <section class=" px-6 py-4 flex justify-between border-b-[1px] border-dashed gap-2 ">
                    <section>
                        <p class="font-semibold text-xl">{{ Auth::user()->name }}</p>
                        <p class="text-sky-200 text-sm">{{ Auth::user()->getQuizCountByType($result->quiz_type) }}
                            Kuisoner diambil</p>

                    </section>
                    <section>
                        <p class="text-sky-200 text-xs">{{ $result->completed_at->format('d M Y') }}</p>
                    </section>
                </section>

            </section>
            <section class=" px-6 py-8">
                <section class="flex flex-col gap-1">
                    @if($result->prediction_data && isset($result->prediction_data['prediction']))
                    @php
                    $prediction = $result->prediction_data['prediction'];
                    $predictionId = match($prediction) {
                    // FAMILY SOCIAL FACTOR (DASS)
                    'Normal' => 'Normal',
                    'Depression' => 'Depresi',
                    'Anxiety' => 'Cemas',
                    'Stress' => 'Stres',

                    // SELF EFFICACY FACTOR (WELL-BEING)
                    'high_well_being' => 'High Well-Being',
                    'low_well_being' => 'Low Well-Being',
                    
                    default => $prediction
                    };
                    @endphp
                    <h2 class="text-4xl font-semibold">{{ $predictionId }}</h2>
                    <p class="text-sm text-sky-200 mt-1">{{ $type === 'family_social' ? 'Family Social Factor' : 'Self
                        Efficacy' }}</p>
                    @else
                    @php
                    $categoryLabel = $result->category === 'tinggi' ? 'Tinggi' : ($result->category === 'sedang' ?
                    'Sedang' : 'Rendah');
                    @endphp
                    <h2 class="text-4xl font-semibold">{{ $categoryLabel }}</h2>
                    <p class="text-sm text-sky-200 mt-1">Skor: {{ $result->total_score }} / {{ $result->max_score }}</p>
                    @endif
                </section>
            </section>
        </section>
        <!-- reason -->
          <section class="flex flex-col bg-white text-neutral-800  rounded-lg overflow-hidden shadow-sm">
            <section class="flex  rounded-md  flex-col ">
                <p class="px-6 py-2 text-xs flex items-center bg-sky-900 text-white">Penjelasan Hasil</p>
            </section>
            <section class=" px-6 py-4 text-sm">
              <p>{{ $result->prediction_data['explanation'] ?? 'Penjelasan tidak tersedia.' }}</p>
            </section>
        </section>
        {{-- dashboard insight --}}
        <section class="space-y-6">
            {{-- Charts Container - Side by Side --}}
            @if($type === 'family_social')
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Chart MSPSS - Family, Friends, Significant Other --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Skor MSPSS</h3>
                    <div class="flex items-end justify-center gap-8 px-4" style="height: 240px;">
                        @if($result->prediction_data && isset($result->prediction_data['scores']['MSPSS']))
                        @foreach(['Family', 'Friends', 'Significant_Other'] as $key)
                        @php
                        $value = $result->prediction_data['scores']['MSPSS'][$key] ?? 0;
                        $maxValue = 30;
                        $barHeight = ($value / $maxValue) * 180;
                        $label = match($key) {
                        'Family' => 'Keluarga',
                        'Friends' => 'Teman',
                        'Significant_Other' => 'Significant Other',
                        default => $key
                        };
                        @endphp
                        <div class="flex flex-col items-center">
                            {{-- Value --}}
                            <div class="mb-2">
                                <span class="text-sm font-bold text-sky-800">{{ $value }}</span>
                            </div>
                            {{-- Bar --}}
                            <div class="w-12 bg-sky-800 rounded-t-lg transition-all duration-500"
                                style="height: {{ $barHeight }}px; min-height: 4px;"></div>
                            {{-- X Axis Label --}}
                            <div class="mt-3 text-center">
                                <span class="text-xs font-medium text-gray-600">{{ $label }}</span>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>

                {{-- Chart DASS-21 - Depresi, Cemas, Stress --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Skor DASS-21</h3>
                    <div class="flex items-end justify-center gap-8 px-4 " style="height: 260px;">
                        @if($result->prediction_data && isset($result->prediction_data['categories']))
                        @foreach(['Depression', 'Anxiety', 'Stress'] as $condition)
                        @if(isset($result->prediction_data['categories'][$condition]))
                        @php
                        $data = $result->prediction_data['categories'][$condition];
                        $label = match($condition) {
                        'Depression' => 'Depresi',
                        'Anxiety' => 'Cemas',
                        'Stress' => 'Stres',
                        default => $condition
                        };
                        $maxScore = 42;
                        $barHeight = ($data['score'] / $maxScore) * 180;
                        @endphp
                        <div class="flex flex-col items-center ">
                            {{-- Value --}}
                            <div class="mb-2">
                                <span class="text-sm font-bold text-green-600">{{ $data['score'] }}</span>
                            </div>
                            {{-- Bar --}}
                            <div class="w-12 bg-green-500 rounded-t-lg transition-all duration-500"
                                style="height: {{ $barHeight }}px; min-height: 4px;"></div>
                            {{-- X Axis Label --}}
                            <div class="mt-3 text-center">
                                <span class="text-xs font-medium text-gray-600">{{ $label }}</span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if($type === 'self_efficacy')

            <div class="grid md:grid-cols-2 gap-6">

                {{-- Self Efficacy Score --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">
                        Self-Efficacy
                    </h3>

                    <div class="text-center">
                        <p class="text-3xl font-bold text-sky-700">
                            {{ $sectionBreakdown['self_efficacy']['score'] }}
                        </p>

                        <p class="text-sm text-gray-500">
                            dari {{ $sectionBreakdown['self_efficacy']['max'] }}
                        </p>

                        <p class="mt-2 text-gray-600">
                            {{ round($sectionBreakdown['self_efficacy']['percentage'],1) }}%
                        </p>
                    </div>
                </div>


                {{-- Well Being Score --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">
                        Psychological Well-Being
                    </h3>

                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">
                            {{ $sectionBreakdown['well_being']['score'] }}
                        </p>

                        <p class="text-sm text-gray-500">
                            dari {{ $sectionBreakdown['well_being']['max'] }}
                        </p>

                        <p class="mt-2 text-gray-600">
                            {{ round($sectionBreakdown['well_being']['percentage'],1) }}%
                        </p>
                    </div>
                </div>

            </div>

            @endif

            {{-- history kuis --}}
            @php
            // Get history data dengan pagination - ambil SEMUA kuis dengan tipe yang sama KECUALI current result
            $historyPaginator = \App\Models\QuizResult::where('user_id', Auth::id())
                ->where('quiz_type', $result->quiz_type)
                ->where('id', '!=', $result->id) // Exclude current result
                ->latest('completed_at')
                ->paginate(5, ['*'], 'history_page');
            @endphp

            @if($historyPaginator->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Kuisioner Terakhir</h3>
                <div class="space-y-3">
                    @foreach($historyPaginator as $historyItem)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-sky-50 transition">
                        <div class="flex items-center gap-4">
                            {{-- Icon dan Type --}}
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ $historyItem->icon }}</span>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $historyItem->type_label }}</p>
                                    <p class="text-sm text-gray-500">{{ $historyItem->formatted_date }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Hasil --}}
                        <div class="flex items-center gap-4">
                            <span class="px-4 py-2 rounded-full text-sm font-semibold text-center min-w-24
                                {{ $historyItem->history_category === 'tinggi' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $historyItem->history_result }}
                            </span>
                            <a href="{{ route('quiz.resultById', ['id' => $historyItem->id, 'type' => $historyItem->quiz_type]) }}"
                               class="text-sky-600 hover:text-sky-700 font-semibold text-sm whitespace-nowrap">
                                Lihat →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($historyPaginator->hasPages())
                <div class="mt-4 flex justify-center">
                    {{ $historyPaginator->appends(['page' => request()->page])->links() }}
                </div>
                @endif
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="grid md:grid-cols-1 gap-4">
                <a href="{{ route('quiz.start', $result->quiz_type) }}"
                    class="flex items-center justify-center gap-2 bg-sky-700 hover:bg-sky-800 text-white font-semibold py-4 px-6 rounded-xl shadow-md transition transform hover:scale-[1.01] active:scale-[0.98]">
                    Kerjakan Lagi
                </a>
                <a href="{{ route('home') }}"
                    class="flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-sky-700 font-semibold py-4 px-6 rounded-xl shadow-md border-2 border-sky-300 transition transform hover:scale-[1.01] active:scale-[0.98]">
                    Kembali ke Home
                </a>
            </div>

        </section>
    </div>
</div>
@endsection