<div>
    @php
        $sun = 'imgs/sun.png';
        $moon = 'imgs/moon.png';
    @endphp
    <button @click="darkMode = !darkMode
        $refs.icon.classList.remove('icon-animate');
        void $refs.icon.offsetWidth;
        $refs.icon.classList.add('icon-animate');">
       <img x-ref="icon" alt="Toggle dark/light mode" class="ml-4 inline-flex items-center justify-center" width="30px" height="30px" :src="!darkMode ? '{{url($sun)}}' : '{{url($moon)}}'">
       
    </button>
    <style>
        @keyframes icon-in {
        0%   { opacity: 0; transform: scale(0.8) rotate(-10deg); }
        60%  { opacity: 1; transform: scale(1.1) rotate(6deg); }
        100% { opacity: 1; transform: scale(1) rotate(0); }
    }

    .icon-animate {
        animation: icon-in 250ms ease-out;
    }
    </style>

</div>