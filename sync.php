<?php

require  dirname(__FILE__) . "/../cursos/aplic/bibliotecas/geral.inc";
require  dirname(__FILE__) . "/admin.inc";

VerificaAutenticacaoAdministracao();

require_once dirname(__FILE__) . '/synchronizer/app/View/synchView.php';
exit();
