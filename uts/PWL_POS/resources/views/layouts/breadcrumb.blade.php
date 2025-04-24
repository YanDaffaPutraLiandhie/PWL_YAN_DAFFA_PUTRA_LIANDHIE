@php
    use Illuminate\Support\Arr;
@endphp

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ data_get($breadcrumb, 'title', 'Profil Saya') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @foreach (data_get($breadcrumb, 'list', []) as $item)
                        @php
                            $label = is_array($item) ? ($item['label'] ?? $item['title'] ?? 'Item') : $item;
                            $url = is_array($item) ? ($item['url'] ?? '#') : '#';
                        @endphp
                        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                            @if (!$loop->last)
                                <a href="{{ $url }}">{{ $label }}</a>
                            @else
                                {{ $label }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>
