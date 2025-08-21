@props(['url', 'title' => __('general.lesson_video'), 'class' => ''])

@php
    use App\Helpers\VideoHelper;
    $videoInfo = VideoHelper::parseVideoUrl($url);
@endphp

@if ($videoInfo && $videoInfo['type'] !== 'unknown')
    <div class="w-100 rounded overflow-hidden {{ $class }}" style="height: auto; min-height: 400px;">
        <iframe src="{{ $videoInfo['embed_url'] }}" title="{{ $title }}" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen style="width: 100%; height: 100%; min-height: 400px;">
        </iframe>
    </div>

    <div class="mt-2">
        <small class="text-muted">
            <i class="bi bi-play-circle mr-1"></i>
            @switch($videoInfo['type'])
                @case('youtube')
                    {{ __('general.youtube_video') }}
                @break

                @case('drive')
                    {{ __('general.google_drive_video') }}
                @break

                @case('vimeo')
                    {{ __('general.vimeo_video') }}
                @break

                @default
                    {{ __('general.video') }}
            @endswitch
        </small>
        <a href="{{ $videoInfo['original_url'] }}" target="_blank" class="btn btn-outline-primary btn-sm ml-2">
            <i class="bi bi-box-arrow-up-right mr-1"></i>{{ __('general.open_in_new_tab') }}
        </a>
    </div>
@else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle mr-2"></i>
        {{ __('general.cannot_embed_video') }}
        <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm ml-2">
            <i class="bi bi-box-arrow-up-right mr-1"></i>{{ __('general.watch_video') }}
        </a>
    </div>
@endif
