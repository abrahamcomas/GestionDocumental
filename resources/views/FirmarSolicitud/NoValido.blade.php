<meta charset="utf-8">
<title>GestionDocumental</title>
<head>
	<meta name="viewport" content="width=device-width"/>
	<link href="{{ asset ('css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset ('ManuLateral/simple-sidebar.css ') }}" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<!--Para que funione el ajax-->
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" ></script>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <!--PDF JAVASCRIP-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
	<style type="text/css">
 
        .card-header, #h, .btn-success,.btn-primary { 
            height:auto;
	        background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(76, 25, 88,.4) 10%,rgba(138,114,76,0) 40%), -webkit-linear-gradient(top,  rgba(255, 255, 255,.25) 0%,rgba(0, 0, 0) 100%), -webkit-linear-gradient(-45deg,  #2AADB8 0%,#2AADB8 100%);
			color: white !important;
		
		}
	</style> 

    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="h">
		<div class="top-right link">	 
			<a class="navbar-brand" href="#" style="color: white; font-size:16px;"><STRONG>GESTIÓN DOCUMENTAL</STRONG></a>		
		</div>
	</nav>  
</head>
<body> 
    <div id="IngresoFirma" class="container-fluid">  
        <div class="row"> 
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2"></div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                <br>
                <div class="col">
                    <div class="card bg-light mb-3">
                        <div class="card-header">ERROR</div>  
                        <div class="card-body">
                            <h3><strong><center>ERROR LA URL SOLICITADA NO ESTÁ DISPONIBLE.<br></center></strong></h3>
                            <br>
                        </div>
                        <div class="card-footer text-muted"> 
                            GESTIÓN DOCUMENTAL
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2"></div>
        </div> 
    </div>
    <div>		
        <div>
            <center><img src="{{URL::asset('Imagenes/escudo.png')}}" width="90" height="90"/></center>
            <center>
                © {{ date("Y") }} Dep. de informática V0.1<br>
                Municipalidad de Curicó<br>
                VERSIÓN BETA 0.5.230322
            </center>
        </div>
    </div> 
    <div id="page-content-wrapper"> 
        @yield("content")
        @livewireScripts
        @yield('scripts')
        @yield("foot")
    </div>
</body>