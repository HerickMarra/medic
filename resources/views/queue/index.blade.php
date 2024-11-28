<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/fila.css">

    <title>Fila de Pacientes</title>
</head>
<body>
    <x-area-mobile>
        <div id="filas">
            <!-- A fila será renderizada aqui -->
        </div>
    </x-area-mobile>

</body>

<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
<script>
    // Simulando que a fila inicial seja recebida do servidor (via Blade ou outra variável PHP)
    var filasInicias = @json($queues); // Isto será substituído pela variável do Blade com a fila inicial

    function compareQueues(newQueues) {
        // Compara a fila inicial com a nova fila
        if (JSON.stringify(filasInicias) !== JSON.stringify(newQueues)) {
            // Se as filas forem diferentes, atualiza a fila e a variável de controle
            updateQueueDisplay(newQueues);
            filasInicias = newQueues; // Atualiza a fila inicial
        }
    }

    function updateQueueDisplay(newQueues) {
        const queueContainer = document.getElementById('filas');
        queueContainer.innerHTML = ''; // Limpa o conteúdo atual da fila

        newQueues.forEach((item,index) => {
            const queueItem = document.createElement('div');
            queueItem.classList.add('queue-item');
            queueItem.innerHTML = `
                <p> ${index + 1}</p>
                <p><strong>Nome:</strong> ${item.patient_name}</p>
                <p><strong>Prioridade:</strong> ${item.priority}</p>
                <p><strong>Chegada:</strong> ${new Date(item.arrival_time).toLocaleString()}</p>
            `;
            queueContainer.appendChild(queueItem);
        });
    }

    function fetchQueue() {
        fetch('/fila/order-queue')
            .then(response => response.json())
            .then(data => {
                compareQueues(data); // Compara a nova fila com a fila inicial
            })
            .catch(error => {
                console.error('Erro ao buscar a fila:', error);
            });
    }

    // Atualiza a fila a cada 10 segundos
    setInterval(fetchQueue, 10000);

    // Busca inicial da fila ao carregar a página
    fetchQueue();
</script>

</html>
