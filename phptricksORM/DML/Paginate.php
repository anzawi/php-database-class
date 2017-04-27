<?php

namespace PHPtricks\Orm\DML;

trait Paginate
{
	/**
	 * @sense v.2.1.0
	 * pagination functionality
	 * @param int $recordsCount count records per page
	 * @return array
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPtricks\Orm\Database::connect();
	 * $results = $db->table("blog")->paginate(15);
	 *
	 * var_dump($results);
	 *
	 * now add to url this string query (?page=2 or 3 or 4 .. etc)
	 * see (link() method to know how to generate navigation automatically)
	 */
	public function paginate($recordsCount = 0, $last = false)
	{
		if($recordsCount === true)
		{
			$last = true;
			$recordsCount = 0;
		}

		if($recordsCount === 0)
			$recordsCount = config("pagination.records_per_page");

		// this method accept one argument must be an integer number .
		if(!is_integer($recordsCount))
		{
			trigger_error("Oops, the records count must be an integer number"
				. "<br> <p><strong>paginate method</strong> accept one argument must be"
				." an <strong>Integer Number</strong> , " . gettype($recordsCount) . " given!</p>"
				. "<br><pre>any question? contact me on team@phptricks.org</pre>", E_USER_ERROR);
		}
		// check current page
		$startFrom = isset($_GET[config("pagination.link_query_key")]) ?
			($_GET[config("pagination.link_query_key")] - 1) * $recordsCount : 0;

		// get pages count rounded up to the next highest integer
		$this->_colsCount = ceil(count($this->select()->results()) / $recordsCount);

		// return query results
		return $this->limit($startFrom, $recordsCount)->select(['*'], $last);
	}

	/**
	 * check if we have a string query in current uri other (pagination key)
	 * if not so return (?) otherwise we want to reorder a string query to keep other keys
	 * @return string
	 */
	private function checkAndGetUriQuery()
	{
		$get = $_GET;
		// remove pagination key from query string
		unset($get[config("pagination.link_query_key")]);
		// init query string and set init value (?)
		$queryString = "?";
		// check if we have other pagination key in query string
		if(count($get))
		{
			// reorder query string to keep other keys
			foreach ($get as $key => $value)
			{
				$queryString .= "{$key}" .
					(!empty($value) ? "=" : "") . $value . "&";
			}

			return $queryString;
		}


		return "?";
	}

	/**
	 * @return int pages count when use paginate() method
	 */
	public function pagesCount()
	{
		if($this->_colsCount < 0)
			return null;

		return $this->_colsCount;
	}

	/**
	 * create pagination list to navigate between pages
	 * @return string (html)
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPtricks\Orm\Database::connect();
	 * $db->table("blog")->where("vote", ">", 2)->paginate(5);
	 * echo $db->link();
	 */
	public function link()
	{
		// get current url
		$link = $_SERVER['PHP_SELF'];

		// current page
		$currentPage =
			(isset($_GET[config("pagination.link_query_key")]) ?
				$_GET[config("pagination.link_query_key")]
				: 1);
		/**
		 * $html var to store <ul> tag
		 */
		$html = '';
		if($this->_colsCount > 0) // check if columns count is not 0 or less
		{
			$operator = $this->checkAndGetUriQuery();

			$html = "<ul class=\"pagination\">";
			// loop to get all pages
			for ($i = 1; $i <= $this->_colsCount; $i++)
			{
				// we need other pages link only ..
				if($i == $currentPage)
				{
					$html .= "<li>{$i}</li>";
				}
				else
				{
					$html .= "<li><a href=\"{$link}{$operator}" .
						config("pagination.link_query_key") .
						"={$i}\">{$i}</a></li>";
				}
			}

			$html .= "</ul>";
		}

		return $html;
	}
}