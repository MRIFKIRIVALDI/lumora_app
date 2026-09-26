import './bootstrap';
import QRCode from 'qrcode';
import { BrowserQRCodeReader } from '@zxing/browser';

document.querySelector('#showPassword')?.addEventListener('click',()=>{const input=document.querySelector('#password'); input.type=input.type==='password'?'text':'password'});
document.querySelectorAll('[data-email]').forEach(button=>button.addEventListener('click',()=>{document.querySelector('input[name=email]').value=button.dataset.email; document.querySelector('input[name=password]').value='password'}));
const themeToggle=document.querySelector('#themeToggle');
const syncThemeIcon=()=>{const icon=document.querySelector('.theme-icon');if(icon)icon.textContent=document.documentElement.classList.contains('dark')?'â˜€':'â˜¾'}; syncThemeIcon();
themeToggle?.addEventListener('click',()=>{document.documentElement.classList.toggle('dark');localStorage.setItem('lumora-theme',document.documentElement.classList.contains('dark')?'dark':'light');syncThemeIcon()});
document.querySelector('#accountThemeToggle')?.addEventListener('click',()=>themeToggle?.click());
const notificationButton=document.querySelector('#notificationButton'),notificationDropdown=document.querySelector('#notificationDropdown');
notificationButton?.addEventListener('click',event=>{event.stopPropagation();const open=notificationDropdown.classList.toggle('open');notificationButton.setAttribute('aria-expanded',String(open))});
document.addEventListener('click',event=>{if(notificationDropdown&&!notificationDropdown.contains(event.target)){notificationDropdown.classList.remove('open');notificationButton?.setAttribute('aria-expanded','false')}});
if('serviceWorker' in navigator) window.addEventListener('load',()=>navigator.serviceWorker.register('/service-worker.js'));

const clock=document.querySelector('#liveClock'); if(clock) setInterval(()=>clock.textContent=new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}).replace('.',':'),1000);
document.querySelectorAll('.attendance-form').forEach(form=>form.addEventListener('submit',event=>{
    if(form.dataset.ready==='true') return;
    event.preventDefault(); const button=form.querySelector('button'); const status=document.querySelector('#locationStatus');
    button.classList.add('loading'); status.textContent='Sedang mengambil lokasi perangkatâ€¦';
    if(!navigator.geolocation){ status.textContent='Perangkat tidak mendukung lokasi. Presensi dikirim tanpa koordinat.'; form.dataset.ready='true'; form.submit(); return; }
    navigator.geolocation.getCurrentPosition(position=>{ form.querySelector('[name=latitude]').value=position.coords.latitude; form.querySelector('[name=longitude]').value=position.coords.longitude; status.textContent=`Lokasi ditemukan (akurasi Â±${Math.round(position.coords.accuracy)} m).`; form.dataset.ready='true'; form.submit(); },()=>{ status.textContent='Izin lokasi ditolak. Aktifkan lokasi browser lalu coba kembali.'; button.classList.remove('loading'); },{enableHighAccuracy:true,timeout:10000,maximumAge:0});
}));

const analytics=document.querySelector('.analytics-section');
if(analytics){
 const data=JSON.parse(analytics.dataset.charts), colors={blue:'#5b57cf',light:'#eeedff',orange:'#f59e0b',purple:'#8b5cf6',red:'#ef4444',green:'#10b981',grid:'#e8edf5',text:'#7b8798'};
 const setup=id=>{const c=document.querySelector(id),dpr=devicePixelRatio||1,w=c.clientWidth,h=Number(c.getAttribute('height'));c.width=w*dpr;c.height=h*dpr;const x=c.getContext('2d');x.scale(dpr,dpr);return{x,w,h}};
 const line=setup('#lineChart'); {const{x,w,h}=line,p=28,vals=data.weekly,labels=['Sen','Sel','Rab','Kam','Jum','Sab','Min']; x.strokeStyle=colors.grid;x.lineWidth=1;for(let i=0;i<4;i++){let y=p+i*(h-2*p)/3;x.beginPath();x.moveTo(p,y);x.lineTo(w-p,y);x.stroke()} const pts=vals.map((v,i)=>[p+i*(w-2*p)/(vals.length-1),h-p-(v-75)/25*(h-2*p)]);let g=x.createLinearGradient(0,p,0,h);g.addColorStop(0,'#2563eb45');g.addColorStop(1,'#2563eb00');x.beginPath();x.moveTo(pts[0][0],h-p);pts.forEach(q=>x.lineTo(...q));x.lineTo(pts.at(-1)[0],h-p);x.fillStyle=g;x.fill();x.beginPath();pts.forEach((q,i)=>i?x.lineTo(...q):x.moveTo(...q));x.strokeStyle=colors.blue;x.lineWidth=3;x.stroke();x.font='11px DM Sans';x.fillStyle=colors.text;x.textAlign='center';pts.forEach((q,i)=>{x.beginPath();x.arc(...q,4,0,Math.PI*2);x.fillStyle='#fff';x.fill();x.strokeStyle=colors.blue;x.stroke();x.fillStyle=colors.text;x.fillText(labels[i],q[0],h-7)})}
 const pie=setup('#pieChart'); {const{x,w,h}=pie,vals=data.attendance.map(v=>v||0),total=vals.reduce((a,b)=>a+b,0)||1,cs=[colors.blue,colors.orange,colors.purple,colors.red];let a=-Math.PI/2;vals.forEach((v,i)=>{const n=a+(v/total)*Math.PI*2;x.beginPath();x.arc(w/2,h/2-5,65,a,n);x.arc(w/2,h/2-5,39,n,a,true);x.closePath();x.fillStyle=cs[i];x.fill();a=n});x.fillStyle='#172033';x.font='800 22px Manrope';x.textAlign='center';x.fillText(total,w/2,h/2-4);x.fillStyle=colors.text;x.font='10px DM Sans';x.fillText('Murid',w/2,h/2+13)}
 const col=setup('#columnChart'); {const{x,w,h}=col,vals=data.finance,max=Math.max(...vals,1),labels=['Lunas','Menunggu'],cs=[colors.green,colors.orange],bw=55;vals.forEach((v,i)=>{let bh=(v/max)*(h-60),px=w/2+(i-.5)*90-bw/2,py=h-30-bh;x.fillStyle=cs[i];x.beginPath();x.roundRect(px,py,bw,bh,8);x.fill();x.fillStyle=colors.text;x.font='10px DM Sans';x.textAlign='center';x.fillText(labels[i],px+bw/2,h-10);x.fillStyle='#172033';x.font='700 10px DM Sans';x.fillText('Rp '+Math.round(v/1000)+'k',px+bw/2,py-8)})}
 const bar=setup('#barChart'); {const{x,w,h}=bar,vals=[88,74,69,81],labels=['Kelas X','Kelas XI','Kelas XII','Ekstrakurikuler'];vals.forEach((v,i)=>{let y=18+i*39;x.fillStyle='#eef2f7';x.beginPath();x.roundRect(105,y,w-130,15,8);x.fill();x.fillStyle=[colors.blue,colors.purple,colors.green,colors.orange][i];x.beginPath();x.roundRect(105,y,(w-130)*v/100,15,8);x.fill();x.fillStyle='#566174';x.font='11px DM Sans';x.textAlign='right';x.fillText(labels[i],95,y+12);x.textAlign='left';x.fillText(v+'%',110+(w-130)*v/100,y+12)})}
}

