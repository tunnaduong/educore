@props(['url', 'title' => 'Video bài học', 'class' => ''])

@php
    use App\Helpers\VideoHelper;
    $videoInfo = VideoHelper::parseVideoUrl($url);
@endphp

@if($videoInfo && $videoInfo['type'] !== 'unknown')
    <div class="ratio ratio-16x9 rounded overflow-hidden {{ $class }}">
        <iframe src="{{ $videoInfo['embed_url'] }}" 
            title="{{ $title }}" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>
    </div>
    
    <div class="mt-2">
        <small class="text-muted">
            <i class="bi bi-play-circle me-1"></i>
            @switch($videoInfo['type'])
                @case('youtube')
                    YouTube Video
                    @break
                @case('drive')
                    Google Drive Video
                    @break
                @case('vimeo')
                    Vimeo Video
                    @break
                @default
                    Video
            @endswitch
        </small>
        <a href="{{ $videoInfo['original_url'] }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2">
            <i class="bi bi-box-arrow-up-right me-1"></i>Mở trong tab mới
        </a>
    </div>
@else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Không thể nhúng video này. 
        <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2">
            <i class="bi bi-box-arrow-up-right me-1"></i>Xem video
        </a>
    </div>
@endif 