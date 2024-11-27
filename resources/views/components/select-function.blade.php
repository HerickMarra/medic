<div class="x-select-function" id="x-select-function">
      
    <div class="x-select-function-sintomas">
        <div class="x-select-function-sintomas-card">Febre</div>
        <div class="x-select-function-sintomas-card">Dor de cabeça</div>
        <div class="x-select-function-sintomas-card"> Gripe</div>
    </div>

    <div class="x-select-function-desc" >
        <div class="x-select-function-desc-btn">Descrever sintomas (por texto)</div>
    </div>
</div>


<script>
    
    $('#x-select-function').css('top', $(window).height() - 10);


    function SelectAtendimento(){
        $('#x-select-function').css('top', '0px');
        $('.x-select-function-desc').css('display', 'flex')
    }
</script>