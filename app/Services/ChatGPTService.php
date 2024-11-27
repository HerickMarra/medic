<?php


namespace App\Services;

use App\Models\Queue;
use Illuminate\Support\Facades\Http;

class ChatGPTService
{
    // Chave da API do ChatGPT (idealmente, configurada no .env)
    protected $apiKey;

    public function __construct()
    {
        // Pega a chave API configurada no .env
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Função para enviar uma requisição ao ChatGPT para pré-diagnóstico com sintomas.
     */
    public function preDiagnose(array $symptoms, $idade, $genero,$nome)
    {
        // Dados para enviar ao ChatGPT
        $data = [
            'model' => 'gpt-3.5-turbo', // Ou outro modelo conforme necessário
            'messages' => [
                ["role" => "system", "content" => "Você é um assistente médico especializado em triagem. Você será responsavel por prediagnosticar as doenças e definir uma prioridade"],
                ["role" => "system", "content" => "Essa prioridade tem que buscar ao máximo desempenho na fila de atendimento, avalie todos os criterios e dê uma prioridade de 0 a 100 onde 0 ele pode esperar o atendimento por mais tempo e 100 ele tem que ser atendido imediatamente,"],
                ["role" => "system", "content" => " considere usar prioridade aproximada a  10 para atendimentos que n precisam de tanta urgencia"],
                ["role" => "system", "content" => " considere usar prioridade aproximada a  0 para pacientes que podem voltar pra casa sem atendimento"],
                ["role" => "system", "content" => "Seja acertivo em dá a prioridade ela será usado para administrar onde o paciente ficará na ordem a fila"],
                ["role" => "system", "content" => "Considere levar a idade como parametro para definir a prioridade"],
                
                ["role" => "system", "content" => "você vai me retornar um json com as possiveis doenças e uma porcentagem de 0 a 100% da probabilidade dessa ser a doença certa "],
                ["role" => "system", "content" => "Analise todas as variaveis, idade, sintomas, Genero"],
                ["role" => "system", "content" => " o json n pode conter erros"],
                ["role" => "system", "content" => " Me retorne APENAS UM JSON e tbm uma ideia de melhoria nesse json"],
                ["role" => "system", "content" => ' o formato do json é:
                    {
                        "Sintomas" : ["Sintomas falados pelo usuário"]
                        "doenças" [
                                {
                                "nome": "Nome da doença",
                                "probabilidade": "70%"
                                },
                                {
                                "nome": "Nome da doen~ça",
                                "probabilidade": "20%"
                                },
                                {
                                "nome": "Nome da doença",
                                "probabilidade": "10%"
                                }
                                outras doenças....
                          ],
                        "pre_medidas" : [
                            "Algum procedimento a ser feito antes do medico avaliar",
                            outros procedimentos....
                        ],
                        "priority": "Nivel de 0 a 100 para ele ser atendido",
                        "nivel_urgencia": "Nivel de urgencia [baixa, media, alta, emergencia",
                        "idade": "Idade do paciente",
                        "name": "Nome Paciente",
                        "setor" : "Setor que o paciente tem que ser atendido",
                        "observacoes_adicionais" : "obs..",
                    }
                        
                    ]'],
                ["role" => "user", "content" => "Sintomas: " . implode(', ', $symptoms)],
                ["role" => "user", "content" => "Idade: " . $idade],
                ["role" => "user", "content" => "Genero: " . $genero],
                ["role" => "user", "content" => "Nome: " . $nome],
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000
        ];

        // Requisição para a API do ChatGPT
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])
        ->withOptions([
            'verify' => false, // Desabilitar verificação SSL, se necessário
        ])
        ->post('https://api.openai.com/v1/chat/completions', $data);

        // Verifica a resposta da API
        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        // Caso a resposta não seja bem-sucedida
        return 'Erro ao processar a requisição: ';
    }