const stationScreen=document.querySelector('.station-screen');
if(stationScreen){
 const stationId=stationScreen.dataset.stationId, tokenUrl=stationScreen.dataset.tokenUrl, canvas=document.querySelector('#dynamicQr'), loading=document.querySelector('#qrLoading'), progress=document.querySelector('#qrProgress'), short=document.querySelector('#tokenShort'), status=document.querySelector('#wsStatus'), clockEl=document.querySelector('#stationClock');
 const draw=payload=>{QRCode.toCanvas(canvas,payload.scan_url,{width:340,margin:2,color:{dark:'#102a66',light:'#ffffff'}},()=>{loading.hidden=true});short.textContent=payload.scan_url.split('/').pop().slice(0,8).toUpperCase();progress.style.animation='none';requestAnimationFrame(()=>{progress.style.animation='qrCountdown 1s linear'});};
 window.Echo.channel(`qr-station.${stationId}`).listen('.qr.rotated',event=>draw(event));
 const connection=window.Echo.connector.pusher.connection; connection.bind('connected',()=>{status.classList.add('connected');status.innerHTML='<i></i> Reverb terhubung'}); connection.bind('disconnected',()=>{status.classList.remove('connected');status.innerHTML='<i></i> Reverb terputus'});
 const rotate=async()=>{try{const response=await fetch(tokenUrl,{headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest'},cache:'no-store'});if(response.status===410){location.href='/presensi';return}if(!response.ok)throw new Error();draw(await response.json())}catch{status.classList.remove('connected');status.innerHTML='<i></i> Menunggu server Reverbâ€¦'}};
 rotate(); setInterval(rotate,1000); setInterval(()=>clockEl.textContent=new Date().toLocaleTimeString('id-ID').replaceAll('.',':'),1000);
}

const qrCamera=document.querySelector('#qrCamera');
if(qrCamera){
 const start=document.querySelector('#startQrCamera'),stop=document.querySelector('#stopQrCamera'),message=document.querySelector('#cameraMessage');
 const reader=new BrowserQRCodeReader(); let controls=null,found=false;
 const stopCamera=()=>{controls?.stop();controls=null;qrCamera.srcObject?.getTracks().forEach(track=>track.stop());start.disabled=false;stop.disabled=true};
 start.addEventListener('click',async()=>{try{start.disabled=true;message.textContent='Mengaktifkan kamera belakang…';controls=await reader.decodeFromConstraints({video:{facingMode:{ideal:'environment'}}},qrCamera,(result,error)=>{if(!result||found)return;const value=result.getText();if(!value.includes('/presensi/scan/')){message.textContent='QR terbaca, tetapi bukan QR presensi Lumora.';return}found=true;message.textContent='QR ditemukan. Membuka konfirmasi…';stopCamera();location.href=value});stop.disabled=false;message.textContent='Kamera aktif. Arahkan ke QR presensi Lumora.'}catch(error){start.disabled=false;message.textContent='Kamera tidak dapat dibuka. Periksa izin kamera dan gunakan HTTPS atau localhost.'}});
 stop.addEventListener('click',()=>{stopCamera();message.textContent='Kamera dimatikan.'});
 window.addEventListener('beforeunload',stopCamera);
}

