<?php 

require_once 'Product.php';

class Pagination
{
	private $pageRange = 10;
	private $totalProducts;
	private $totalPages;

	public function __construct($currPage)
	{
		$this->currPage = $currPage;
		$this->totalProducts = Product::getProductsCount();
		$this->totalPages = round($this->totalProducts / 10);
	}

	public function getPages()
	{
		$pages = '<ul><form action="" method="GET">';

		$limits = $this->limits();

		if (is_null($this->currPage) || !is_numeric($this->currPage)) {
			$this->currPage = 1;
		}

		for ($i = $limits[0]; $i <= $limits[1]; $i++) {
			if ($i == $this->currPage) {
				$pages .= '<input type="submit" name="" size=1 value="'
					. $i . '" class="currentPage">';
			}
			else {
				$pages .= '<input type="submit" name="page" size=1 value="'
					. $i . '" class="page">';
			}
		}

		$pages .= '</form></ul>';

		return $pages;
	}

	private function limits()
	{
		$left = $this->currPage - 4;
		$start = $left > 0 ? $left : 1;

		if ($start + 10 <= $this->totalPages) {
			$end = $start + $this->pageRange;
		}
		else {
			$end = $this->totalPages;
			$start = $this->totalPages - $this->pageRange > 0 ? 
				$this->totalPages - $this->pageRange : 1;
		}

		return array($start, $end);
	}
}

 ?>