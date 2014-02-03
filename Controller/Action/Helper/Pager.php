<?php

require_once("Zend/Controller/Action/Helper/Abstract.php");

class Can_Controller_Action_Helper_Pager extends Zend_Controller_Action_Helper_Abstract
{

	private $_page = 1;

	private $_limit = 1;

	private $_foundRows = 0;

	private $_pageSize = 0;

	private $_pagePad = 4;

	private $_defaultOffset = 0;

	private $_url = "";

	private $_seo = TRUE;

	private $_isBasic = FALSE;

	private $_text
			= array(
					'next' => 'Sonraki',
					'prev' => 'Önceki'
			);

	private $_view = NULL;

	/**
	 * @param array $text
	 */
	public function setText($text)
	{
		$this->_text = $text;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getText()
	{
		return $this->_text;
	}

	/**
	 * @param boolean $isBasic
	 */
	public function setIsBasic($isBasic)
	{
		$this->_isBasic = $isBasic;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsBasic()
	{
		return $this->_isBasic;
	}

	/**
	 * @param boolean $seo
	 */
	public function setSeo($seo)
	{
		$this->_seo = $seo;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getSeo()
	{
		return $this->_seo;
	}

	/**
	 * @param int $defaultOffset
	 */
	public function setDefaultOffset($defaultOffset)
	{
		$this->_defaultOffset = $defaultOffset;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getDefaultOffset()
	{
		return $this->_defaultOffset;
	}

	public function setFoundRows($value)
	{
		$this->_foundRows = (int)$value;
		$this->_foundRows -= $this->getDefaultOffset();

		if ($this->_foundRows > 0 && ($this->_page > $this->_foundRows)) {
			Zend_Controller_Action_HelperBroker::getStaticHelper("redirector")->gotoUrl($this->_url);
		}

		return $this;
	}

	public function setPagePad($value)
	{
		$this->_pagePad = (int)$value;

		return $this;
	}

	public function setPage($value)
	{
		$page = (int)$value;
		$this->_page = (($page <= 0) ? 1 : $page);

		return $this;
	}

	public function setUrl($value)
	{
		$this->_url = $value;

		return $this;
	}

	public function setLimit($value)
	{
		$this->_limit = $value;

		return $this;
	}

	public function getLimit()
	{
		return $this->_limit;
	}

	public function getOffset()
	{
		return (($this->_page - 1) * $this->_limit) + $this->getDefaultOffset();
	}

	public function getPrev()
	{
		return (($this->_page > 1) ? ($this->_page - 1) : FALSE);
	}

	public function getNext()
	{
		return (($this->_page < $this->_pageSize) ? ($this->_page + 1) : FALSE);
	}

	public function setView(Zend_View_Interface &$view = NULL)
	{
		$this->_view = $view;

		return $this;
	}

	private function _isFirstPage()
	{
		return (bool)($this->_page <= 1);
	}

	private function _isLastPage()
	{
		return (bool)($this->_page >= $this->_foundRows);
	}

	public function __construct()
	{
	}

	public function init()
	{
		$this->_pageSize = ceil($this->_foundRows / $this->_limit);

		return $this;
	}


	private function _generateUrl($page)
	{
		if ($this->getSeo()) {
			if ($page > 1) {
				return $this->_url . '/page/' . $page;
			} else {
				return $this->_url;
			}
		} else {
			return $this->_url . '?page=' . $page;
		}
	}

	public function render()
	{
		$minPage = max(1, ($this->_page - $this->_pagePad));
		$maxPage = min($this->_pageSize, ($this->_page + $this->_pagePad));
		$prev = $this->getPrev();
		$next = $this->getNext();
		$layout = array();
		if (!$this->getIsBasic()) {
			if ($this->_foundRows > $this->_limit) {
				$layout[] = '<ul class="pagination pagination-centered">';
				if ($prev) {
					// First Page
				}
				$layout[] = '<li class="first-page' . (!$prev ? ' disabled' : '') . '"><a href="' . (!$prev
								? 'javascript:void(0);' : $this->_generateUrl(1)) . '">İlk</a></li>';
				// Prev Page
				$layout[] = '<li class="prev' . (!$prev ? ' disabled' : '') . '"><a href="' . (!$prev
								? 'javascript:void(0);' : $this->_generateUrl($this->_page - 1))
						. '"><i class="fa fa-angle-left"></i> ' . $text['prev'] . '</a></li>';
				if ($minPage > 1) {
					$layout[] = '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
				}
				for ($i = $minPage; $i <= $maxPage; $i++) {
					if ($i == $this->_page) {
						// Current Page
						$layout[] = '<li class="active"><a href="' . $this->_generateUrl($i) . '">' . $i . '</a></li>';
					} else {
						// Page Numbers
						$layout[] = '<li><a href="' . $this->_generateUrl($i) . '">' . $i . '</a></li>';
					}
				}
				if ($this->_pageSize > $maxPage) {
					$layout[] = '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
				}
				// Next Page
				$layout[] = '<li class="next' . (!$next ? ' disabled' : '') . '"><a href="' . (!$next
								? 'javascript:void(0);' : $this->_generateUrl($this->_page + 1))
						. '">' . $text['next'] . ' <i class="fa fa-angle-right"></i></a></li>';
				if ($next) {
					// Last Page
				}
				$layout[] = '<li class="last-page' . (!$next ? ' disabled' : '') . '"><a href="' . (!$next
								? 'javascript:void(0);' : $this->_generateUrl($this->_pageSize)) . '">Son</a></li>';
				$layout[] = '</ul>';
			}

		} else {
			$text = $this->getText();
			$layout[] = '<ul class="pagination pagination-centered">';
			if ($prev) {
				$layout[]
						= '<li class="prev"><a href="' . $this->_generateUrl($this->_page - 1)
						. '"><i class="fa fa-angle-left"></i> ' . $text['prev'] . '</a></li>';
			}
			if ($next) {
				// Last Page
				$layout[] = '<li class="next"><a href="' . $this->_generateUrl($this->_page + 1) . '">' . $text['next']
						. ' <i class="fa fa-angle-right"></i></a></li>';
			}
			$layout[] = '</ul>';
		}
		if ($this->_view == NULL) {
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper("viewRenderer");
			if ($viewRenderer->view === NULL) {
				$viewRenderer->initView();
			}
			$this->_view = $viewRenderer->view;
		}
		$this->_view->pagerLayout = implode("", $layout);
	}
}