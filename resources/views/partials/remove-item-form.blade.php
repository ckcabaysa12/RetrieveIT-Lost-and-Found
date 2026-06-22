<form method="POST" action="{{ route('items.destroy', $item) }}" class="remove-item-form"
    onsubmit="return confirm('Remove this listing permanently? This cannot be undone.');">
    @csrf
    <button type="submit" class="{{ $class ?? 'btn btn-lf-danger-outline btn-sm' }}">
        <i class="bi bi-trash me-1"></i>{{ $label ?? 'Remove' }}
    </button>
</form>
