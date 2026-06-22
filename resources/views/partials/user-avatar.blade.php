@if($url = $user->profileImageUrl())
    <img src="{{ $url }}" alt="{{ $user->name }}" class="rounded-circle object-fit-cover {{ $class ?? '' }}" style="width:{{ $size ?? 72 }}px;height:{{ $size ?? 72 }}px;">
@else
    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center {{ $class ?? '' }}" style="width:{{ $size ?? 72 }}px;height:{{ $size ?? 72 }}px">
        <i class="bi bi-person text-muted" style="font-size:{{ ($size ?? 72) * 0.45 }}px"></i>
    </div>
@endif
