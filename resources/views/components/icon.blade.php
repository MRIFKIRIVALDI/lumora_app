@props(['name','size'=>20])
@php
$icons=[
'home'=>'<path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v10h13V10M9 20v-6h6v6"/>',
'compass'=>'<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2.1 4.9-4.9 2.1 2.1-4.9 4.9-2.1Z"/>',
'classes'=>'<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H11v16H6.5A2.5 2.5 0 0 0 4 21.5v-16ZM20 5.5A2.5 2.5 0 0 0 17.5 3H13v16h4.5a2.5 2.5 0 0 1 2.5 2.5v-16Z"/>',
'chat'=>'<path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z"/><path d="M8 9h8M8 13h5"/>',
'user'=>'<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
'users'=>'<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
'calendar'=>'<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>',
'book'=>'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5v14Z"/><path d="M8 7h8M8 11h6"/>',
'check'=>'<path d="m5 12 4 4L19 6"/>',
'clipboard'=>'<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V2h6v2M9 10h6M9 14h6M9 18h4"/>',
'wallet'=>'<path d="M4 6h15a2 2 0 0 1 2 2v11H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h13"/><path d="M15 11h6v4h-6a2 2 0 0 1 0-4Z"/>',
'news'=>'<path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h8M8 17h5"/>',
'settings'=>'<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1v.1h-4v-.1a1.7 1.7 0 0 0-1.1-1.6 1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1-.4h-.1v-4H3A1.7 1.7 0 0 0 4.6 8.5a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1v-.1h4V3a1.7 1.7 0 0 0 1.1 1.6 1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 9c.15.38.37.72.6 1 .28.3.65.4 1 .4h.1v4H21a1.7 1.7 0 0 0-1.6.6Z"/>',
'moon'=>'<path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8Z"/>',
'bell'=>'<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>',
'logout'=>'<path d="M10 17l5-5-5-5M15 12H3M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>',
'arrow-left'=>'<path d="m15 18-6-6 6-6"/>',
'assignment'=>'<path d="M9 4h6M9 2h6v4H9z"/><path d="M7 4H5a2 2 0 0 0-2 2v15h18V6a2 2 0 0 0-2-2h-2M8 12h8M8 16h5"/>',
'quiz'=>'<circle cx="12" cy="12" r="9"/><path d="M9.8 9a2.3 2.3 0 1 1 3.5 2c-1 .6-1.3 1-1.3 2M12 17h.01"/>',
'qr'=>'<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM18 18h3v3h-3zM18 14h3M14 18v3"/>',
'palette'=>'<path d="M12 3a9 9 0 0 0 0 18h1.5a2 2 0 0 0 0-4H12a2 2 0 0 1 0-4h4.5A4.5 4.5 0 0 0 21 8.5C21 5.5 17 3 12 3Z"/><circle cx="7.5" cy="10.5" r=".7" fill="currentColor"/><circle cx="9.5" cy="7" r=".7" fill="currentColor"/><circle cx="14" cy="6.5" r=".7" fill="currentColor"/><circle cx="17" cy="9" r=".7" fill="currentColor"/>',
'language'=>'<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>',
'help'=>'<circle cx="12" cy="12" r="9"/><path d="M9.8 9a2.3 2.3 0 1 1 3.5 2c-1 .6-1.3 1-1.3 2M12 17h.01"/>',
'chevron-right'=>'<path d="m9 18 6-6-6-6"/>',
];
@endphp
<svg {{ $attributes->merge(['class'=>'ui-icon','width'=>$size,'height'=>$size,'viewBox'=>'0 0 24 24','fill'=>'none','stroke'=>'currentColor','stroke-width'=>'1.8','stroke-linecap'=>'round','stroke-linejoin'=>'round','aria-hidden'=>'true']) }}>{!! $icons[$name]??$icons['home'] !!}</svg>
