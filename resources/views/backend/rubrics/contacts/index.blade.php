
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Liste des Contactes</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th>Type de Contact</th>
                <th class="text-center">Contenu</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($data['list_contacts'] as $contact)
                    <tr>
                        <td>
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left flex2">
                                        <div class="widget-heading">{{ trans('frontend.' . $contact->contact_type->name) }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            {{ $contact->content }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.contacts.edit', ['id' => $contact->id]) }}" class="btn btn-success mb-md-1"><i class="fa fa-pen"></i></a>
                            <a href="#" class="btn btn-danger delete" data-contactId="{{ $contact->id }}"><i class="fa fa-trash-alt"></i></a>
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
                    timeout: 5000,
                    progressBar: true,
                    type: 'success',
                    theme: 'sunset',
                    text: "{{ session('success') }}"
                }).show();
            @elseif(session()->has('error'))
                new Noty({
                        timeout: 5000,
                        progressBar: true,
                        type: 'error',
                        theme: 'sunset',
                        text: "Une erreur est survenue"
                    }).show();
            @endif
        });
    </script>

    <script>
        $(document).ready(function() {
            /* Delete a box confirmation */
            $('a.delete').click(function() {
                elem = $(this);
                var stat = window.confirm("Voulez vos vraiment supprimer ce Contact?");

                if (stat) {
                    contactId = $(this).attr('data-contactId');

                    // CSRF TOKEN Setup
                    jQuery.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

                    // Ajax requests
                    $.ajax({
                        url: "{{ route('admin.contacts.destroy', ['id' => 'codeX']) }}".replace('codeX', contactId),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }
                    }).done(function(response) {
                        if (response.success) {
                            elem.closest('tr').remove();

                            new Noty({
                                timeout: 5000,
                                progressBar: true,
                                type: 'success',
                                theme: 'sunset',
                                text: response.message
                            }).show();
                        }
                    }).fail(function(response) {
                        new Noty({
                            timeout: 5000,
                            progressBar: true,
                            type: 'error',
                            theme: 'sunset',
                            text: response.message
                        }).show();
                    });
                }
            });
        });
    </script>
@endsection
