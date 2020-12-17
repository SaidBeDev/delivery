
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Liste d'utilisateurs</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Nom complet</th>
                <th class="text-center">Nom d'utilisateur</th>
                <th class="text-center">Ville</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($data['list_users'] as $user)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->index + 1 }}</td>
                        <td>
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    {{-- <div class="widget-content-left mr-3">
                                        <div class="widget-content-left">
                                            <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                                        </div>
                                    </div> --}}
                                    <div class="widget-content-left flex2">
                                        <div class="widget-heading">{{ $user->full_name }}</div>
                                        <div class="widget-subheading opacity-7">{{ $user->profile_type->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{ $user->username }}</td>
                        <td class="text-center">{{ $user->daira->name . ', ' . $user->daira->wilaya->name }}</td>
                        {{-- <td class="text-center">
                            <div class="badge badge-warning">Pending</div>
                        </td> --}}
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal">Supprimer</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-block text-center card-footer">

    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            @if(!empty(session()->has('success')))
                new Noty({
                    type: 'success',
                    theme: 'sunset',
                    text: "{{ session('success') }}"
                }).show();
            @elseif(session()->has('error'))
                new Noty({
                        type: 'error',
                        theme: 'sunset',
                        text: "Une erreur est survenue"
                    }).show();
            @endif
        });
    </script>
@endsection

{{-- Modals --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Voulez-vous vraiment supprimer ce compte?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <a href="{{ route('admin.users.destroy', ['id' => $user->id]) }}" class="btn btn-danger btn-sm">Supprimer</a>
            </div>
        </div>
    </div>
</div>
