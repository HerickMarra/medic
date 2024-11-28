<div class="x-select-function" id="x-select-function">
      
    <div class="x-select-function-banner"></div>

    <x-pergunte-med-ia />

    <section class="x-select-function-area">
        <div class="x-select-function-banner-triagem"></div>
        <div class="x-select-function-banner-sintomas"></div>
    </section>

    <iframe style="margin: 25px auto; display: block;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61428.769221748276!2d-47.972690278320336!3d-15.7882292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x935a3af497d1a31b%3A0x25333616a03d2777!2sCentro%20de%20Conven%C3%A7%C3%B5es%20Ulysses%20Guimar%C3%A3es!5e0!3m2!1spt-BR!2sbr!4v1732748073348!5m2!1spt-BR!2sbr" width="95%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    

    {{-- <div class="x-select-function-desc" >
        <div class="x-select-function-desc-btn">Descrever sintomas (por texto)</div>
    </div> --}}
</div>


<script>
    
    $('#x-select-function').css('top', $(window).height() - 10);


    function SelectAtendimento(){
        $('#x-select-function').css('top', '0px');
        $('.x-select-function-desc').css('display', 'flex')
    }
</script>