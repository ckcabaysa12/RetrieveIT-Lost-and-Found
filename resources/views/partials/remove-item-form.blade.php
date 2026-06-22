<form method="POST" action="{{ route('items.destroy', $item) }}" class="d-inline"
    onsubmit="return confirm('Remove this listing permanently? This cannot be undone.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $class ?? 'btn btn-outline-danger btn-sm' }}">
        <i class="bi bi-trash me-1"></i>{{ $label ?? 'Remove' }}
    </button>
</form>
