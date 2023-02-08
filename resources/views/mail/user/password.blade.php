<x-mail::message>
    # Your account has been successfully created! Your password: {{ $password }}

    <x-mail::button :url="route('login')"> Log In </x-mail::button>

    Thanks for registration,<br />
    {{ config('app.name') }}
</x-mail::message>
