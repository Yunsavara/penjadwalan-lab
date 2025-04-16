@props([
    'background' => 'white',
    'color' => 'white',
    'counter' => 0,
    'title' => 'Counter Title'
])

<div class="col-12 mb-3 col-md-3 shadow-sm border rounded p-2 bg-{{ $background }}">
    <h4 class="text-end fw-bold text-{{ $color }}">{{ $counter }}</h4>
    <h5 class="fw-bold mb-0 text-uppercase text-{{ $color }}">{{ $title }}</h5>
</div>
