<?php

require_once dirname(__FILE__) . '/../Context/serverContext.php';
require_once dirname(__FILE__) . '/../Wrapper/DBWrapper.php';

ini_set('display_errors', 'On');
require  dirname(__FILE__) . "/../../../../cursos/aplic/bibliotecas/geral.inc";
require  dirname(__FILE__) . "/../../../../cursos/aplic/administracao/administracao.inc";

/**
 * Class used when getting user data from database.
 */
class userDAO implements abstractDAO {

	private $dbAccess;
	
	public function __construct()
	{
		$this->dbAccess = new DBWrapper();
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function getUserList($dbInfo, $serverType, $internal) {
		
		try {
			$recordsLoader = new serverContext($serverType, 'users');
		} catch (Exception $e) {
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
		}
		
		if($internal)
		{
			$query = 'select login, nome AS name, email from Usuario';
			return $recordsLoader->serverQuery($dbInfo, $query);
		}

		$query = 'SELECT login, name, email FROM users';
		
		return $recordsLoader->serverQuery($dbInfo, $query);
	}

	/**
	 * Only for sql database. 
	 * */
	public function getUserByLogin($dbInfo, $internal, $login)
	{
		if($internal)
		{
			return $this->dbAccess->manipulateData($dbInfo, 'SELECT login, nome, email FROM Usuario WHERE login=?', true, array($login));
			//echo "Oi!";
		}
		
		return $this->dbAccess->manipulateData($dbInfo, 'SELECT * FROM users WHERE login=?', true, array($login));
	}
	
	/**
	 * Adds a user to the database.
	 * @param $user An user object.
	 */
	public function addUser(/*$dbInfo, $serverType, */$user) {
		
		VerificaAutenticacaoAdministracao();
		
		echo "Oieee";
		$sock=Conectar("");
		 
		$cod_usuario=RetornaProximoCodigo($sock,"Usuario");
		$senhaUC=GeraSenha();
		$senha=crypt($senhaUC,"AA");
		 
		$login=$user->getLogin();
		$nome=$user->getName();
		$email=$user->getEmail();
		 
		 
		$query="insert into Usuario (cod_usuario,login,senha,nome,rg,email,telefone,endereco,cidade,estado,pais,
		data_nasc,  sexo, local_trab,profissao,cod_escolaridade,informacoes,data_inscricao,cod_lingua,confirmacao)
		values
		 
		('.$cod_usuario.','".$login."','".$senha."','.$nome.','0','".$email."','0','0','0','sp','br'," . time() . ",'U','uai','loglog',
			'777','none'," . time() . ",'1','')";
		 
		$res=Enviar($sock,$query);
		$lista_frases=RetornaListaDeFrases($sock,0);
		Desconectar($sock);
		 
		$remetente="TelEduc";
		$destino=$email;
		 
		$sock=Conectar("");
		 
		$query="select valor from Config where item='host'";
		$res=Enviar($sock,$query);
		$linha=RetornaLinha($res);
		$host=$linha['valor'];
		 
		$query="select diretorio from Diretorio where item='raiz_www'";
		$res=Enviar($sock,$query);
		$linha=RetornaLinha($res);
		$raiz_www=$linha['diretorio'];
		 
		/* 63 - TelEduc: Inscricao */
		$assunto=RetornaFraseDaLista($lista_frases,138);
		 
		/* 67 - Seu login eh*/
		$mensagem="<p>".RetornaFraseDaLista($lista_frases,67)." <big><em><strong>".$login."</strong></em></big> ";
		
		/* 68 - e sua senha eh*/
		$mensagem.=RetornaFraseDaLista($lista_frases,68)." <big><em><strong>".$senhaUC."</strong></em></big></p>\n";
		 
		/* 252 - Para acessar o curso basta ir ao endereço  */
		$mensagem.="<p>".RetornaFraseDaLista($lista_frases,252)."<br />\n";
		 
		$mensagem.="<a href=\"http://".$host.$raiz_www."\"><p><strong>http://".$host.$raiz_www."</strong></p></a></p>\n\n";
		 
		$mensagem.="<hr /><br />\n";
		 
		/* 253 - Para alterar a senha entre na opção Configurar localizada no menu fora do curso. */
		$mensagem.="<p><small>".RetornaFraseDaLista($lista_frases,253)."</small></p><br /><br />\n";
		 
		/* 66 - Anteciosamente, Coordenacao do curso */
		$mensagem.="<p style=\"text-align:right;\">".RetornaFraseDaLista($lista_frases,66)."</strong>. </p><br />\n";
		 
		Desconectar($sock);
		 
		$cod_curso='';
		 
		$mensagem_envio = MontaMsg($host, $raiz_www, $cod_curso, $mensagem, $assunto);
		 
		MandaMsg($remetente,$destino,$assunto,$mensagem_envio);
		
		Desconectar($sock);
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateUser($dbInfo, $serverType, $user) {
		
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteUser($dbInfo, $serverType, $user) {
		
	}
	
	public function serverQuery($string, $serverType){
	
	}
}
