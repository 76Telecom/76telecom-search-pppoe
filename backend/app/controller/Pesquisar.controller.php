<?php

function pesquisarChamado($cookies, $protocol)
{
    $open = new View();

    $dados = [];
    $header = [
        'Accept: application/json; charset=utf-8',
        'Cookie: ' . $cookies,
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36'
    ];

    $login_one = $open->Web(
        CONFIG['ERP'] .'/chamadocontroller/pesquisar',
        'numero=' . $protocol . '&fila=0&meuChamado=0&cliente=&assunto=&page=1&rows=30',
        $header
    );

    $json = json_decode($login_one, true);

    if ($json['total'] == 0) {
        die(json_encode(array('status' => false, 'return' => 'Chamado não encontrado.')));
    }

    foreach ($json['rows'] as $key => $value) {
        array_push($dados, $value);
    }

    /* Validar se o chamado está aberto */
    if ($dados[0]['status'] == 1) {
        die(json_encode(array('status' => false, 'return' => 'Certifique-se que o chamado está aberto.')));
    }

    /* Validar se o chamado está na fila de ATIVAÇÃO */
    if (!preg_match_all('/(ATIVA)/i', $dados[0]['fila'])) {
        die(json_encode(array('status' => false, 'return' => 'Chamado não está na fila de Ativação')));
    }

    $login_two = $open->Web(
        CONFIG['ERP'] .'/meuchamado/' . $json['rows'][0]['id'],
        null,
        $header
    );

    /* Verifica se o chamado possui bandwidth */
    if (!preg_match_all('/(Velocidade de Download)/i', $login_two)) {
        die(json_encode(array('status' => false, 'return' => 'Chamado fora do padrão, não foi localizado Download / Upload.')));
    }

    /* Cria usuario e senha PPPoE com base no nome do cliente e aplica filtros de caracteres */
    $nome = explode(' ', ltrim($dados[0]['cliente']))[0];
    $nome = $open->ReplaceString($nome);

    $dados[0]['pppoe'] =  strtoupper($nome) . '.COD' . $dados[0]['itemContratoId'];
    $dados[0]['password'] = substr(md5($dados[0]['pppoe']), 0, 10);

    /* Seta bandwidthDownload / Upload */
    preg_match_all('/(d:)(\s?)[0-9]+(\s?)(mb)/i', $login_two, $result);

    for ($i = 0; $i < 2; $i++) {
        preg_match_all('/[0-9]+/i', $result[0][$i], $banda);

        if ($i == 0) {
            $dados[0]['download'] = $banda[0][0];
        }
    }

    /* Verificar se a banda é x2 */
    if (preg_match('/(DOBRO)/i', $login_two)) {
        $dados[0]['download'] = $dados[0]['download'] * 2;
    }

    $dados[0]['upload'] = $dados[0]['download'] / 2;

    return $dados[0];
}