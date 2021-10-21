<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Prova Rodrigo C. Santiago</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    </head>
        
 
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <img src="{{ asset('img/ferrari01.png') }}" class="img-responsive logo" alt="">
                </div> 
            </div>  


            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <b>Meus Veículos</b> 
                        </div>
                        <div class="card-body">
                            <select class="form-select rounded-0" name="id_veiculo" id="id_veiculo" require="">
                                <option selected disabled>Selecione um veículo</option>
                                @foreach ($veiculos as $veiculo)
                                    <option value="{{$veiculo->id}}">{{ $veiculo->descricao }}</option>
                                @endforeach
                            </select> 
                        </div>
                    </div>   
                    
                    <div id="respConta" class="mt-4"></div>
                </div>
            </div>

            
        </div>

   
        
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

     
        <script src="{{ asset('js/script.js') }}"></script>

        <script>
            $(function(){

                
                $('#id_veiculo').change(function(){

                    var id_carro = $(this).val();
                    var rota  = "/busca/"+id_carro;

                    var resposta=null;

                    $('#respConta').append('');
                    $('#respConta').html("");
                    $("#respConta").html("");
                    $("#respConta").empty();

                    $.ajax({
                        url: rota,
                        dataType: 'json',
                        type: 'POST',                        
                        data: { id_carro: id_carro, _token: '{{csrf_token()}}' },
                        success: function (data) {                            

                            //Evitando concatenação no resultado das escolhas apartir da terceira, creio que seja bug de buffer do jquery
                            $('#resultado').remove();                            

                            resposta='<div id="resultado">';
                                resposta+='<h5 class="mt-4" style="margin-left:10%">>&nbsp;'+data.descricao+'</h5>';
                                resposta+='<input type="hidden" value='+data.valor+' id="vl_carro" style="margin-left:10%;margin:none">';
                                resposta+='<h5 class="mt-4" style="margin-left:10%;padding-top:0" id="vl_carro">>&nbsp;<b>'+data.valor.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})+'</b></h5>';
                                resposta+='<div class="row">';
                                resposta+='<div class="col-lg-8">';
                                resposta+='<input type="text" id="valor_entrada" class="form-control mt-4" placeholder="Valor de Entrada" style="margin-left:10%;padding-top:0;width:200px">';
                                resposta+='</div>';
                                resposta+='<div class="col-lg-4">';
                                resposta+='<a href="#" class="btn btn-success btn-sm mt-4" name="btnSimularParcelas" id="btnSimularParcelas" style="width:100%" onclick="calcular()">Simular</a><br>';
                                resposta+='</div>';
                                resposta+='</div>';                             
                            resposta+='</div>';
                           
                           
                            $('.card-body').append(resposta);
                           
                        },
                        error: function (data, textStatus, errorThrown) {
                            console.log(data);
                        }
                    });
                });

            

                
            });
        </script>

        <script>
            function calcular(){
                //(valor do carro - valor da entrada) / números de parcelas
                var val_entrada = $('#valor_entrada').val().toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                var val_carro   = $('#vl_carro').val().toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                
                //Valor entrada não poderá ser maior a que o do carro
                if(val_entrada>val_carro){
                    alert('O valor da entrada não poderá ser maior a que o do automóvel!');
                    document.getElementById("valor_entrada").focus();
                    document.getElementById("valor_entrada").style.backgroundColor = "#CCC";                   
                    return false;
                }else{
                    document.getElementById("valor_entrada").style.backgroundColor = "#FFF"; 
                }

                var dif = (parseFloat(val_carro).toFixed(2)-parseFloat(val_entrada).toFixed(2));
                var tot_6 = dif/6;
                var tot_12 = dif/12;
                var tot_48 = dif/48;
                
                $result='<h3>Valores simulados para você</h3>';
                $result+='<ul style="list-style:none">';
                    $result+='<li><b>6x</b> de '+tot_6.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})+'</li>';                    
                    $result+='<li><b>12x</b> de '+tot_12.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})+'</li>';
                    $result+='<li><b>48x</b> de '+tot_48.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})+'</li>';
                $result+='</ul>';

                $('#respConta').append($result);
                
            }
        </script>
        
    </body>
</html>
