
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Recevoir d'une Coulis</div>

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


@endsection

@section('scripts')
    {{-- Include scripts --}}
    {!! Html::script('node_modules/quagga/dist/quagga.min.js') !!}

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


            /**
            * Trigger a barcode scanner event
            */
            /* Quagga.init({
                inputStream : {
                name : "Live",
                type : "LiveStream",
                target: document.querySelector('#reference')    // Or '#yourElement' (optional)
                },
                decoder : {
                    readers : ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "code_39_vin_reader", "codabar_reader", "upc_reader", "upc_e_reader", "i2of5_reader", "2of5_reader", "code_93_reader"]
                }
            }, function(err) {
                if (err) {
                    console.log(err);
                    return
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            }); */
            /* End */

            $('input.reference').on('change', function() {
                var len = $(this).val();
                if(len !== '' && len.length === 10) {
                    setReceived();
                }
            });

            $('button.save').on('click', function() {
                setReceived();
            });

            function setReceived() {

                code  = $('input.reference').val();

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.setRecieved', ['code' => 'codeX']) }}".replace('codeX', code),
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

            /**
             * Change box status
             */
            $('select.box-select').change(function() {
                boxId  = $('select.box-select option:selected').attr('data-boxId');
                statusId = $('select.box-select option:selected').attr('data-statusId');

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.changeStatus', ['id' => 'boxId', 'userId' => 'statusID']) }}".replace('boxId', boxId).replace('statusID', statusId),
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
            }); /* end */

        });
    </script>
@endsection

{{-- Modals --}}
{{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <a href="{{ route('admin.users.destroy', ['id' => $box->id]) }}" class="btn btn-danger btn-sm">Supprimer</a>
            </div>
        </div>
    </div>
</div>
 --}}
