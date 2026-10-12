@if(session('ok'))
    <div class="alert alert-success" style="margin-bottom:12px">{{ session('ok') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-warn" style="margin-bottom:12px">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-warn" style="margin-bottom:12px">
        {{ $errors->first() }}
    </div>
@endif
