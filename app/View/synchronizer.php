<?php
/* COLOCAR ESTE ARQUIVO em webdriver/administracao
<!--
-------------------------------------------------------------------------------

    Arquivo : administracao/criar_curso.php

    TelEduc - Ambiente de Ensino-Aprendizagem a Dist�ncia
    Copyright (C) 2001  NIED - Unicamp

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2 as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    You could contact us through the following addresses:

    Nied - N�cleo de Inform�tica Aplicada � Educa��o
    Unicamp - Universidade Estadual de Campinas
    Cidade Universit�ria "Zeferino Vaz"
    Bloco V da Reitoria - 2o. Piso
    CEP:13083-970 Campinas - SP - Brasil

    http://www.nied.unicamp.br
    nied@unicamp.br

------------------------------------------------------------------------------
-->
*/

/*==========================================================
  ARQUIVO : administracao/criar_curso.php
  ========================================================== */

  $bibliotecas="../cursos/aplic/bibliotecas/";
  include($bibliotecas."geral.inc");
  include("admin.inc");
  

  require_once("../cursos/aplic/xajax_0.5/xajax_core/xajax.inc.php");
  
  //Estancia o objeto XAJAX
  $objAjax = new xajax();
  $objAjax->configure("characterEncoding", 'ISO-8859-1');
  $objAjax->setFlag("decodeUTF8Input",true);
  $objAjax->configure('javascript URI', "../cursos/aplic/xajax_0.5");
  $objAjax->configure('errorHandler', true);
  //Registre os nomes das fun?es em PHP que voc?quer chamar atrav? do xajax
  $objAjax->register(XAJAX_FUNCTION,"SugerirLoginDinamic");
  $objAjax->register(XAJAX_FUNCTION,"ExisteLoginEmail");
  //Manda o xajax executar os pedidos acima.
  $objAjax->processRequest();

  include("../topo_tela_inicial.php");

  $lista_frases_adm = RetornaListaDeFrases($sock,-5);
 
  /* Inicio do JavaScript */
  echo("  <script type=\"text/javascript\">\n\n");

  echo("    var flagOnDivSugs=0;");

  echo("    function EmailLoginRepetido()\n");
  echo("    {\n");
  echo("      alert('Email e/ou login fornecidos ja existem! Digite valores diferentes (note que os logins existentes aparecem na lista de sugestoes !).');\n");
  echo("      document.frmCriar.email.value = '';\n");
  echo("      document.frmCriar.login.value = '';\n");
  echo("      document.frmCriar.email.focus();\n");
  echo("    }\n");

  echo("    function TestaForm()\n");
  echo("    {\n");
  echo("      var escolha = document.frmCriar.optUsu.value;\n");
  echo("      var nome_curso = document.frmCriar.nome_curso.value;\n");
  echo("      while (nome_curso.search(\" \") != -1)\n");
  echo("        nome_curso = nome_curso.replace(/ /, \"\");\n\n");

  echo("      var num = document.frmCriar.num_alunos.value;\n");
  echo("      while (num.search(\" \") != -1)\n");
  echo("        num = num.replace(/ /, \"\");\n\n");

  echo("      if(escolha == 'nao')\n");
  echo("      {");
  echo("        var nome_coordenador = document.frmCriar.nome_coordenador.value;\n");
  echo("        while (nome_coordenador.search(\" \") != -1)\n");
  echo("          nome_coordenador = nome_coordenador.replace(/ /, \"\");\n\n");

  echo("        var email = document.frmCriar.email.value;\n");
  echo("        while (email.search(\" \") != -1)\n");
  echo("          email = email.replace(/ /, \"\");\n\n");
  echo("      }\n");

  echo("      var login = document.frmCriar.login.value;\n");
  echo("      while (login.search(\" \") != -1)\n");
  echo("        login = login.replace(/ /, \"\");\n\n");

  echo("      if(escolha == 'nao')\n");
  echo("      {");
  echo("        if(nome_curso == '' || num == '' || nome_coordenador == '' || email == '' || login == '')\n");
  echo("        {\n");
  /* 166 - Os seguintes campos n�o podem ser deixados em branco:\n Nome e n�mero de alunos do curso;\n Nome, email e login do coordenador */
  echo("          alert('".RetornaFraseDaLista($lista_frases_adm,166)."');\n");
  echo("          return false;\n");
  echo("        }\n");
  echo("      }\n");
  echo("      else\n");
  echo("      {");
  echo("        if(nome_curso == '' || num == '' || login == '')\n");
  echo("        {\n");
  /* 512 - Os seguintes campos nao podem ser deixados em branco: Nome e numero de alunos do curso; login do coordenador */
  echo("          alert('".RetornaFraseDaLista($lista_frases_adm,512)."');\n");
  echo("          return false;\n");
  echo("        }\n");
  echo("      }\n");

  echo("      var intValue = parseInt(num);\n");
  echo("      if ((isNaN(intValue)) || (intValue < 0))\n");
  echo("      {\n");
  /* 167 - O campo n�mero de alunos deve ser um inteiro positivo. */
  echo("        alert('".RetornaFraseDaLista($lista_frases_adm,167)."');\n");
  echo("        return false;\n");
  echo("      }\n");

  echo("      if(escolha == 'nao')\n");
  echo("      {");
  echo("        var cnt = 0;\n");
  echo("        var email = document.frmCriar.email.value;\n");
  echo("        while (email.search(\"@\") != -1)\n");
  echo("        {\n");
  echo("          email = email.replace(/@/, \"\");\n\n");
  echo("          cnt++;\n");
  echo("        }\n");
  echo("        var email = document.frmCriar.email.value;\n");
  echo("        var p_arroba = email.indexOf('@');\n");
  echo("        var p_u_ponto = email.lastIndexOf('.');\n");
  echo("        if ((email.indexOf(' ') >= 0) || (email.charAt(email.length-1)=='@') || (email.indexOf('.@') >= 0) || (email.indexOf('@.') >= 0) || (p_u_ponto==(email.length-1)) || (p_u_ponto < 0) || (p_u_ponto < p_arroba) || (cnt == 0) || (cnt > 1)) \n");
  echo("        {\n");
  /* 168 - E-mail inv�lido. */
  echo("          alert('".RetornaFraseDaLista($lista_frases_adm, 168)."');\n");
  echo("          return false;\n");
  echo("        }\n");
  echo("      }\n");

  echo("      document.frmCriar.submit();\n");
  echo("    }\n\n");

  echo("    function Iniciar()\n");
  echo("    {\n");
  echo("	startList();\n");
  echo("    }\n\n");

  echo("    function VerificaEscolha()\n");
  echo("    {\n");
  echo("	var escolha = document.frmCriar.optUsu.value;\n");
  echo("        if(escolha == 'sim')\n");
  echo("        {\n");
  echo("          document.getElementById('tr_nome_coord').style.display = 'none';\n");
  echo("          document.getElementById('tr_email_coord').style.display = 'none';\n");
  echo("        }\n");
  echo("        else\n");
  echo("        {\n");
  echo("          document.getElementById('tr_nome_coord').style.display = '';\n");
  echo("          document.getElementById('tr_email_coord').style.display = '';\n");
  echo("        }\n");
  echo("    }\n\n");

  echo("    function TesteBlur()");
  echo("    {\n");
  echo("      if(flagOnDivSugs == 0)\n");
  echo("      {\n");
  echo("        document.getElementById('tr_sugs').style.display='none';\n");
  echo("        document.getElementById('divSugs').style.display='none';\n");
  echo("      }\n");
  echo("    }\n");

  ?>

