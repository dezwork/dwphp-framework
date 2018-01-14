<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleberson Bieleski
 * @Last Modified time: 2018-01-12 07:28:53
 */

	namespace DwPhp\Library;

	class pagination{

		public $rs;
		private static $instance = false;		
		private $current;
		private $numPages;
		private $minPage;
		public $maxPage;
		public $pageReg;

		/* CONSTRUCT */
		public function __construct($sqlOrMaxpage, $current, $pageReg = 2, $numPages = 10){
			pagination::$instance = $this;
			$this->current = $current;
			$this->numPages = $numPages;
			$this->minPage = 1;
			$this->maxPage = 0;
			$this->pageReg = $pageReg;
			if(!is_numeric($sqlOrMaxpage)){
				$offset = ($this->current-1)*$pageReg;
				$numrows = $pageReg;
				$sql = preg_replace('/^SELECT(?! SQL_CALC_FOUND_ROWS)/', 'SELECT SQL_CALC_FOUND_ROWS', $sqlOrMaxpage);

				$this->rs = $GLOBALS['CONN']->SelectLimit($sql, $numrows, $offset);
				if($this->rs !== false)
					$this->maxPage = ceil($GLOBALS['CONN']->GetOne('SELECT FOUND_ROWS()')/$pageReg);
			}else{
				$this->maxPage = $sqlOrMaxpage;
			}
			if($this->current > $this->maxPage)
				$this->current = $this->maxPage;
		}
		/* LINK */
		private function link($uri, $uri2, $qs = ''){
			//return $uri == '#/' ? '#' : func::link($uri.$uri2, 0, $qs); exit();
			return $uri == '#/' ? '#' : $uri.$uri2; exit();
		}

		/* PAGING SITE */
		private function paging_view($numPages, $initPages, $uri, $qs){
			/*
				alteravel
			*/
		


			$ret='                                           <div class="col-sm-6">'."\n";
            $ret.='                                                <div  class="dataTables_info" role="status" aria-live="polite">'."\n";
			if($this->current==''){$this->current=1;}
			if(($this->maxPage*$this->pageReg)>0)
                //$ret.='          <div class="floatL" id="dyntable_info">Exibindo '.$c.' registros</div>'."\n";
				$ret.='          Exibindo '.((($this->current-1)*$this->pageReg)+1).' a '.(($this->current*$this->pageReg)).' de '.($this->maxPage*$this->pageReg).' registros'."\n";
            $ret.='                                                </div>'."\n";
            $ret.='                                          </div>'."\n";
            $ret.='                                          <div class="col-sm-6">'."\n";
            $ret.='                                                <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">'."\n";
            $ret.='                                                      <ul class="pagination pull-right">'."\n";
			if($this->current > $this->minPage){
				//$ret .= '<a href="'.$this->link($uri, $this->minPage, $qs).'" class="first paginate_button paginate_button_disabled" id="dyntable_first">Primeira</a>';
				$ret .= '<li class="paginate_button previous" aria-controls="datatable" tabindex="0" id="datatable_previous"><a href="'.$this->link($uri, $this->current-1, $qs).'">Anterior</a></li>';
			}
			if($numPages !== false)
				for($numPages, $initPages; $numPages >= 0; $numPages--, $initPages++)
					if($initPages != $this->current)
						$ret .= '<li class="paginate_button" aria-controls="datatable" tabindex="0"><a href="'.$this->link($uri, $initPages, $qs).'" title="página '.$initPages.'">'.$initPages.'</a></li>';
					else
						$ret .= '<li class="paginate_button active" aria-controls="datatable" tabindex="0"><a href="#">'.$initPages.'</a></li>';

			if($this->current < $this->maxPage){
				$ret .= '<li class="paginate_button next" aria-controls="datatable" tabindex="0" id="datatable_next"><a href="'.$this->link($uri, $this->current+1, $qs).'">Próxima</a></li>';
				//$ret .= '<a href="'.$this->link($uri, $this->maxPage, $qs).'" class="last paginate_button" id="dyntable_last">Última</a>';
			}

            $ret.='                                                      </ul>'."\n";
            $ret.='                                                </div>'."\n";
            $ret.='                                          </div>'."\n";
			/*
				fim alteravel
			*/
            if($initPages<1){$ret='';}
			return $ret;
		}

		/* PAGING */
		public function paging($uri = '', $qs = ''){
			if($this->maxPage == $this->minPage) return false;
			
			$uri = preg_replace('/([^\/])$/', '\\1/', $uri); // coloca '/' no fim de URI
			if($this->maxPage <= $this->numPages){
				$numPages = $this->maxPage-1;
				$initPages = $this->minPage;
			}else{
				$numPages = $this->numPages;
				$pagDiv = ceil($numPages/2);
				if($pagDiv >= $this->current){
					$initPages = $this->minPage;
				}elseif(($this->current+$pagDiv) > $this->maxPage){
					$initPages = $this->maxPage - $this->numPages;
				}else{
					$initPages = $this->current - $pagDiv;
				}
			}
			
			$ret = '';
				$ret .= $this->paging_view($numPages, $initPages, $uri, $qs);
			return $ret;
		}
		
		/* INSTANCE PAGING */
		public static function instance_paging($uri = '', $qs = ''){
			if(pagination::$instance)
				return pagination::$instance->paging($uri, $qs);
		}
	}
?>