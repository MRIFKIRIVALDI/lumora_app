@php $dayNames=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; @endphp
<section class="card full-calendar">
    <div class="calendar-toolbar">
        <div><p class="eyebrow">KALENDER JADWAL</p><h2>{{ $calendarMonth->locale('id')->translatedFormat('F Y') }}</h2></div>
        <div class="calendar-nav"><a href="{{ route('dashboard',['month'=>$calendarMonth->copy()->subMonth()->format('Y-m')]) }}" aria-label="Bulan sebelumnya"><x-icon name="arrow-left" size="17"/></a><a href="{{ route('dashboard',['month'=>now()->format('Y-m')]) }}">Hari ini</a><a href="{{ route('dashboard',['month'=>$calendarMonth->copy()->addMonth()->format('Y-m')]) }}" aria-label="Bulan berikutnya"><x-icon name="chevron-right" size="17"/></a></div>
    </div>
    <div class="calendar-weekdays">@foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $day)<span>{{ $day }}</span>@endforeach</div>
    <div class="calendar-month-grid">@foreach($calendarDays as $date)@php $lessons=$calendarSchedule->where('day',$dayNames[$date->dayOfWeek]);$events=$calendarEvents->filter(fn($event)=>$date->between(\Carbon\Carbon::parse($event->date_start),\Carbon\Carbon::parse($event->date_end))); @endphp
        <article class="{{ !$date->isSameMonth($calendarMonth)?'outside':'' }} {{ $date->isToday()?'today':'' }}"><time>{{ $date->day }}</time><div>@foreach($lessons->take(2) as $lesson)<span class="lesson" title="{{ $lesson->subject }} · {{ $lesson->class_name }}">{{ substr($lesson->start_time,0,5) }} {{ $lesson->subject }}</span>@endforeach @foreach($events->take(1) as $event)<span class="event" title="{{ $event->title }}">{{ $event->title }}</span>@endforeach @if($lessons->count()+$events->count()>3)<small>+{{ $lessons->count()+$events->count()-3 }} lainnya</small>@endif</div></article>
    @endforeach</div>
</section>
