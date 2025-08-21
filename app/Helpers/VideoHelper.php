<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class VideoHelper
{
    /**
     * Parse video URL and return embed information
     */
    public static function parseVideoUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        $isYoutube = Str::contains($url, ['youtube.com', 'youtu.be']);
        $isDrive = Str::contains($url, 'drive.google.com/file/d/');
        $isVimeo = Str::contains($url, 'vimeo.com');

        $result = [
            'type' => 'unknown',
            'embed_url' => $url,
            'original_url' => $url,
            'id' => null,
        ];

        if ($isYoutube) {
            $youtubeId = null;
            if (Str::contains($url, 'youtu.be/')) {
                $youtubeId = Str::after($url, 'youtu.be/');
                $youtubeId = Str::before($youtubeId, '?');
            } elseif (Str::contains($url, 'v=')) {
                $youtubeId = Str::after($url, 'v=');
                $youtubeId = Str::before($youtubeId, '&');
            }

            if ($youtubeId) {
                $result = [
                    'type' => 'youtube',
                    'embed_url' => "https://www.youtube.com/embed/{$youtubeId}",
                    'original_url' => $url,
                    'id' => $youtubeId,
                ];
            }
        } elseif ($isDrive) {
            $driveId = Str::between($url, '/file/d/', '/');
            if ($driveId) {
                $result = [
                    'type' => 'drive',
                    'embed_url' => "https://drive.google.com/file/d/{$driveId}/preview",
                    'original_url' => $url,
                    'id' => $driveId,
                ];
            }
        } elseif ($isVimeo) {
            $vimeoId = Str::after($url, 'vimeo.com/');
            $vimeoId = Str::before($vimeoId, '?');
            if ($vimeoId) {
                $result = [
                    'type' => 'vimeo',
                    'embed_url' => "https://player.vimeo.com/video/{$vimeoId}",
                    'original_url' => $url,
                    'id' => $vimeoId,
                ];
            }
        }

        return $result;
    }

    /**
     * Check if URL is a valid video URL
     */
    public static function isValidVideoUrl($url)
    {
        if (empty($url)) {
            return false;
        }

        $parsed = self::parseVideoUrl($url);

        return $parsed && $parsed['type'] !== 'unknown';
    }

    /**
     * Get video thumbnail URL
     */
    public static function getThumbnailUrl($url)
    {
        $parsed = self::parseVideoUrl($url);

        if (! $parsed) {
            return null;
        }

        switch ($parsed['type']) {
            case 'youtube':
                return "https://img.youtube.com/vi/{$parsed['id']}/maxresdefault.jpg";
            case 'vimeo':
                // Vimeo requires API call for thumbnail, return null for now
                return null;
            default:
                return null;
        }
    }

    /**
     * Get video duration (requires API calls, placeholder for now)
     */
    public static function getVideoDuration($url)
    {
        // This would require API calls to YouTube/Vimeo APIs
        // For now, return null
        return null;
    }
}
