<?php

require 'app/common/Viewstate.php';

require 'app/controller/Pesquisar.controller.php';
require 'app/controller/CriarUser.controller.php';
require 'app/controller/Interagir.controller.php';
require 'app/controller/LoginERP.controller.php';

if (!empty($chamado)) {
    $login = loginErp([
        'user' =>  $user,
        'pass' => $pass
    ]);

    $dados = pesquisarChamado($login, $chamado);
    $criar = criarUsario($dados);
    $inter = interarErp($login, $dados);

    print $inter;
}