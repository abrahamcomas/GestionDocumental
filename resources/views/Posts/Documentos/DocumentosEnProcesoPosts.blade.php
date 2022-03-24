@extends('App')
@section('content')
	<div class="container-fluid">   
		@livewire('documentos.documentos-en-proceso')  
	</div>  
@endsection  
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){               
		$(document).on('click', '#btnEnviar1', function(){ 
			$("#MostrarFor").show(); 
			$("#IngresoFirma").hide();      
		}); 
	}); 
</script> 
@endsection 