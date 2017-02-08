<?php

require_once dirname(__FILE__) . '/../Context/serverContext.php';
require_once dirname(__FILE__) . '/userDAO.php';
require_once dirname(__FILE__) . '/categoryDAO.php';

/**
 * TODO Auto-generated comment.
 */
class courseDAO{

	private $dbAccess;
	
	public function __construct()
	{
		$this->dbAccess = new DBWrapper();
	}
	
	public function getCourseCordList($dbInfo, $internal, $courseName)
	{
		if($internal)
		{
			$query = "SELECT Usuario.login from Usuario_curso 
					  INNER JOIN Cursos ON Cursos.cod_curso=Usuario_curso.cod_curso 
					  LEFT OUTER JOIN Usuario ON cod_usuario_global=Usuario.cod_usuario 
					  WHERE Usuario_curso.tipo_usuario='F' AND Cursos.nome_curso=? and not Usuario.login='admtele';";
				
				
			return $this->dbAccess->manipulateData($dbInfo, $query, true, array($courseName));
		}
		
		return $this->dbAccess->manipulateData($dbInfo, 
				                               "SELECT login FROM coursemember WHERE role='F' AND courseName=?", 
												true, 
												array($courseName));
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function getCourseList($dbInfo, $serverType, $internal) {

		$recordsLoader = new serverContext($serverType, 'courses');

		if($internal)
		{
			$query = "
					SELECT Cursos.nome_curso AS courseName, Cursos_pastas.pasta AS category
					FROM Cursos
					LEFT JOIN Cursos_pastas
					ON Cursos.cod_pasta=Cursos_pastas.cod_pasta;";
			return $recordsLoader->serverQuery($dbInfo, $query);
		}		

		$query = 'SELECT courseName, category FROM courses';
		
		return $recordsLoader->serverQuery($dbInfo, $query);
	}

	public function getCourseByName($dbInfo, $internal, $courseName)
	{
		if($internal)
		{
			$query = "SELECT Cursos.nome_curso AS courseName, Cursos_pastas.pasta AS category 
					FROM Cursos 
					LEFT JOIN Cursos_pastas 
					ON Cursos.cod_pasta=Cursos_pastas.cod_pasta 
					WHERE Cursos.nome_curso=?";
			
			//$query = 'SELECT * FROM coursesCache where courseName=?';
			
			return $this->dbAccess->manipulateData($dbInfo, $query, true, array($courseName));
		}
		
		return $this->dbAccess->manipulateData($dbInfo, 'SELECT * FROM courses WHERE courseName=?', true, array($courseName));
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	//public function addCourse($dbInfo, $serverType, $course) {
	public function addCourse($nome_curso, $num_alunos, $cod_pasta, $nome_coordenador, $email, $login, $senha, $cod_usuario) {
		
		global $dbnamecurso;
		global $cod_lingua_s;
		$sock=Conectar("");
		$query="select max(cod_curso) from Cursos";
		$res=Enviar($sock,$query);
		$linha=RetornaLinha($res);
		$cod_curso=$linha[0] + 1;
		
		$nome_base=$dbnamecurso.$cod_curso;
		$query="drop database if exists ".$nome_base;
		Enviar($sock,$query);
		
		$query="create database ".$nome_base;
		Enviar($sock,$query);
		
		if($cod_usuario == -1)
		{
			$senha_crypt=crypt($senha,"AA");
			$cod_usuario=RetornaProximoCodigo($sock,"Usuario");
		
			$query="insert into Usuario (cod_usuario,nome,email,login,senha,data_inscricao, cod_lingua) values (".$cod_usuario.",'".$nome_coordenador."','".$email."','".$login."','".$senha_crypt."',".time().", ".$cod_lingua_s.")";
			Enviar($sock,$query);
		}
		
		// Coordinator will be inserted during coursemember sync, so do his config information.
		/*
 		$query="insert into Usuario_config (cod_usuario,cod_curso) values (1, ".$cod_curso.")";
 		Enviar($sock,$query);
		*/
		// Coordinator will be inserted during coursemember sync.
		/*
		$query="insert into Usuario_curso (cod_usuario_global,cod_usuario,cod_curso,tipo_usuario,portfolio,data_inscricao) values (".$cod_usuario.",1, ".$cod_curso.", 'F', 'ativado', ".time().")";
		Enviar($sock,$query);
		*/
		
		// admtele is always a coordinator.
		$query="insert into Usuario_curso (cod_usuario_global,cod_usuario,cod_curso,tipo_usuario,portfolio,data_inscricao) values (-1,-1, ".$cod_curso.", 'F', 'ativado', ".time().")";
		Enviar($sock,$query);
		
		
		if($cod_pasta != NULL)
		{
			$query="insert into Cursos (cod_curso,nome_curso,num_alunos,cod_coordenador,cod_pasta,cod_lingua,acesso_visitante) 
							values (".$cod_curso.",'".$nome_curso."',".$num_alunos.",1,".$cod_pasta.",".$cod_lingua_s.",'N')";
		}
		else 
		{
			$query="insert into Cursos (cod_curso,nome_curso,num_alunos,cod_coordenador,cod_pasta,cod_lingua,acesso_visitante)
							values (".$cod_curso.",'".$nome_curso."',".$num_alunos.",1,NULL,".$cod_lingua_s.",'N')";
		}
		
		Enviar($sock,$query);
		
		Desconectar($sock);
		
		$sock = Conectar($cod_curso);
		
		if ($fh=fopen("base_curso/Base_Vazia.table","r"))
		{
			$content = fread($fh, filesize("base_curso/Base_Vazia.table"));
			$lines = explode(";", $content);
		
			foreach ($lines as $sql){
				if (trim($sql) != ""){
					Enviar($sock, $sql);
				}
			}
		}
		
		fclose($fh);
		
		if($cod_pasta != NULL)
		{
			$query="insert into Cursos (cod_curso,nome_curso,num_alunos,cod_coordenador,cod_pasta,cod_lingua,acesso_visitante) 
							values (".$cod_curso.",'".$nome_curso."',".$num_alunos.",1,".$cod_pasta.",".$cod_lingua_s.",'N')";
		}
		else
		{
			$query="insert into Cursos (cod_curso,nome_curso,num_alunos,cod_coordenador,cod_pasta,cod_lingua,acesso_visitante)
							values (".$cod_curso.",'".$nome_curso."',".$num_alunos.",1,NULL,".$cod_lingua_s.",'N')";
		}		
		
		Enviar($sock,$query);
		
		Desconectar($sock);

		$diretorio=RetornaDiretorio('Arquivos');

		CriaDiretorio($diretorio."/".$cod_curso);
		CriaDiretorio($diretorio."/".$cod_curso."/dinamica");
		CriaDiretorio($diretorio."/".$cod_curso."/agenda");
		CriaDiretorio($diretorio."/".$cod_curso."/atividades");
		CriaDiretorio($diretorio."/".$cod_curso."/apoio");
		CriaDiretorio($diretorio."/".$cod_curso."/leituras");
		CriaDiretorio($diretorio."/".$cod_curso."/obrigatoria");
		CriaDiretorio($diretorio."/".$cod_curso."/correio");
		CriaDiretorio($diretorio."/".$cod_curso."/perfil");
		CriaDiretorio($diretorio."/".$cod_curso."/portfolio");
		CriaDiretorio($diretorio."/".$cod_curso."/exercicios");
		CriaDiretorio($diretorio."/".$cod_curso."/extracao");
		
		// Enviar e-mail para o coordenador
		$sock = Conectar("");
		
		$lista_frases=RetornaListaDeFrases($sock,-5);
		
		$query="select valor from Config where item = 'host'";
		$res=Enviar($sock,$query);
		$linha=RetornaLinha($res);
		$host=$linha['valor'];
		
		$query="select diretorio from Diretorio where item='raiz_www'";
		$res=Enviar($sock,$query);
		$linha=RetornaLinha($res);
		$raiz_www=$linha['diretorio'];
		
		$remetente = RetornaConfig('adm_email');
		$destino = $email;
		$nome_aluno = $nome_coordenador;
		$endereco=$host.$raiz_www;
		
		
		
		/* 99 - Informa��es para acesso ao curso no TelEduc */
		$assunto = RetornaFraseDaLista($lista_frases,99);
		
		/* 100 - Seu pedido para realiza��o do curso*/
		/* 101 - foi aceito.*/
		/* 102 - Para acessar o curso, a sua Identifica��o �:*/
		/* 103 - e a sua senha �:*/
		/* 104 - O acesso deve ser feito a partir do endereco:*/
		/* 105 - Atenciosamente, Administra��o do Ambiente TelEduc*/
		
		$mensagem ="<p>".$nome_aluno.",</p>\n";
		$mensagem.="<p>".RetornaFraseDaLista($lista_frases,100)." ".$nome_curso." ".RetornaFraseDaLista($lista_frases,101)."</p>\n";
		if($cod_usuario == -1)
		{
			//um novo usuario foi cadastrado, entaum devemos enviar-lhe seus dados para acessar o teleduc
			$mensagem.="<p>".RetornaFraseDaLista($lista_frases,102)." <big><em><strong>".$login."</strong></em></big> ";
			$mensagem.=RetornaFraseDaLista($lista_frases,103)." <big><em><strong>".$senha."</strong></em></big></p>\n";
		}
		$mensagem.="<p>".RetornaFraseDaLista($lista_frases,104)."<br />\n";
		$mensagem.="<a href=\"http://".$endereco."/cursos/aplic/index.php?cod_curso=".$cod_curso."\">http://".$endereco."/cursos/aplic/index.php?cod_curso=".$cod_curso."</a></p>\n\n";
		$mensagem.="<p style=\"text-align:right;\">".RetornaFraseDaLista($lista_frases,105).".</p><br />\n";
		
		
		$mensagem_envio = MontaMsg($host, $raiz_www, $cod_curso, $mensagem, $assunto);
		MandaMsg($remetente,$destino,$assunto,$mensagem_envio);
		
	}

	public function insertUserInCourse($dbInfo ,$userLogin, $courseName, $role)
	{
		$sock=Conectar("");
		
		$lista_frases=RetornaListaDeFrases($sock,0);
		
		Desconectar($sock);
		
		// Get cod_curso using course parameter
		$cod_curso = $this->getCourseCodByName($dbInfo, $courseName);
		
		$dados = array();
		
		// get cod_usuario using user parameter
		// prepare "dados" parameter: $dados['cod_usuario_global'] and $role;
		$userDAOObj = new userDAO();

		$sock = Conectar($cod_curso);
		
		// call CadastrarUsuarioExistente or paste it there...
		//CadastrarUsuarioExistente($sock,$courseCode,$data, $lista_frases);
		//function CadastrarUsuarioExistente($sock,$cod_curso,$dados, $lista_frases)
		
		$dbnamebase = $_SESSION['dbnamebase'];
		$cod_lingua_s = $_SESSION['cod_lingua_s'];
		
		$data_inscricao = time();
		
		$cod_usuario_prox = RetornaProximoCodigoUsuarioCurso($sock,$cod_curso);
		
		$dados['cod_usuario_global'] = $userDAOObj->getUserCodeByLogin($dbInfo, $userLogin);
				
		
		$query  = "insert into ".$dbnamebase.".Usuario_curso (cod_usuario_global,cod_usuario,cod_curso,tipo_usuario,data_inscricao) ";
		$query .= "values ('".$dados['cod_usuario_global']."','".$cod_usuario_prox."','".$cod_curso."','".$role."',".$data_inscricao.")";
		Enviar($sock,$query);
		
		$query  = "insert into ".$dbnamebase.".Usuario_config (cod_usuario,cod_curso,notificar_email) values ";
		$query .= "('".$cod_usuario_prox."','".$cod_curso."','0')";
		Enviar($sock, $query);
		
		$remetente = RetornaConfig('adm_email');
		
		$destino = $userDAOObj->getEmailByLogin($dbInfo, $userLogin);
		
		Desconectar($sock);
		$sock=Conectar("");
		
		$query="select valor from Config where item='host'";
		$res=Enviar($sock,$query);
		$linha=RetornaLinha($res);
		$host=$linha['valor'];
		
		$query="select diretorio from Diretorio where item='raiz_www'";
		$res=Enviar($sock,$query);
		$linha=RetornaLinha($res);
		$raiz_www=$linha['diretorio'];
		
		/* 63 - TelEduc: Inscri  o */
		$assunto=RetornaFraseDaLista($lista_frases,63)." ".$courseName;
		
		if ($role == 'A')
		{
			/* 64 - Voc\EA foi inscrito como aluno no curso */
			$mensagem = "<p>".RetornaFraseDaLista($lista_frases,64)." <strong>".$courseName."</strong>.</p>\n";
		}
		else if ($role == 'F')
		{
			/* 72 - Voc\EA foi inscrito como formador no curso */
			$mensagem = "<p>".RetornaFraseDaLista($lista_frases,72)." <strong>".$courseName."</strong>.</p>\n";
		}
		else if ($role == 'Z')
		{
			/* 196 - Voc\EA foi inscrito como colaborador no curso */
			$mensagem = "<p>".RetornaFraseDaLista($lista_frases,196)." <strong>".$courseName."</strong>.</p>\n";
		}
		else if ($role == 'V')
		{
			/* 188 - Voc\EA foi inscrito como visitante no curso */
			$mensagem = "<p>".RetornaFraseDaLista($lista_frases,188)." <strong>".$courseName."</strong>.</p>\n";
		}
		else
		{
			// Erro !
			echo("<big>Erro em ".__FILE__." linha ".__LINE__." parametro tipo_usuario inesperado</big>");
			var_dump($dados[tipo_usuario]);
			die();
		}
		
		/* 65 - Visite a p gina do curso para obter informa  es sobre o seu in cio. */
		//$mensagem.="<p>".RetornaFraseDaLista($lista_frases,65).".</p>\n";
		
		//MENSAGEM DEVE INFORMAR QUE LOGIN E SENHA SAO OS MESMOS QUE ELE UTILIZA EM OUTROS CURSOS
		
		/* 254 - O seu login e senha são os mesmos já utilizados em cursos do endereço */
		//$mensagem.="<p>".RetornaFraseDaLista($lista_frases,254)." <a href=\"http://".$host.$raiz_www."\">http://".$host.$raiz_www."</a></p>\n";
		
		/* 311 - Para acessar o curso clique  em*/
		$mensagem .= "<p>".RetornaFraseDaLista($lista_frases,311).": </p>";
		$mensagem .= "<p><a href=\"http://".$host.$raiz_www."/cursos/aplic/index.php?cod_curso=".$cod_curso."\">http://".$host.$raiz_www."/cursos/aplic/index.php?cod_curso=".$cod_curso."</a></p>\n\n";
		/* 312 - Use login e senha j� cadastrados.*/
		$mensagem .= "<br /><p>".RetornaFraseDaLista($lista_frases,312)."</p>";
		/* 313 - Visite a p�gina do curso pra saber o seu in�cio.*/
		$mensagem .= "<br /><p>".RetornaFraseDaLista($lista_frases,313)."</p>";
		
		
		$mensagem.="\n";
		
		/* 66 - Anteciosamente, Coordena  o do curso */
		$mensagem .= "<p style=\"text-align:right;\">".RetornaFraseDaLista($lista_frases,66)." <strong>".$courseName."</strong>. </p><br />\n";
		
		Desconectar($sock);
		
		$mensagem_envio = MontaMsg($host, $raiz_www, $cod_curso, $mensagem, $assunto);
		
		MandaMsg($remetente,$destino,$assunto,$mensagem_envio);
		
		$sock=Conectar($cod_curso);
		
		return ($sock);
	
	}
	
	public function getCourseCodByName($dbInfo, $courseName)
	{
		$query = "select cod_curso from Cursos where nome_curso=?";
		
		$qresult = $this->dbAccess->dataRequest($dbInfo, $query, array($courseName));
		
		return $qresult[0]['cod_curso'];
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function updateCourseCategory($dbInfo, $serverType, $course, $newCategory) {
		
		// get cod_pasta from 
		$categoryDAOObj = new categoryDAO();
		
		$categoryCode = $categoryDAOObj->getCategoryCode($dbInfo, $course['category']);
		
		$courseCode = $this->getCourseCodByName($dbInfo, $course['courseName']);
		
		$query ='UPDATE Cursos SET cod_pasta=? WHERE cod_curso=?';
		
		$this->dbAccess->manipulateData($confDB, $query, true, array($categoryCode, $courseCode));
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteCourse($dbInfo, $serverType, $course) {
	}

}
