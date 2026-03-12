@component('mail::message')
# Kedves {{ $user->name }}!

Elfelejtetted a jelszavad? Semmi gond, az alábbi gombra kattintva beállíthatsz egy újat:

@component('mail::button', ['url' => $url])
Jelszó visszaállítása
@endcomponent

Ha nem te kérted a visszaállítást, kérjük, hagyd figyelmen kívül ezt a levelet. 
A link 60 percig érvényes.

Üdvözlettel,<br>
{{ config('app.name') }} csapat
@endcomponent