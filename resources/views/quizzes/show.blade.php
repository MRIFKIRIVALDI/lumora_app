@extends('layouts.app')
@section('title', $quiz->title.' — Lumora')
@section('heading', 'Kuis Pilihan Ganda')

@section('content')
<a href="{{ route('learning.show',$quiz->class_session_id) }}" class="back-link">← Kembali ke Pertemuan</a>
<section class="quiz-hero">
    <div>
        <p class="eyebrow">{{ $quiz->subject }} · {{ $quiz->meeting_title }}</p>
        <h2>{{ $quiz->title }}</h2>
        <span>{{ $questions->count() }} soal · {{ $quiz->duration_minutes }} menit · {{ ucfirst($quiz->status) }}</span>
    </div>
</section>

@if(auth()->user()->role === 'teacher' && auth()->id() == $quiz->teacher_id)
<div class="quiz-layout">
    <section class="card">
        <div class="section-head"><div><p class="eyebrow">BANK SOAL</p><h2>Soal pilihan ganda</h2></div><span>{{ $questions->sum('points') }} poin</span></div>
        @forelse($questions as $index => $question)
            <article class="question-review">
                <b>{{ $index + 1 }}. {{ $question->question_text }}</b>
                @foreach($question->options as $key => $option)
                    <p class="{{ $question->correct_option === $key ? 'correct' : '' }}">{{ $key }}. {{ $option }}</p>
                @endforeach
                <small>Jawaban: {{ $question->correct_option }} · {{ $question->points }} poin</small>
            </article>
        @empty
            <p class="empty">Belum ada soal. Tambahkan soal pertama melalui formulir.</p>
        @endforelse
    </section>
    <aside class="card quiz-editor">
        <h3>Tambah soal pilihan ganda</h3>
        <form method="post" action="{{ route('quizzes.questions.store',$quiz->id) }}" class="compact-form">
            @csrf
            <textarea name="question_text" placeholder="Tulis pertanyaan" required>{{ old('question_text') }}</textarea>
            @foreach(['a','b','c','d'] as $option)
                <input name="option_{{ $option }}" value="{{ old('option_'.$option) }}" placeholder="Pilihan {{ strtoupper($option) }}" required>
            @endforeach
            <div class="form-row">
                <select name="correct_option" required>
                    @foreach(['A','B','C','D'] as $option)<option value="{{ $option }}">Jawaban {{ $option }}</option>@endforeach
                </select>
                <input type="number" name="points" value="10" min="1" max="100" required>
            </div>
            <button class="primary-button" type="submit">Simpan soal</button>
        </form>
        <h3 class="attempt-title">Hasil murid</h3>
        @forelse($attempts as $attemptRow)
            <div class="attempt-row"><b>{{ $attemptRow->name }}</b><span>{{ $attemptRow->score }}/{{ $attemptRow->max_score }}</span></div>
        @empty
            <p class="empty compact">Belum ada murid mengerjakan.</p>
        @endforelse
    </aside>
</div>
@elseif(auth()->user()->role === 'student')
<section class="card student-quiz {{ !$attempt&&!$questions->isEmpty()?'quiz-secure':'' }}">
    @if($attempt)
        <div class="quiz-result"><span>Nilai kuis</span><strong>{{ $attempt->score }}/{{ $attempt->max_score }}</strong><p>Dikumpulkan {{ \Carbon\Carbon::parse($attempt->submitted_at)->locale('id')->translatedFormat('d F Y, H:i') }}</p></div>
    @elseif($questions->isEmpty())
        <p class="empty">Guru belum menambahkan soal pada kuis ini.</p>
    @else
        <div class="quiz-focus-warning" hidden><b>Kuis disamarkan</b><span>Kembali ke tab ini untuk melanjutkan.</span></div>
        <form method="post" action="{{ route('quizzes.submit',$quiz->id) }}" autocomplete="off">
            @csrf
            @foreach($questions as $index => $question)
                <fieldset class="quiz-question">
                    <legend>{{ $index + 1 }}. {{ $question->question_text }} <small>{{ $question->points }} poin</small></legend>
                    @foreach($question->options as $key => $option)
                        <label><input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" required><span><b>{{ $key }}</b>{{ $option }}</span></label>
                    @endforeach
                </fieldset>
            @endforeach
            <button class="primary-button" type="submit">Kumpulkan dan lihat nilai</button>
        </form>
    @endif
</section>
@else
<section class="card"><p>Informasi kuis tersedia. Jawaban hanya dapat dikirim melalui akun murid.</p></section>
@endif
@endsection
