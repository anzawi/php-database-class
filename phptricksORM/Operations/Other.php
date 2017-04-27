<?php

namespace PHPtricks\Orm\Operations;

trait Other
{
	/**
	 * How to Use:
	 *
	 * $db = PHPtricks\Orm\Database::connect();
	 * $db->table("blog")->where("vote", ">", 2)->select();
	 * echo $db->dataView();
	 */
	public function dataView()
	{
		// get columns count to create the table
		$colsCount = count($this->firstRecord());
		// if no data received so return no data found!
		if($colsCount <= 0)
		{
			return config("pagination.no_data_found_message");
		}

		// to fix for counter -> on array we want to counter from columns count -1
		// on object we want the records count
		if(is_array($this->_results) && isset($this->_results[0]) && is_array($this->_results[0])) $colsCount -= 1;
		// get Columns name's
		$colsName = array_keys((array)$this->firstRecord());

		// init html <table> tag
		$html = "<table border=1><thead><tr>";

		/**
		 * create table header
		 * its contain table columns names
		 */
		foreach ($colsName as $colName)
		{
			$html .= "<th>";
			// get column name
			/**
			 * the getColumnName() function define in (config_function.php) file
			 * this function replace (_) to space for example (column_name -> Column Name)
			 * of separate words (columnName -> Column Name)
			 */
			$html .= getColumnName($colName);
			$html .= "</th>";
		}

		// end table header tag and open table body tag
		$html .= "</tr></thead><tbody>";

		// loop all results to create the table (tr's and td's)
		foreach ((array)$this->results() as $row)
		{
			$row = (array)$row; // make sure the $row is array and not an object

			if(count($row) > 1)
			{
				$html .= "<tr>"; // open tr tag
				// loop all columns in row to create <td>'s tags
				for ($i = 0; $i <= $colsCount; $i++)
				{
					$html .= "<td>";
					$html .= $row[$colsName[$i]]; // get current data from the row
					$html .= "</td>";
				}

				$html .= "</tr>";
			}
			else // first method is called not select
			{
				$html .= "<td>";
				$html .= $row[0]; // get current data from the row
				$html .= "</td>";
			}
		}

		$html .= "</tbody></table>";

		return $html; // return created table
	}

	/**
	 * get first row from query results
	 * @return Database
	 */
	public function firstRecord()
	{
		$results = (array)$this->_results;

		if(count($results))
		{
			return isset($results[0]) ? $results[0] : $results;
		}

		return [];
	}
}