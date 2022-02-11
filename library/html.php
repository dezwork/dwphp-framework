<?php

	namespace DwPhp\Library;
	use DwPhp\Library\head as head;
	use DwPhp\Library\pagination as pagination;

	class html{

		private 	$filesLinks	=	array();
		private 	$filesScripts	=	array();
		public function __construct(){
			$this->head = new head();
		}

		public static function compactarHTML($b='',$bolean=false) {
			if($bolean==true){
				$b = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $b);
				$b = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $b);
	        }
			return $b;
		}

		 /**
         * @param string local file in public.
         * @param starting or end file
         */
		public function includeFile($filename='',$local='',$additional=''){
			if($local!='start' && $local!='end'){$local='end';}
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if($ext=='css'){
				$this->setFilesLinks($GLOBALS['f']->fileVersion($filename),$local,($additional==''?'rel="stylesheet" type="text/css"':$additional));
			}else if($ext=='js'){
				$this->setFilesScripts($GLOBALS['f']->fileVersion($filename),$local,($additional==''?'type="text/javascript"':$additional));
			}else{
				$this->setFilesLinks($GLOBALS['f']->fileVersion($filename),$local,$additional);
			}
		}

		/* GETTRES AND SETTRES */
		public function setFilesLinks($filesLinks,$local='',$additional=''){
			if($local!='start' && $local!='end'){$local='end';}
			$this->filesLinks[$local][$filesLinks] = $additional;
		}
		public function getFilesLinks(){
			if(isset($this->filesLinks['start']) && is_array($this->filesLinks['start'])){
				// unique keys in array
				$tmp=$this->filesLinks['start'];
				foreach ($this->filesLinks['start'] as $key => $value) { if(is_array($tmp[$key]) && count($tmp[$key])>1){ unset($tmp[$key]); } }
			}
			if(isset($this->filesLinks['end']) && is_array($this->filesLinks['end'])){
				// unique keys in array
				$tmp=$this->filesLinks['end'];
				foreach ($this->filesLinks['end'] as $key => $value) { if(is_array($tmp[$key]) && count($tmp[$key])>1){ unset($tmp[$key]); } }
			}
			return $this->filesLinks;
		}
		public function getTagFilesLinks($local){
			if($local!='start' && $local!='end'){$local='end';}
			$files= $this->getFilesLinks();
			$ret = '';
			if(isset($files[$local])){
				foreach ($files[$local] as $key => $value) {
					$ret .= '<link href="'.$key.'" '.$value.' />'."\n";
				}
			}
			return $ret;
		}
		// FilesScripts
		public function setFilesScripts($filesScripts,$local='',$additional=''){
			if($local!='start' && $local!='end'){$local='end';}
			$this->filesScripts[$local][$filesScripts] = $additional;
		}
		public function getFilesScripts(){
			if(isset($this->filesScripts['start']) && is_array($this->filesScripts['start'])){
				// unique keys in array
				$tmp=$this->filesLinks['start'];
				foreach ($this->filesLinks['start'] as $key => $value) { if(is_array($tmp[$key]) && count($tmp[$key])>1){ unset($tmp[$key]); } }
			}
			if(isset($this->filesScripts['end']) && is_array($this->filesScripts['end'])){
				// unique keys in array
				$tmp=$this->filesScripts['end'];
				foreach ($this->filesScripts['end'] as $key => $value) { if(is_array($tmp[$key]) && count($tmp[$key])>1){ unset($tmp[$key]); } }
			}
			return $this->filesScripts;
		}

		public function getTagFilesScripts($local){
			if($local!='start' && $local!='end'){$local='end';}
			$files= $this->getFilesScripts();
			$ret = '';
			if(isset($files[$local])){
				foreach ($files[$local] as $key => $value) {
					$ret .= '<script src="'.$key.'" '.$value.'></script>'."\n";
				}
			}
			return $ret;
		}


		public function inputHTML($a){


			/*
				$a=array(
					'REQUIRED'	=>	true,
					'NAME'		=>	"nome_do_input",
					'LABEL'		=>	"Campo teste",
					'WIDTH'		=>	"span6",
					'TYPE'		=>	"select",
					'CLASS'		=>	"switch",
					'VALUE'		=>	"12",
					'HELP'		=>	"Esse campo é de preenchimento teste",
					'MAX'		=>	cadastra maxlength="",
					'PALCEHOLDER'=>	"teste",
					'ADDHTML'	=>	adiciona conteudo html no input
					'CLASS_GROUP'	=>	Adiciona classe no grupo
					'STYLE'		=>	'2-10 4-8 ... lavel-input'
					'OPTION'	=>	array(	'1'	=>	'Teste 1',
											'12'=>	'Teste 12')
					);
			 */
			$ret='';
			if(is_array($a)){
				$required 	= (isset($a['REQUIRED']) && $a['REQUIRED']==true?	' required '	:	'');
				$label 		= (isset($a['LABEL'])?		$a['LABEL']							:	'');
				$name 		= (isset($a['NAME'])?		$a['NAME']							:	'');
				$id 		= (isset($a['ID'])?			$a['ID']							:	$a['NAME']);
				$class 		= (isset($a['CLASS'])?		$a['CLASS']							:	'span6');
				$type 		= (isset($a['TYPE'])?		$a['TYPE']							:	'text');
				$value 		= (isset($a['VALUE'])?		$a['VALUE']							:	'');
				$help 		= (isset($a['HELP'])?		$a['HELP']							:	'');
				$maxlength	= (isset($a['MAX'])?		$a['MAX']							:	'');
				$placeholder= (isset($a['PLACEHOLDER'])?$a['PLACEHOLDER']							:	'');
				$class_group= (isset($a['CLASS_GROUP'])?$a['CLASS_GROUP']					:	'');
				$addhtml	= (isset($a['ADDHTML'])?	$a['ADDHTML']						:	'');
				$html_group	= (isset($a['HTML_GROUP'])?	$a['HTML_GROUP']					:	'');
				$search		= (isset($a['SEARCH'])?		$a['SEARCH']						:	false);
				$option 	= (isset($a['OPTION']) && is_array($a['OPTION'])?$a['OPTION']	:	array(0=>'Nenhum registro encontrado.'));

				$style		= (isset($a['STYLE'])?		$a['STYLE']							:	'');
				if (strripos($style, '-') === false) {
					$style = array();
				}else{
					$style = explode('-',$style);
					if(count($style)<2){
						$style = array();
					}else{
						$style[0] = (int)$style[0];
						$style[1] = (int)$style[1];

						if(($style[0]+$style[1])>12 || ($style[0]==0 && $style[1]==0)){
							$style = array();
						}
					}
				}


				$ret=''."\n";

				if($type!='hidden'){
					$ret.='                                             <div class="form-group '.$class_group.'" '.$html_group.'>'."\n";
	            	$ret.='                                                 <label class="control-label '.(count($style)>1 && $style[0]!=0?'col-sm-'.$style[0]:'').'" for="'.$name.'">'.$label.($required!=''?'<span class="'.$required.'">*</span>':'').'</label>'."\n";
	            	$ret.='                                                 <div class="controls '.(count($style)>1 && $style[1]!=0?'col-sm-'.$style[1]:'').'">'."\n";
	            }
	            if($type=='select'){
	            	$ret.='                                                     <select name="'.$name.'" id="'.$id.'" class="form-control '.$class.'" '.($addhtml!=''?$addhtml:'').' '.$required.'>'."\n";
	            	for($i=0;$i<count($option); $i++){
	            		if($i==0){
	            			$op=current($option);
	            		}else{
	            			$op=next($option);
	            		}
	            		$ret.='                                                     	<option	'.(key($option)==$value?'selected':'').' value="'.key($option).'">'.$op.'</option>'."\n";
	            	}
	            	$ret.='                                                     </select>'."\n";
	            	if($search==true){
	            		$ret.='<div class="chzn-container chzn-container-single" style="width: 220px;" title="" id="select01_chzn"><a href="javascript:void(0)" class="chzn-single" tabindex="-1"><span>something</span><div><b></b></div></a><div class="chzn-drop"><div class="chzn-search"><input type="text" autocomplete="off"></div><ul class="chzn-results"><li class="active-result result-selected" style="" data-option-array-index="0">something</li><li class="active-result" style="" data-option-array-index="1">2</li><li class="active-result" style="" data-option-array-index="2">3</li><li class="active-result" style="" data-option-array-index="3">4</li><li class="active-result" style="" data-option-array-index="4">5</li></ul></div></div>'."\n";
	            	}
	            }else if($type=='textarea'){
	            	$ret.='                                                     <textarea id="bootstrap-editor" name="'.$name.'" id="'.$id.'" class="'.$class.' m-wrap form-control" '.($placeholder!=''?'placeholder="'.$placeholder.'"':'').' '.($addhtml!=''?$addhtml:'').'>'.$value.'</textarea>'."\n";
	            }else{
	            	$ret.='                                                     <input name="'.$name.'" '.($type!='file'?'value="'.$value.'"':"").' id="'.$id.'" type="'.$type.'" '.($maxlength!=''?'maxlength="'.$maxlength.'"':'').'  '.($placeholder!=''?'placeholder="'.$placeholder.'"':'').' class="'.$class.' form-control" '.($type=='file'?'multiple="multiple"':'').' '.$required.' '.($addhtml!=''?$addhtml:'').'/>'."\n";
	            }
	            if($type!='hidden'){
		            if($help!=''){
		            	$ret.='                                                     <i class="help-block">'.$help.'</i>'."\n";
		        	}
		            //$ret.='                                                     <span class="help-inline"></span>'."\n";
		            $ret.='                                                 </div>'."\n";
		            $ret.='                                             </div>'."\n";
				}
			}

			return $ret;
		}


		public function tableView($query,$pageNow='1',$num_page=10,$tabela){

			############CONFIGURAÇÕES###########
			/*
			//Titulo da tabela
				$tabela['name']               =     'Listagem de dados';
			//botão de adicionar
				$tabela['btn']['add'][]         =     array('Adicionar' ,     '');
			//Botoes de opçoes
				$tabela['btn']['options'][]   =     array('Excluir'   ,     $_SERVER['REQUEST_URI'].'excluir/');
			//botão de Pesquisa
				$tabela['btn']['search']      =     true;
			//opções de paginação
				$tabela['btn']['rows']        =     array(15,30,60,120);
			//Definindo colunas da tabela
				$tableActive = array(
				    '0' =>'<button class="btn btn-success btn-mini">Ativo</button>',
				    '1' => '<button class="btn btn-success btn-mini">Ativo</button>'
				);
			//define se haverá boltão de editar na tabela
			$tabela['btn']['column_edit'][]   =     false;
			//recebe array(NomeParaSerExibido=>campoDaTabelaSQL,poderáordenar?,elementosHtml)
				$tabela['btn']['column'][]    =     array('Nome'=>'nome','ordenacao'=>true);
				$tabela['btn']['column'][]    =     array('Cidade'=>'cidade','ordenacao'=>true);
				$tabela['btn']['column'][]    =     array('Status'=>'active','ordenacao'=>false,'html'=>$tableActive);
			*/
			####################################


			$table_erros=array();
			if(!isset($query)){
				return "É necessario informar uma Query";
			}

			//Organiza URL da tabela e paginação
        	$parametro=parse_url(substr($_SERVER['REQUEST_URI'],1));
        	$diretorio=$diretorio_ordem=$GLOBALS['f']->getUrlCompletePath();
        	$path=explode('/',$_SERVER['REQUEST_URI']);

            if(isset($parametro['query'])){ $parametro=$parametro['query']; }else{$parametro='';}
            //defini diretorio do cabeçalho de ordeanação
    		for ($i=0; $i < count($path); $i++) {
    			$diretorio_ordem.=$path[$i].'/';
    		}
            $diretorio_ordem.=($parametro!=''?'?'.$parametro:'');


			$pag = new pagination($query, $pageNow,$num_page);




			$ret='';
			$ret.='<form id="formulario" name="formulario" action="'.$diretorio.'1" method="POST">'."\n";
			$ret.='	   <input type="hidden" valeu="" id="optionSelected">'."\n";



			$ret.='<div class="page-title">'."\n";
		    $ret.='    <h3 class="title pull-left">'.$tabela['name'].'</h3>'."\n";
		    if(isset($tabela['name'])){
			$ret.='		<div class="exibe_resultados pull-right">'."\n";
			            if(isset($tabela['btn']['rows'])){
			$ret.='			<label>'."\n";
			$ret.=' 			<select size="1" name="num_page" class="form-control input-sm num_page" onchange="document.getElementById(\'formulario\').submit();">'."\n";
			                   	for ($i=0; $i < count($tabela['btn']['rows']); $i++) {
			$ret.='					<option value="'.$tabela['btn']['rows'][$i].'" '.($num_page==$tabela['btn']['rows'][$i]?'selected':'').' >'.$tabela['btn']['rows'][$i].' - Resultados</option>'."\n";
			                   }
			$ret.='				</select>'."\n";
			$ret.='			</label>'."\n";
			             }
		    $ret.='		</div>'."\n";
		         }
		    $ret.='</div>'."\n";


		    $ret.='<div class="row">'."\n";
		    $ret.='    <div class="col-md-12">'."\n";
		    $ret.='			<div class="panel panel-default">'."\n";

        	$ret.='				<div class="block-content collapse in">'."\n";
            $ret.='  				<div class="span12">'."\n";


            $ret.='						<div class="btn-toolbar">';
            if(isset($tabela['btn']['search']) && $tabela['btn']['search']==true){

            	$ret.='						<div class="btn-group focus-btn-group searchtable">';
	            $ret.='						      <label><input type="text" class="form-control" name="b" id="b" placeholder="Pesquisar..."></label>'."\n";
	            if(isset($_SESSION['SEARCH_PAGE']) && $_SESSION['SEARCH_PAGE']!=''){
	            	$ret.='							<br/><span>Busca por: </span><b>'.$_SESSION['SEARCH_PAGE'].'</b><button><i class="fa fa-remove"></i></button>'."\n";
	            }
	            $ret.='						</div>'."\n";
        	}




			$ret.='							<div class="btn-group dropdown-btn-group pull-right">';	
			if(isset($tabela['btn']['add']) && is_array($tabela['btn']['add'])){
                  for ($i=0; $i < count($tabela['btn']['add']); $i++) {
                  		$ret.='				<div class="btn-group" style="margin-right: 7px;">'."\n";
                  		$ret.='					<a href="'.(isset($tabela['btn']['add'][$i][1])?$tabela['btn']['add'][$i][1]:$diretorio.'editar').'"><span class="btn btn-success"><i class="fa fa-plus"></i> '.$tabela['btn']['add'][$i][0].'</span></a>'."\n";
                  		$ret.='				</div>'."\n";
                  }
            }


            if(isset($tabela['btn']['options']) && is_array($tabela['btn']['options'])){
			$ret.='							<div data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Ferramentas <span class="caret"></span></div>';
	            $ret.='						<ul class="dropdown-menu">';
	                  for ($i=0; $i < count($tabela['btn']['options']); $i++) {
	                        $ret.='				<li><a '.$tabela['btn']['options'][$i][1].'>'.$tabela['btn']['options'][$i][0].'</a></li>'."\n";
	                  }
	           	$ret.='						</ul>';
            }
			$ret.='						</div>';
			$ret.='					</div>';



            //tabela
            if($pag->rs !== false && $pag->rs->RecordCount()){
            	$ret.='                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="organize">'."\n";
	            $ret.='                                          <thead>'."\n";
	            $ret.='                                                <tr role="row">'."\n";
	            if(isset($tabela['btn']['column'])){
	            	$ret.='                                         	<th width="30" class="txtCenter"><input type="checkbox" id="optionAll"></th>'."\n";
	            	for ($i=0; $i < count($tabela['btn']['column']); $i++) {
	            		$chave = key($tabela['btn']['column'][$i]);
	            		if($tabela['btn']['column'][$i]['ordenacao']==true){
	            			if(isset($_GET[$tabela['btn']['column'][$i][$chave]])){
	            				if($_GET[$tabela['btn']['column'][$i][$chave]]=='asc'){
	            					$varGet='desc';
	            				}else{
	            					$varGet='asc';
	            				}
	            			}else{
	            				$varGet='';
	            			}
	            			$ret.='                                         	<th class="sorting'.($varGet!=''?'_'.$varGet:'').'" role="columnheader" '.($chave=='Código' || $chave=='id'?'width="90"':"").'>'."\n";
	            			if($varGet==''){$varGet='asc';}
	            			$ret.='                                         		<a href="'.$diretorio.'1/?'.$tabela['btn']['column'][$i][$chave].'='.$varGet.'">'.$chave.'</a>'."\n";
	            			$ret.='                                         	</th>'."\n";
	            		}else{
	            			$ret.='                                         	<th>'."\n";
	            			$ret.='                                         		<a href="#" onclick="return false;">'.$chave.'</a>'."\n";
	            			$ret.='                                         	</th>'."\n";
	            		}
	            	}
	            	if(isset($tabela['btn']['column_edit']) && $tabela['btn']['column_edit']==true){
		        		$ret.='													<th width="35"></th>'."\n";
		        	}
	        	}
	            $ret.='                                                </tr>'."\n";
	            $ret.='                                          </thead>'."\n";

		        $ret.='                                          <tbody role="alert" aria-live="polite" aria-relevant="all">'."\n";
		        	$c=1;
					while($row = $pag->rs->FetchRow()){
			            $ret.='                                                <tr class="gradeA odd listagem" id="'.$row['id'].'">'."\n";

			            if(isset($tabela['btn']['column'])){
			            	$ret.='												 <td class="sorting_0"><input class="option" id="option'.$row['id'].'" name="id" type="checkbox" value="'.$row[0].'"></td>'."\n";
			            	for ($i=0; $i < count($tabela['btn']['column']); $i++) {
			            		$chave = key($tabela['btn']['column'][$i]);
			            		if(isset($tabela['btn']['column'][$i]['html']) && is_array($tabela['btn']['column'][$i]['html'])){
			            			$campo = $tabela['btn']['column'][$i][$chave];
			            			$arrayHTML = $tabela['btn']['column'][$i]['html'];
			            			if(!isset($row[$campo])){
			            				$valor='';
			            				$table_erros[$i] = 'falta posição do row: '.$campo;
				            		}else{

				            			if(isset($arrayHTML[$row[$campo]])){
				            				$valor = $arrayHTML[$row[$campo]];
				            			}else{
				            				$valor='';
				            				$table_erros[$i] = 'falta posição do array: '.$row[$campo];
				            			}
			            			}
			            		}else{
			            			$campo = $tabela['btn']['column'][$i][$chave];
			            			if(isset($row[$campo])){
			            					if($campo=='date_update' && $row['date_update']!='0000-00-00 00:00:00'){
				            					$d = new data();
				            					$v=$d->formattedInterval(new data($row['date_update']));
				            					$valor = $v;
				            				}else{
				            					$valor = $row[$campo];
				            				}
			            			}else{
			            				$table_erros[$i] = 'falta posição do row: '.$campo;
			            				$valor = '';
			            			}
			            		}
			            		$ret.='												 <td class="sorting_'.($i+1).'">'.$valor.'</td>'."\n";
			            	}
			        	}
			        	if(isset($tabela['btn']['column_edit']) && $tabela['btn']['column_edit']==true){
			        		$ret.='													<td class="txtCenter"><a href="'.$diretorio.'edit/'.$row['id'].'"><i class="fa fa-pencil" aria-hidden="true"></i></td>'."\n";
			        	}

			            $ret.='                                                </tr>'."\n";
			            $c++;
		        	}
			}else{
				$ret.=' Nenhum registro cadastrado nesse setor.'."\n";
			}
	        $ret.='                                          </tbody>'."\n";
            $ret.='                                    	</table>'."\n";
            $ret.='                                    	<div class="row">'."\n";
            $ret.=                  						pagination::instance_paging($diretorio,$parametro);
            if(count($table_erros)>0){
            	$ret.='<span style="color:#F00;">';
            	for($i=0;$i<count($table_erros);$i++){
            		if($i==0){
            			$ret.='<hr> ### FALHAS DA TABELA:<br/>';
            			$ret.=current($table_erros).'<br/>';
            		}else{
            			$ret.=next($table_erros).'<br/>';
            		}
            	}
            	$ret.='</span>';
        	}
            $ret.='										</div><!-- row -->'."\n";
            $ret.='                              </div>'."\n";
            $ret.='                  </div><!-- block-content -->'."\n";
            $ret.='				</form><!-- form -->'."\n";

		    $ret.='        </div>'."\n";
		    $ret.='    </div>'."\n";
		    $ret.='</div> <!-- End Row -->'."\n";


            return $ret;
		}



	}