@props(['size'=>44,'label'=>'Lumora'])
<svg {{ $attributes->merge(['class'=>'lumora-logo','width'=>$size,'height'=>$size,'viewBox'=>'0 0 64 64','role'=>'img','aria-label'=>$label]) }} xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="lumoraLogoGradient" x1="9" y1="8" x2="55" y2="57" gradientUnits="userSpaceOnUse"><stop stop-color="#8B87F4"/><stop offset="1" stop-color="#4A46A4"/></linearGradient>
        <linearGradient id="lumoraLogoGlow" x1="16" y1="12" x2="48" y2="51" gradientUnits="userSpaceOnUse"><stop stop-color="#FFFFFF" stop-opacity=".95"/><stop offset="1" stop-color="#DCD9FF" stop-opacity=".7"/></linearGradient>
    </defs>
    <rect x="3" y="3" width="58" height="58" rx="19" fill="url(#lumoraLogoGradient)"/>
    <path d="M20 19.5h13.5c6.35 0 11.5 5.15 11.5 11.5s-5.15 11.5-11.5 11.5H20" stroke="url(#lumoraLogoGlow)" stroke-width="5" stroke-linecap="round"/>
    <path d="M20 19.5v25M20 44.5h13" stroke="#fff" stroke-width="5" stroke-linecap="round"/>
    <circle cx="48" cy="17" r="3" fill="#FFB75D"/>
</svg>
