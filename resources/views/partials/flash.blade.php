@if (session('sukses'))<div class="flash ok">{{ session('sukses') }}</div>@endif
@if (session('galat'))<div class="flash err">{{ session('galat') }}</div>@endif
@if ($errors->any())
    <div class="flash err">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
@endif
