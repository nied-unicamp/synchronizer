<?php

require_once dirname(__FILE__) . '/../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class courseDAO implements abstractDAO {

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
	public function addCourse($nome_curso, $num_alunos,   $cod_pasta, $nome_coordenador, $email, $login, $senha, $cod_usuario) {
		
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
		
		$query="insert into Usuario_config (cod_usuario,cod_curso) values (1, ".$cod_curso.")";
		Enviar($sock,$query);
		
		$query="insert into Usuario_curso (cod_usuario_global,cod_usuario,cod_curso,tipo_usuario,portfolio,data_inscricao) values (".$cod_usuario.",1, ".$cod_curso.", 'F', 'ativado', ".time().")";
		Enviar($sock,$query);
		
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

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateCourse($dbInfo, $serverType, $course) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteCourse($dbInfo, $serverType, $course) {
	}

	public function serverQuery($string, $serverType){

	}
}
