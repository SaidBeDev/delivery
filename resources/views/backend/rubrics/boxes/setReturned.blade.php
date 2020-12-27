

@extends('backend.layouts.master')

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-header">Retour d'une Coulis</div>

        <div class="card-body">
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label for="validationCustom01">Reference</label>
                    <input type="text" name="reference" class="form-control form-control-lg reference" id="validationCustom01" placeholder="Reference N°" autocomplete="new-password">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4">
                    <button class="btn btn-primary save">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Include scripts --}}
    {!! Html::script('backend/js/JQuery.Scanner.js') !!}

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

            $(document).scannerDetection({
                onComplete: function(code, qty) {
                    var len = $('input.reference').val();

                    if(len !== '' && len.length === 10) {
                        setReceived(code);
                    }
                }
            });

            $('button.save').on('click', function() {
                var len = $('input.reference').val();

                if(len !== '' && len.length === 10) {
                    code  = $('input.reference').val();
                    setReceived(code);
                }
            });

            function setReceived(code) {

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.setReturned', ['code' => 'codeX']) }}".replace('codeX', code),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                }).done(function(response) {
                    if (response.success) {
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
            };

        });
    </script>
@endsection
