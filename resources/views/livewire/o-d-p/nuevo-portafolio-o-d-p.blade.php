<div> 
    <br>  
    @if($Ayuda==1)    
    <div class="container-fluid">  
        <div class="row">  
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col">
                    <div class="card bg-light mb-3">  
                        <div class="card-header"> 
                        <center>
                                <h5> 
                                    <strong>
                                        INFORMACIÓN
                                    </strong>
                                </h5>
                            </center> 
                        </div>
                        <div class="card-body">
                            <center><img src="{{URL::asset('Imagenes/Portafolio/Nuevo.JPG')}}" width="1200" height="1200" class="img-fluid" alt="Responsive image"/></center> 
                        </div>
                        <div class="card-footer text-muted"> 
                            <div class="btn-group" style=" width:100%;">	
                                <button class="btn btn-danger active" wire:click="VolverAyuda">
                                    VOLVER
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else                             
    @if($Existe==0) 
        <style>
                #Imagen {
                    font-size: 18px;
                    width: 700px;
                    height: 150px;
                }
                img.izquierda { 
                    float: left;
                }
                img.derecha { 
                    float: right; 
                }
                p { 
                font: oblique bold 120%;
                }  
            </style>
        <script type="text/javascript">
            function Capturar(){
                html2canvas(document.querySelector('.specific'), {
                    onrendered: function(canvas) {
                        document.getElementById("Firma").value = canvas.toDataURL();
                    }
                });
            }
        </script>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2"></div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                @include('messages')  
                <div class="col">       
                    <div class="card bg-light mb-3">
                        <div class="text-muted"> 
                            <h1><center><strong>IMAGEN DE FIRMA AUTOMÁTICA</strong></center></h1>
                            <hr>
                        </div>
                        <div class="card-body">	 
                            <div id="Imagen" class="specific"> 
                                <p><img class="izquierda" src="{{URL::asset('Imagenes/escudo.png')}}" width="120" height="120"/><strong>Firmado digitalmente por<br> {{$Nombres}} {{$Apellidos}} <br> {{$Rut}} <br>{{$Oficina}} <br>{{$Cargo}}</strong></p>
                            </div>
                            <form method="POST" action="{{ route('ImagenCreada2') }}"> 
                                @csrf  
                                <div style="display: none">   
                                    <input type="text" id="Firma" name="Firma">
                                </div> 
                                @if($Creado==1)
                                    <div class="btn-group" style=" width:100%;">
                                        <button type="button" onclick="Capturar()" class="btn btn-warning" wire:click="Creada">
                                            ACEPTAR
                                        </button>  
                                    </div>  
                                @else
                                    <div class="btn-group" style=" width:100%;">	
                                        <button type="submit" class="btn btn-success">CONTINUAR</button>
                                    </div>
                                @endif 
                            </form>
                        </div> 
                        <div class="card-footer text-muted">  
                            SGD
                        </div>
                    </div>
                </div> 
            </div> 
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2"></div>		 
        </div>    
    @else                                     
        @if($Pagina==0) 
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col">
                    @if (session()->has('message'))
                        <div class="alert alert-danger">
                            {{ session('message') }}
                        </div>
                    @endif
                </div> 
            </div> 
        </div> 
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col">   
                        @include('messages')  
                        <div class="card bg-light mb-3">
                            <div class="text-muted">
                                <h1><center><strong>NUEVA SOLICITUD</strong></center></h1>
                                <hr> 
                            </div>
                            <div class="card-body"> 
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-1">
                                      <!--  <button class="btn" wire:click="Ayuda"><img src="{{URL::asset('Imagenes/ayuda.png')}}" onmouseover="mostrar('Más información.');" onmouseout="ocultar()" width="25" height="25"/></button>
                                        <strong><div id="ver"></div></strong>-->
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                                        <!--<strong>IMPORTANTE <br> El sistema ingresara de forma automática un código QR en cada archivo subido en la parte inferior derecha de dicho archivo.</strong>-->
                                    </div> 
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                                        <h6>CREAR SOLICITUD A*</h6>
                                        <div class="form-label-group"> 
                                            <select wire:model="DestinoFuncionario" class="form-control" >
                                                <option value="0" selected>---SELECCIONAR---</option>
                                                @foreach($ListaFuncionariosOP as $post)
                                                    <option value="{{ $post->ID_Funcionario_T   }}">{{ $post->Nombres }} {{ $post->Apellidos }}</option>
                                                @endforeach
                                            </select> 
                                        </div> 	
                                    </div> 	
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
                                        <h6>PLANTILLAS (OPCIONAL)</h6> 
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                                <div class="form-label-group">  
                                                    <select wire:model="ID_Plantilla" class="form-control" >
                                                        <option value="0" selected>---SELECCIONAR---</option>
                                                        @foreach($plantillas as $post)
                                                            <option value="{{ $post->id_plantillas}}">
                                                                {{ $post->nombre_plantilla }}
                                                            </option>
                                                        @endforeach 
                                                    </select> 
                                                </div> 	 
                                            </div> 
                                        @if($ID_Plantilla!=0)
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                                <form method="POST" action="{{ route('DescargarPlantillas') }}" enctype="multipart/form-data">
                                                    @csrf  
                                                    <input type="hidden" name="id_plantillas" value="{{ $ID_Plantilla  }}">
                                                    <button type="submit" class="btn btn-info active" style="background: #31A877;">DESCARGAR</button>
                                                </form> 
                                            </div> 
                                        @endif    
                                        </div>
                                    </div> 
                                </div> 
                                <hr>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                                        <h6>TÍTULO*</h6>
                                        <div class="form-label-group"> 
                                            <input type="text" class="form-control" wire:model="Titulo_T">
                                        </div>		 
                                    </div> 
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                                        <h6>TIPO*</h6>
                                        <div class="form-label-group"> 
                                            <select wire:model="Tipo_T" class="form-control" >
                                                <option value="0" selected>---SELECCIONAR---</option>
                                                @foreach($TipoDocumento as $post)
                                                    <option value="{{ $post->ID_TipoDocumento_T  }}">{{ $post->Nombre_T }}</option>
                                                @endforeach
                                            </select> 
                                        </div> 	  
                                    </div> 
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                                        <h6>NÚMERO FOLIO (OPCIONAL)</h6>
                                        <div class="form-label-group"> 
                                            <div class="form-label-group"> 
                                                <input type="text" class="form-control" wire:model="Folio">
                                            </div>		
                                        </div> 	 
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                                        <h6>DÍAS PARA FINALIZAR*</h6>
                                        <div class="form-label-group"> 
                                            <input type="number" class="form-control" wire:model="Fecha_T" min="1" pattern="^[0-9]+" >
                                        </div>		
                                    </div>
                                </div>    
                                <br>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3"></div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <hr>
                                            <h6>AGREGAR ARCHIVO/S* <strong>PDF</strong></h6>
                                            <div class="form-label-group"> 
                                                <input type="file" class="form-control" id="PDF" wire:model="PDF" multiple accept="application/pdf">
                                                <h6><strong>(MÁXIMO 10 PDF)</strong></h6>
                                            </div> 
                                            <div wire:loading wire:target="PDF">
                                                <center> 
                                                    <h5><strong>Subiendo documentos, espere por favor...</strong></h5>
                                                </center>
                                            </div> 
                                        </div>
                                        <div class="btn-group" style=" width:100%;">
                                            <button class="btn btn-primary" wire:click="Ingresar" id="boton">INGRESAR</button>
                                        </div>
                                        <center>
                                            <div wire:loading wire:target="Ingresar">
                                                <div class="circle bounce2"><h1 style="color: #FFFFFF;"><strong>SGD</strong></h1></div>
                                                <h5><strong>Verificando documentos, espere por favor...</strong></h5>                         
                                            </div>  
                                        </center> 
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3"></div>	
                                </div>  
                            </div> 
                            <div class=" text-muted">
                                <strong>(*)OBLIGATORIO</strong>
                            </div>
                            <div class="card-footer text-muted">
                            </div>
                            <div class="card-footer text-muted"> 
                                SGD
                            </div>
                        </div>
                    </div> 
                </div>		  
            </div> 
            @else
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col">
                        <div class="card bg-light mb-3">
                                <div class="text-muted">
                                    <h1><center><strong>NÚMERO DE IDENTIFICACIÓN INTERNA<strong> {{ $NumeroIngresado }} </strong></center></h1>
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <h5>NUEVA SOLICITUD INGRESADA.</h5>
                                    </div>
                                </div>  
                                <form method="POST" action="{{ route('EnvioOficinaPartesODP') }}">
                                    @csrf 
                                    <center>
                                    <div class="btn-group" style=" width:50%;">
                                        <button type="submit" class="btn btn-primary active">
                                            CONTINUAR
                                        </button> 
                                    </div></center> 
                                </form>  
                                <br>
                                <div class="card-footer text-muted">
                                </div>
                                <div class="card-footer text-muted">
                                   SGD
                                </div>
                            </div>
                        </div>	
                    </div>
                </div>			
            </div>
        @endif
    @endif               
</div> 
@endif  