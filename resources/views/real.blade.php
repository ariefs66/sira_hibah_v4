<!DOCTYPE html>
<html>
    <head>
        <title>.bdg Planning & Budgeting</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #6F6F6F;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
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
                margin-top: -10px;
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
                <div class="title">PENDAPATAN<br><h2><a id="pendapatan"></a></h2></div>
                <hr>
                <div class="title">BELANJA TIDAK LANGSUNG<br><h2><a id="btl"></a></h2></div>
                <hr>
                <div class="title">BELANJA LANGSUNG / PAGU<br><h2><a id="pagu"></a></h2></div>
                {{-- <h2><a id="bl"></a></h2> --}}
                <hr>
                <div class="title">DEFISIT / PAGU<br><h1><a id="defisit_"></a></h1></div>
                {{-- <h1><a id="defisit"></a></h1> --}}
            </div>
        </div>
    </body>
<script src="{{ url('/') }}/libs_dashboard/jquery/jquery/dist/jquery.js"></script>
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
    $(document).ready(function(){
        setInterval(function(){get();}, 2500);
    });

    function get(){
        $.ajax({
            type  : "get",
            url   : "{{ url('/') }}/real/{{ $tahun }}/getdata",
            success : function (data) {
                $('#pendapatan').text(data['PENDAPATAN']);
                $('#btl').text(data['BTL']);
                $('#bl').text(data['BL']);
                $('#pagu').text(data['PAGU']);
                $('#defisit').text(data['DEFISIT']);
                $('#defisit_').text(data['DEFISIT_']);
            }
        });
    }
</script>
</html>
