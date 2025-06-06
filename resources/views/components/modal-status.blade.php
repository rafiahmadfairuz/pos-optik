@props([
    'id' => 'alertModal',
    'type' => 'success',
    'title' => 'Pemberitahuan',
    'message' => 'Pesan default',
])

@php
    $colors = [
        'success' => ['btn' => 'btn-success', 'icon' => 'bi-check-circle-fill', 'title' => 'Sukses!'],
        'error' => ['btn' => 'btn-danger', 'icon' => 'bi-x-circle-fill', 'title' => 'Gagal!'],
        'warning' => ['btn' => 'btn-warning text-dark', 'icon' => 'bi-exclamation-triangle-fill', 'title' => 'Peringatan!'],
        'info' => ['btn' => 'btn-info text-dark', 'icon' => 'bi-info-circle-fill', 'title' => 'Info'],
    ];

    $color = $colors[$type] ?? $colors['info'];
    $modalTitle = $title ?? $color['title'];
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm border-0">
            <div class="modal-header border-0 justify-content-center">
                <h5 class="modal-title d-flex align-items-center gap-2 text-dark fw-bold" id="{{ $id }}Label">
                    <i class="bi {{ $color['icon'] }}" style="font-size: 1.8rem; color: inherit;"></i>
                    {{ $modalTitle }}
                </h5>
                <button type="button" class="btn-close btn-close-gray position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center text-secondary fs-6 px-4">
                {{ $message }}
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn {{ $color['btn'] }} px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