function askForDataInfo()
{
	
	if (document.getElementById('mysqlRadio').checked) {
        document.getElementById('configMysqlDb').style.display = 'block';
    } else {
        document.getElementById('configMysqlDb').style.display = 'none';
    }
	
}


  <?php

  echo("  </script>\n");

  $objAjax->printJavascript();

  /* Fim do JavaScript */

  include("../menu_principal_tela_inicial.php");

  $sock=Conectar("");

  $lista_frases=RetornaListaDeFrases($sock,-5);
  $lista_frases_pag_inicial=RetornaListaDeFrases($sock,-3);

  VerificaAutenticacaoAdministracao();

  echo("        <td width=\"100%\" valign=\"top\" id=\"conteudo\">\n");
  /* 3 - Cria��o de Curso */
  echo("          <h4>".RetornaFraseDaLista($lista_frases,3)."</h4>\n");

  // 3 A's - Muda o Tamanho da fonte
  echo("          <div id=\"mudarFonte\">\n");
  echo("            <a onclick=\"mudafonte(2)\" href=\"#\"><img width=\"17\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 3\" src=\"../cursos/aplic/imgs/btFont1.gif\"/></a>\n");
  echo("            <a onclick=\"mudafonte(1)\" href=\"#\"><img width=\"15\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 2\" src=\"../cursos/aplic/imgs/btFont2.gif\"/></a>\n");
  echo("            <a onclick=\"mudafonte(0)\" href=\"#\"><img width=\"14\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 1\" src=\"../cursos/aplic/imgs/btFont3.gif\"/></a>\n");
  echo("          </div>\n");

   /* 509 - Voltar */
  echo("                  <ul class=\"btsNav\"><li><span onclick=\"javascript:history.back(-1);\">&nbsp;&lt;&nbsp;".RetornaFraseDaLista($lista_frases_geral,509)."&nbsp;</span></li></ul>\n");
  
  echo("          <!-- Tabelao -->\n");
  echo("          <form name=\"frmCriar\" action=\"criar_curso2.php\" method=\"post\" onsubmit=\"return(false);\">\n");
  echo("          <table cellpadding=\"0\" cellspacing=\"0\" id=\"tabelaExterna\" class=\"tabExterna\">\n");
  echo("            <tr>\n");
  echo("              <td>\n");
  echo("                <ul class=\"btAuxTabs\">\n");
  /* 23 - Voltar (Ger) */
  echo("                  <li><span title=\"".RetornaFraseDaLista($lista_frases_geral,23)."\" onClick=\"document.location='index.php'\">".RetornaFraseDaLista($lista_frases_geral,23)."</span></li>\n");

  echo("                </ul>\n");

  echo("              </td>\n");
  echo("            </tr>\n");





  echo("            <tr>\n");

  echo("              <td valign=\"top\">\n");
  echo("                <table cellpadding=\"0\" cellspacing=\"0\" class=\"tabInterna\">\n");

  /* 91 - Dados do Curso */
  echo("                  <tr class=\"head\">\n");
  echo("                    <td align=\"center\"><b>Please select where the external data is comming from.</b></td>\n");
  echo("                  </tr>\n");
  echo("                  <tr>");
  echo("                    <td align=\"center\">\n");

