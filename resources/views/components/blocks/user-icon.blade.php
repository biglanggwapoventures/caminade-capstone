@if(Auth::user()->is('admin'))
<i class="fas fa-gem"></i>
@elseif(Auth::user()->is('doctor'))
<i class="fas fa-user-md"></i>
@else(Auth::user()->is('doctor'))
<i class="fas fa-user-circle"></i>
@endif

{{ Auth::user()->username }}