    public function filaAtendimento($paciente)
    {

        $fila = json_encode(Queue::all());
        // Dados para enviar ao ChatGPT
        $data = [
            'model' => 'gpt-4', // Ou outro modelo conforme necessário
            'messages' => [
                ["role" => "system", "content" => "Você será responsavel por fazer toda a administração da fila de atendimento de um hospital"],
                ["role" => "system", "content" => "Seu objetivo é fazer a fila de atendimento ser mais rápida possivel"],
                ["role" => "system", "content" => "Você vai levar em consideração varios fatoes como idade, tempo que o paciente está esperando, sintomas, nivel de prioridade e as demais variaveis"],
                ["role" => "system", "content" => "Você vai trabalhar apenas com JSON, vai receber um json e devolver um json"],
                ["role" => "system", "content" => "Você n pode errar"],
                ["role" => "system", "content" => "o json tem que estar certo"],
                ["role" => "system", "content" => "Não pode perder nenhuma pessoa no processo"],
                ["role" => "system", "content" => "O campo order do json vai ser rresponsavel por guardar a ordem do paciente na fila de consulta"],
                ["role" => "system", "content" => "Reorganize o campo  'order' do json para que otimize a fila de atendimento usando os criterios idade e prioridade, vão ter usuários com 'order' = 1 mas que o novo usuário precise estar na posição 1"],
                
                
                ["role" => "system", "content" => ' o formato do json que vc vai recerber é:
                    {
                        "Sintomas" : ["Sintomas falados pelo usuário"]
                        "doenças" [
                                {
                                "nome": "Nome da doença",
                                "probabilidade": "70%"
                                },
                                {
                                "nome": "Nome da doen~ça",
                                "probabilidade": "20%"
                                },
                                {
                                "nome": "Nome da doença",
                                "probabilidade": "10%"
                                }
                                outras doenças....
                          ],
                        "pre_medidas" : [
                            "Algum procedimento a ser feito antes do medico avaliar",
                            outros procedimentos....
                        ],
                        "priority": "Nivel de 0 a 100 para ele ser atendido",
                        "nivel_urgencia": "Nivel de urgencia [baixa, media, alta, emergencia",
                        "idade": "Idade do paciente",
                        "name": "Nome Paciente",
                        "setor" : "Setor que o paciente tem que ser atendido",
                        "observacoes_adicionais" : "obs..",
                    }
                        
                    ]'],
                ["role" => "system", "content" => "Esse é o json da fila, o campo 'order' é a order na fila, se vc for adicionar um novo paciente deixe o id como nulo: ".$fila],
                ["role" => "system", "content" => "Paciente a ser adicionado: " . $paciente],
                ["role" => "system", "content" => "retorne Apenas um json com a fila Atualizada com os que já estão e os adicionados, Você n pode errar o json e me dê o json mais correto possivel, n escreva nada a mais do json"],
                ["role" => "system", "content" => "Me retorne EPENAS O JSON SEM ESCREVER NADA"],
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000
        ];

        // Requisição para a API do ChatGPT
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])
        ->withOptions([
            'verify' => false, // Desabilitar verificação SSL, se necessário
        ])
        ->post('https://api.openai.com/v1/chat/completions', $data);

        // Verifica a resposta da API
        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        // Caso a resposta não seja bem-sucedida
        return 'Erro ao processar a requisição: ' . $response->json()['error']['message'];
    }

    /**
     * Função para calcular a prioridade de atendimento.
     */
    public function calculatePriority(array $patientData)
    {
        // Exemplo de como você pode estruturar os dados e interagir com o modelo
        $data = [
            'model' => 'gpt-4',
            'messages' => [
                ["role" => "system", "content" => "Você é um assistente de triagem médica que calcula a prioridade."],
                ["role" => "user", "content" => "Dados do paciente: " . json_encode($patientData)]
            ]
        ];

        // Envia a requisição para o ChatGPT
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])
        ->withOptions([
            'verify' => false,
        ])
        ->post('https://api.openai.com/v1/chat/completions', $data);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return 'Erro ao calcular prioridade: ' . $response->json()['error']['message'];
    }

    /**
     * Função para alocar o paciente na fila de triagem.
     */
    public function allocatePatientToQueue(array $patientInfo)
    {
        // Exemplo de como alocar na fila, pode ser personalizado conforme necessidade
        $data = [
            'model' => 'gpt-4',
            'messages' => [
                ["role" => "system", "content" => "Você é um assistente responsável pela alocação de pacientes na fila de triagem."],
                ["role" => "user", "content" => "Paciente a ser alocado: " . json_encode($patientInfo)]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])
        ->withOptions([
            'verify' => false,
        ])
        ->post('https://api.openai.com/v1/chat/completions', $data);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return 'Erro ao alocar paciente: ' . $response->json()['error']['message'];
    }
}
