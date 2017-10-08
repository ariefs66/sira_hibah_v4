<!DOCTYPE html>
<html>
    <head>
        <title>.bdg Planning & Budgeting</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
          <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/animate.css/animate.css" type="text/css" />
          <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
          <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" /> 
          <link rel="stylesheet" href="{{ url('/') }}/libs_dashboard/jquery/jquery.confirm/css/jquery-confirm.css" type="text/css" />
          <link rel="stylesheet" href="{{ url('/') }}/assets/css/font.css" type="text/css" />
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #6F6F6F !important;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }
            #load{
                position:fixed;
                top: 0%;
                left: 0%;
                width:100%;
                height:100%;
                background-color: #ffffff;
            }
            #load-img{
                position:fixed;
                top: 40%;
                left: 47%;
            }

            .container {
                text-align: center;
                display: table-cell;
                /*vertical-align: middle;*/
            }

            .content {
                /*vertical-align: middle;*/
                text-align: center;
                display: inline-block;
            }

            .title {
                margin-top: 25px;
                font-size: 30px;
            }
            h1{
                margin-top: 10px;
            }
            h2{
                margin-top: -10px;
            }
            hr{
                margin-top: -30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">TRANSFER KE SIMDA TAHUN {{$tahun}}<br>
                </div>
                <br>
                <button class="btn btn-info" id="geturusan">URUSAN</button>
                <button class="btn btn-info" id="getprogram">PROGRAM</button>
                <button class="btn btn-info" id="getkegiatan">KEGIATAN</button>
                <button class="btn btn-info" id="getbelanja">BELANJA</button>
                <button class="btn btn-info" id="getsubrincian">SUBRINCIAN</button>
                <button class="btn btn-info" id="getrincian">RINCIAN</button>
                <button class="btn btn-info" id="getbtl">BTL</button>
                <button class="btn btn-info" id="getpendapatan">PENDAPATAN</button>
                <button class="btn btn-info" id="getpembiayaan">PEMBIAYAAN</button>
                <br>
                <h4 id="title"></h4>
                <table class="table" id="table-simda">
                    <thead>
                        <tr>
                            <th width="1%">KODE</th>
                            <th>URAIAN</th>
                            <th width="1%">SIMDA</th>
                            <th width="1%">BUDGETING</th>
                            <th width="1%">STATUS</th>
                            <th width="1%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="load" class="hide"><img id="load-img" src="{{url('/')}}/assets/img/load.gif"></div>
    </body>
<script src="{{ url('/') }}/libs_dashboard/jquery/jquery/dist/jquery.js"></script>
<script src="{{ url('/') }}/libs_dashboard/jquery/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/libs_dashboard/jquery/bootstrap/dist/js/bootstrap.js"></script>
<script src="{{ url('/') }}/libs_dashboard/jquery/jquery.confirm/js/jquery-confirm.js"></script>
<script src="{{ url('/') }}/assets/js/ui-load.js"></script>
<script src="{{ url('/') }}/assets/js/ui-jp.config.js"></script>
<script src="{{ url('/') }}/assets/js/ui-jp.js"></script>
<script src="{{ url('/') }}/assets/js/ui-nav.js"></script>
<script src="{{ url('/') }}/assets/js/ui-toggle.js"></script>
<script src="{{ url('/') }}/assets/js/ui-client.js"></script>
<script src="{{ url('/') }}/assets/js/numeral.js"></script>
<script src="{{ url('/') }}/assets/js/custom.js"></script>
<script type="text/javascript">
    function loadtable(url,title){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#title').html(title);
            $('#table-simda').DataTable().destroy();
            $('#table-simda').DataTable({
                "paging"        : false,
                "searching"     : false,
                sAjaxSource: "{{ url('/') }}/auto/{{$tahun}}/getStatus/"+url+"/"+kodeperubahan,
                aoColumns: [
                    { mData: 'KODE',class:'text text-center'},
                    { mData: 'URAIAN',class:'text text-left' },
                    { mData: 'SIMDA',class:'text text-right' },
                    { mData: 'BUDGETING',class:'text text-right' },
                    { mData: 'STATUS',class:'text text-center' },
                    { mData: 'AKSI',class:'text text-center' }
                ]
            });
        }
    }
    function loading(param,kodeperubahan){
        $.ajax({
            type  : "get",
            url   : "{{ url('/') }}/auto/{{$tahun}}/progres/"+param+"/"+kodeperubahan,
            success : function (data) {
                $('#progress').text(data);
            }
        });        
    }
    $('#geturusan').on('click',function(){
        loadtable("urusan","URUSAN");
    });
    $('#getprogram').on('click',function(){
        loadtable("program","PROGRAM");
    });
    $('#getkegiatan').on('click',function(){
        loadtable("kegiatan","KEGIATAN");
    });
    $('#getbelanja').on('click',function(){
        loadtable("belanja","BELANJA<button class='btn btn-success pull-right' onclick='return transferbelanja(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");
    });
    $('#getsubrincian').on('click',function(){
        loadtable("subrincian","SUBRINCIAN<button class='btn btn-success pull-right' onclick='return transfersubrincian(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");
    });
    $('#getrincian').on('click',function(){
        loadtable("rincian","RINCIAN<button class='btn btn-success pull-right' onclick='return transferrincian(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");
    });
    $('#getbtl').on('click',function(){
        loadtable("btl","BELANJA TIDAK LANGSUNG<button class='btn btn-success pull-right' onclick='return transferbtl(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");
    });
    $('#getpendapatan').on('click',function(){
        loadtable("pendapatan","PENDAPATAN<button class='btn btn-success pull-right' onclick='return transferpendapatan(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");
    });
    $('#getpembiayaan').on('click',function(){
        loadtable("pembiayaan","PEMBIAYAAN<button class='btn btn-success pull-right' onclick='return transferpembiayaan(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");
    });
    function transferprogram(){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfProgramFromSimda",
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("program","PROGRAM");                    
                }
            });
        }        
    }
    function transferkegiatan(){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfKegiatanFromSimda",
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("kegiatan","KEGIATAN");                    
                }
            });
        }        
    }
    function transferbelanja(kode){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfBelanjaFromSimda/"+kodeperubahan+"/"+kode,
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("belanja","BELANJA<button class='btn btn-success pull-right' onclick='return transferbelanja(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");                    
                }
            });
        }        
    }
    function transfersubrincian(kode){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfSubrincianFromSimda/"+kodeperubahan+"/"+kode,
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("subrincian","SUBRINCIAN<button class='btn btn-success pull-right' onclick='return transfersubrincian(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");                    
                }
            });
        }        
    }
    function transferrincian(kode){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfRincianFromSimda/"+kodeperubahan+"/"+kode,
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("rincian","RINCIAN<button class='btn btn-success pull-right' onclick='return transferrincian(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");                    
                }
            });
        }        
    }
    function transferbtl(kode){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfBTLFromSimda/"+kodeperubahan+"/"+kode,
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("btl","BELANJA TIDAK LANGSUNG<button class='btn btn-success pull-right' onclick='return transferbtl(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");                    
                }
            });
        }        
    }
    function transferpendapatan(kode){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfPendapatanFromSimda/"+kodeperubahan+"/"+kode,
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("pendapatan","PENDAPATAN<button class='btn btn-success pull-right' onclick='return transferpendapatan(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");                    
                }
            });
        }        
    }
    function transferpembiayaan(kode){
        kodeperubahan = $('#form_kode').val();
        if(kodeperubahan == ""){
            $.alert('ISI KODE PERUBAHAN!')
        }else{
            $('#load').removeClass('hide');
            $.ajax({
                type  : "get",
                url   : "{{ url('/') }}/auto/{{$tahun}}/trfPembiayaanFromSimda/"+kodeperubahan+"/"+kode,
                success : function (data) {
                    $('#load').addClass('hide');
                    loadtable("pembiayaan","PEMBIAYAAN<button class='btn btn-success pull-right' onclick='return transferpembiayaan(0)' style='margin-right:10px;'><i class='fa fa-retweet'></i></button>");                    
                }
            });
        }        
    }
</script>
</html>
