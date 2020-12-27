
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Liste des Livreurs</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Livreur</th>
                <th class="text-center">Ville</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($data['list_delivrers'] as $user)
                    <tr>
                        <td class="text-center text-muted font-weight-bold">{{ $user->id }}</td>
                        <td class="text-muted font-weight-bold"><a href="{{ route('admin.getLivredBoxesById', ['id' => $user->id]) }}">{{ $user->full_name }}</a></td>
                        <td class="text-center">{{ $user->daira->name . ', ' . $user->daira->wilaya->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-block text-center card-footer">

    </div>
</div>

@endsection
