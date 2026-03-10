@extends('layouts.app')

@section('title', $type === 'family_social' ? 'Family Social Factor - Mental Health' : 'Self Efficacy - Mental Health')

@section('content')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('quiz.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                    ← Kembali
                </a>
                <div class="text-gray-600">
                    Pertanyaan <span class="font-bold text-blue-700">{{ $questions->count() }}</span>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-blue-700">
                {{ $type === 'family_social' ? 'Family Social Factor' : 'Self Efficacy' }}
            </h1>
            <p class="text-gray-600 mt-2">
                @if($type === 'family_social')
                    Kuisioner ini terdiri dari 2 bagian: Family Social dan DASS-21. Jawab semua pertanyaan dengan jujur sesuai dengan kondisi Anda.
                @else
                    Jawab pertanyaan berikut sesuai dengan keyakinan diri Anda
                @endif
            </p>
        </div>

        <!-- Quiz Form -->
        <form action="{{ route('quiz.submit', $type) }}" method="POST" class="space-y-8">
            @csrf

            @if($type === 'family_social')
                <!-- Group questions by scale_type for family_social quiz -->
                @php
                    $familySocialQuestions = $questions->where('scale_type', 'likert_5')->values();
                    $dass21Questions = $questions->where('scale_type', 'dass21')->values();
                @endphp

                <!-- Family Social Section -->
                @if($familySocialQuestions->count() > 0)
                    <div class="bg-blue-50 rounded-xl border-2 border-blue-200 p-6 mb-8">
                        <h2 class="text-2xl font-bold text-blue-700 mb-2">Family Social</h2>
                        <p class="text-blue-600 mb-4">Jawab pertanyaan berikut sesuai dengan kondisi keluarga Anda</p>
                    </div>

                    @foreach($familySocialQuestions as $index => $question)
                        <?php $scaleOptions = $question->getScaleOptions(); ?>
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <div class="mb-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    Pertanyaan {{ $index + 1 }}
                                </span>
                            </div>
                            <p class="text-xl text-gray-800 mb-6">{{ $question->question_text }}</p>

                            <div class="space-y-3">
                                @foreach($scaleOptions as $option)
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                        <input type="radio"
                                               name="answers[{{ $question->id }}]"
                                               value="{{ $option['value'] }}"
                                               required
                                               class="w-5 h-5 text-blue-600">
                                        <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- DASS-21 Section -->
                @if($dass21Questions->count() > 0)
                    <div class="bg-green-50 rounded-xl border-2 border-green-200 p-6 mb-8">
                        <h2 class="text-2xl font-bold text-green-700 mb-2">DASS-21</h2>
                        <p class="text-green-600 mb-4">Jawab pertanyaan berikut sesuai dengan kondisi Anda dalam minggu terakhir</p>
                    </div>

                    @foreach($dass21Questions as $index => $question)
                        <?php $scaleOptions = $question->getScaleOptions(); ?>
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <div class="mb-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    Pertanyaan {{ $index + 1 }}
                                </span>
                            </div>
                            <p class="text-xl text-gray-800 mb-6">{{ $question->question_text }}</p>

                            <div class="space-y-3">
                                @foreach($scaleOptions as $option)
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 transition">
                                        <input type="radio"
                                               name="answers[{{ $question->id }}]"
                                               value="{{ $option['value'] }}"
                                               required
                                               class="w-5 h-5 text-green-600">
                                        <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

            @else
                <!-- Self Efficacy Quiz - Group questions by scale_type -->
                @php
                    $selfEfficacyQuestions = $questions->where('scale_type', 'likert_4')->values();
                    $wellBeingQuestions = $questions->where('scale_type', 'likert_7')->values();
                @endphp

                <!-- Self Efficacy Section -->
                @if($selfEfficacyQuestions->count() > 0)
                    <div class="bg-blue-50 rounded-xl border-2 border-blue-200 p-6 mb-8">
                        <h2 class="text-2xl font-bold text-blue-700 mb-2">Self Efficacy</h2>
                        <p class="text-blue-600 mb-4">Jawab pertanyaan berikut sesuai dengan keyakinan diri Anda</p>
                    </div>

                    @foreach($selfEfficacyQuestions as $index => $question)
                        <?php $scaleOptions = $question->getScaleOptions(); ?>
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <div class="mb-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    Pertanyaan {{ $index + 1 }}
                                </span>
                            </div>
                            <p class="text-xl text-gray-800 mb-6">{{ $question->question_text }}</p>

                            <div class="space-y-3">
                                @foreach($scaleOptions as $option)
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                        <input type="radio"
                                               name="answers[{{ $question->id }}]"
                                               value="{{ $option['value'] }}"
                                               required
                                               class="w-5 h-5 text-blue-600">
                                        <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Well-being Section -->
                @if($wellBeingQuestions->count() > 0)
                    <div class="bg-green-50 rounded-xl border-2 border-green-200 p-6 mb-8">
                        <h2 class="text-2xl font-bold text-green-700 mb-2">Well-being</h2>
                        <p class="text-green-600 mb-4">Jawab pertanyaan berikut sesuai dengan kondisi Anda</p>
                    </div>

                    @foreach($wellBeingQuestions as $index => $question)
                        <?php $scaleOptions = $question->getScaleOptions(); ?>
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <div class="mb-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    Pertanyaan {{ $index + 1 }}
                                </span>
                            </div>
                            <p class="text-xl text-gray-800 mb-6">{{ $question->question_text }}</p>

                            <div class="space-y-3">
                                @foreach($scaleOptions as $option)
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 transition">
                                        <input type="radio"
                                               name="answers[{{ $question->id }}]"
                                               value="{{ $option['value'] }}"
                                               required
                                               class="w-5 h-5 text-green-600">
                                        <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif

            <!-- Submit Button -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 rounded-lg shadow-lg transition transform hover:scale-105">
                    Selesaikan Kuisioner
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Add visual feedback for selected answers
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove border from all options in the same question
        const questionContainer = this.closest('.space-y-3');
        questionContainer.querySelectorAll('label').forEach(label => {
            label.classList.remove('border-blue-500', 'bg-blue-50');
            label.classList.add('border-gray-200');
        });
        
        // Add border to selected option
        const selectedLabel = this.closest('label');
        selectedLabel.classList.remove('border-gray-200');
        selectedLabel.classList.add('border-blue-500', 'bg-blue-50');
    });
});
</script>
@endsection
