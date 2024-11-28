<div class="x-select-function" id="x-select-function">
      
    <div class="x-select-function-banner"></div>

    <x-pergunte-med-ia />

    <section class="x-select-function-area">
        <div onclick="inicio()" class="x-select-function-banner-triagem"></div>
        <div class="x-select-function-banner-sintomas"></div>
    </section>

    <iframe style="margin: 25px auto; display: block;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61428.769221748276!2d-47.972690278320336!3d-15.7882292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x935a3af497d1a31b%3A0x25333616a03d2777!2sCentro%20de%20Conven%C3%A7%C3%B5es%20Ulysses%20Guimar%C3%A3es!5e0!3m2!1spt-BR!2sbr!4v1732748073348!5m2!1spt-BR!2sbr" width="95%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    

    {{-- <div class="x-select-function-desc" >
        <div class="x-select-function-desc-btn">Descrever sintomas (por texto)</div>
    </div> --}}


    <div id="descrever" style="display: none;" class="modal-triagem">
        <div class="modal-triagem-area">
            <p class="title" style="margin-top: 20px;">Descreva os sintomas que você está apresentando.</p>

            <textarea class="modal-triagem-area-textarea layout-input" name="" id="escricc" cols="34" rows="5"></textarea>


            <button class="modal-triagem-area-enviar" onclick="preTriagem()">Enviar</button>
        </div>  
    </div>


    <div id="saibamais" style="display: none;" class="modal-triagem">
        <div class="modal-triagem-area">
            <p class="title" style="margin-top: 20px;">Por favor, forneça suas informações.</p>
            <label style="text-align: center; display: block; margin: 25px auto 5px auto;" for="">Nome completo:</label>
            <input class="layout-input" type="text" name="" id="nomep"><br>
            <label style="text-align: center; display: block; margin: 5px auto;" for="">Data de nascimento:</label>
            <input class="layout-input" type="date" name="" id="idadeP">
            <button onclick="triagem()" style="text-align: center; display: block; margin: 20px auto 0 auto;">Enviar</button>
        </div>  
    </div>


    <div id="final" style="display: none;" class="modal-triagem">
        <div class="modal-triagem-area">
            <div class="carregamento">
                <div class="spinner">
                    <div class="spinnerin"></div>
                </div>
            </div>

            <div style="display: none" class="resultado">
                <div style="display:flex; flex-direction: row; align-items: center; justify-content:center; margin-bottom: 20px;">
                    <p style="margin-right:4px; font-size: 20px !important; font-weight: bold">Nivel de urgencia</p>
                    <div class="Bandeira"></div>
                </div>

                <p style="padding-left: 22px;
    margin-bottom: 4px;
    font-size: 18px;
    font-weight: 500;
    text-align: start;">Pré-Medidas</p>
                <p class="recomendacoes" style="margin-bottom: 14px; padding-left: 22px; text-align: start;"></p>

                <div class="hospitais">
                    <p>Mais próximo</p>
                    <div class="hosp">Hospital de Base do Distrito Federal </div>

                    <p>Recomendado</p>
                    <div class="hosp">Hospital Brasília</div>

                    <p>Mais vazio</p>
                    <div class="hosp">Hospital Daher</div>
                </div>

                <a href="/user" style="    padding: 6px;
    font-weight: 700;
    width: 90px;
    border: 1px solid #e6e3e3;
    align-self: center;
    color: black;
    text-decoration: none;">Voltar</a>


            </div>
        </div>  
    </div>
</div>


<script>
    
    $('#x-select-function').css('top', $(window).height() + 300);


    function SelectAtendimento(){
        $('#x-select-function').css('top', '0px');
        $('.x-select-function-desc').css('display', 'flex')
    }

    function inicio(){
        $('#descrever').css('display', 'flex');
    }

    function preTriagem(response){
        let text = $('#escricc').val();

        if (!text || text.trim() === "") {
            $('#escricc').css('border', '2px solid red');
        } else {
            $('#escricc').css('border', '1px solid black');
        }
        $('#descrever').css('display', 'none');
        $('#saibamais').css('display', 'flex');

        $('.Bandeira').addClass(response.data.nivel_urgencia);
    }

    function triagem(){
        $('#saibamais').css('display', 'none');
        $('#final').css('display', 'flex');
        // URL da rota para onde a requisição será enviada
        var url = "/atendimento/realizar-atendimento";

        // Dados que você deseja enviar para a API
        var dataToSend = {
            _token: '{{csrf_token()}}',
            symptoms: $('#escricc').val(),
            nome: $('#nomep').val(),
            genero: 'Não informado',
            idade: $('#idadeP').val(),
        };

        $.post(url, dataToSend, function(response) {
            // Manipule a resposta da API aqui
            console.log(response);
            preTriagem(response);
            
            var listaRecomendacoes = document.querySelector('.recomendacoes');

            // Loop para criar um item da lista para cada recomendação
            response.data.pre_medidas.forEach(function(medida) {
                var item = document.createElement('li'); // Cria um novo item da lista <li>
                item.textContent = medida; // Define o texto do item como a recomendação
                listaRecomendacoes.appendChild(item); // Adiciona o item à lista
            });

            $('.carregamento').css('display', 'none');
            $('.resultado').css('display', 'flex');

        }).fail(function(xhr, status, error) {
            // Tratamento de erro
            console.error("Erro na requisição:", error);
        });
    }
</script>