////////////////////////////////////////////////////// parte interna comeca aqui!!	

  echo("                      <table>");
?>
<td>
					<div style="display:inline-block; margin:auto;">
						
						<div style="margin:auto; width:10em;"><input id="mysqlRadio" type="radio" onclick="javascript:askForDataInfo()" name="serverType" value="SERVER_TYPE_MYSQL" checked > Mysql Database<br></div>
						<div id="configMysqlDb" style="display:block;  margin-left:2em;">
						
							
							<p style="text-align:center	">
								Please complete the external mysql database information below.
							</p>

							 <table>
									<tr>
										<td>Host:</td>
										<td><input type="text" name="dbHost" value="localhost" class="input"><br></td>
									</tr>
									<tr>
										<td>Port:</td>
										<td><input type="text" name="dbPort" value="3306" class="input"><br></td>
									</tr>
									<tr>
										<td>Database's Name:</td>
										<td><input type="text" name="dbName" value="" class="input"><br></td>
									</tr>
									<tr>
										<td>Login:</td>
										<td><input type="text" name="dbLogin" class="input"><br></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input type="password" name="dbPassword" class="input"><br></td>
									</tr>

							 </table>	
							
						</div>
							<div style="margin:auto; width:10em;"><input type="radio" onclick="javascript:askForDataInfo()" name="serverType" value="json" > JSON file<br></div>
							<div style="margin:auto; width:10em;"><input type="radio" onclick="javascript:askForDataInfo()" name="serverType" value="xml"> XML file<br>	</div>
							<div style="margin:auto; width:10em;"><input type="radio" onclick="javascript:askForDataInfo()" name="serverType" value="SERVER_TYPE_REST"> Rest interface<br>	</div>
							<div style="margin:auto; width:10em;"><input type="radio" onclick="javascript:askForDataInfo()" name="serverType" value="csv"> CSV file<br></div>
					
						<!-- <input type="text" name="db" value="Type identifier of source (Ex.: Database name or file name)"><br> -->
					</div>

	
</td>

<?php

  echo("                      </table>");
  echo("                    </td>");
  echo("                  </tr>");
  echo("                  <tr class=\"head\">\n");
  /////////////////Please select what do you want to syncronize.
  echo("                    <td align=\"center\"><b>Please select what do you want to syncronize.</b></td>\n");
  echo("                  </tr>\n");
  echo("                  <tr>");
  echo("                    <td align=\"center\" style=\"border:none;\">\n");
  echo("                      <table>");

  


//////////////////////////////////////// aqui terminaaaaa
?>	
	<td>


		<div style="width:12em; display:inline-block">
				<input type="checkbox" name="targets[]" value="users" checked> Users<br>
				<input type="checkbox" name="targets[]" value="courses"> Courses<br>
				<input type="checkbox" name="targets[]" value="coursemember"> Members of courses<br>
		</div>

	</td>
 <?php

  echo("                      </table>\n");
  echo("                    </td>\n");

  echo("                  </tr>\n");

  echo("                  <tr>\n");
  echo("                    <td id=\"td_hint\" style=\"text-align:left;border:none;\">&nbsp;</td>\n");
  echo("                  </tr>\n");
  echo("                </table>\n");
  echo("              </td>\n");
  echo("            </tr>\n");
  echo("            <tr>\n");
  echo("              <td align=\"right\">\n");
  /* 98 - Criar Curso */
  echo("                <input class=\"input\" value=\"Synchronize\" type=\"submit\" onclick=\"xajax_ExisteLoginEmail(document.frmCriar.login.value,document.frmCriar.email.value,document.frmCriar.optUsu.value);\"/>\n");
  echo("              </td>\n");
  echo("            </tr>\n");
  echo("          </table>\n");
  echo("          </form>\n");
  echo("        </td>\n");
  echo("      </tr>\n");
  include("../rodape_tela_inicial.php");
  echo("  </body>\n");
  echo("</html>\n");
?>
