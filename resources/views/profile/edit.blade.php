@extends($layout)

@section('title', 'Profil Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @livewire('user.profile-page')
</div>
@endsection