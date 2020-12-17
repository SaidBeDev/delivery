
<style media="print">
    @media print {
        @page {
        size: 10mm 20mm;
        }
    }
</style>
<div id="printable" class="card-body" style="font-size: 18px">
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}" media="print" />
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}"  />
    <div class="form-row">
        <div class="col-md-3"  style="border: 1px solid #111; padding: 10px">
            <div>
                {{-- <img  src="{{ asset('backend/assets/images/barcode.png') }}"  style="width: 100%"/> --}}
                {!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($box->code, 'C39+') . '" alt="barcode" style="width: 100%; height: 80px"  />' !!}
            </div>
            <div style="text-align: center; margin-top: 15px">
                <p>Service de livraison</p>
                <p style="font-weight: 700">ZROmar</p>
            </div>
        </div>
        <div class="col-md-6" style="border: 1px solid #111; padding: 15px">
            <div class="row">
                <div class="col-md-6" style="line-height: 2.4">
                    <div>
                        <span>Client: </span> {{ $box->full_name }}
                    </div>
                    <div>
                        <span>Tel: </span> {{ $box->tel }}
                    </div>
                    <div>
                        <span>Addresse: </span> {{ $box->address }}
                    </div>
                    <div>
                        <span>Note: </span>
                        <p class="font-weight-bold">{{ $box->note }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="
                        border: 1px solid #666;
                        border-right: 0;
                        border-top: 0;
                        height: 120px;
                        width: 150px;
                        float:right
                    "></div>
                    <div class="clearfix"></div>
                    <p style="
                        padding: 15px;
                        font-weight: bold;
                        text-align: right;
                    ">Prix: {{ $box->total_price .',00'. ' Da' }}</p>
                </div>
            </div>

        </div>


        <div class="col-md-3" style="border: 1px solid #111; padding: 15px">
            <div style="
                border: 1px solid #666;
                height: 120px;
                width: 150px;
                margin: auto;
                text-align: center;
                font-size: 40px;
                font-weight: bold;
                padding-top: 18px;
            ">
                27
            </div>

            <div style="text-align: center; margin: auto; padding: 5px">
                <p class="font-weight-bold; margin: 5px">{{ $box->daira->wilaya->name }}</p>
                <p style="font-size: 15px; margin: 0">{{ $box->daira->name }}</p>
            </div>

            <div style="margin: auto; text-align: center">
                <div style="border: 1px solid #666; width: 30px; height:30px; display: inline-block"></div>
                <div style="border: 1px solid #666; width: 30px; height:30px; display: inline-block"></div>
                <div style="border: 1px solid #666; width: 30px; height:30px; display: inline-block"></div>
            </div>
        </div>
    </div>

</div>
