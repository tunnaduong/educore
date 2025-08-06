@php
    $locale = Session::get('locale', config('app.locale'));

    if (in_array($locale, ['vi', 'en', 'zh'])) {
        app()->setLocale($locale);
    }
@endphp
