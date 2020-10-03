<?php
/**
 * *
 *  * please don't remove this comment block
 *  *
 *  * @author phptricks Team - Mohammad Anzawi
 *  * @author_uri https://phptricks.org
 *  * @uri https://github.com/anzawi/php-database-class
 *  * @version 5.0.0
 *  * @licence MIT -> https://opensource.org/licenses/MIT
 *  * @package PHPtricks\Orm
 *
 */

namespace PHPtricks\Orm\DML;

trait Paginate
{

    /**
     * @sense v.2.1.0
     * pagination functionality
     *
     * @param  int  $recordsCount  count records per page
     *
     * @return array
     */
 
    public function paginate($recordsCount = 0, $last = false)
    {
        if ($recordsCount === true) {
            $last         = true;
            $recordsCount = 0;
        }

        if ($recordsCount === 0) {
            $recordsCount = config("pagination.records_per_page");
        }

        // this method accept one argument must be an integer number .
        if ( ! is_integer($recordsCount)) {
            trigger_error("Oops, the records count must be an integer number"
                          ."<br> <p><strong>paginate method</strong> accept one argument must be"
                          ." an <strong>Integer Number</strong> , "
                          .gettype($recordsCount)." given!</p>"
                          ."<br><pre>any question? contact me on team@phptricks.org</pre>",
                E_USER_ERROR);
        }

        $uriQueryKey = config("pagination.link_query_key");
        // check current page
        $startFrom = isset($_GET[$uriQueryKey]) ?
            ($_GET[$uriQueryKey] - 1) * $recordsCount
            : 0;

        // get pages count rounded up to the next highest integer
        $this->_colsCount = ceil(count($this->select()->results())
                                 / $recordsCount);

        // return query results
        return $this->limit($startFrom, $recordsCount)->select(['*'], $last);
    }

    /**
     * @return int pages count when use paginate() method
     */
    public function pagesCount()
    {
        if ($this->_colsCount <= 0) {
            return null;
        }

        return $this->_colsCount;
    }

  
    public function link()
    {
        // get current url
        $link = $_SERVER['PHP_SELF'];
        $uriQueryKey = config("pagination.link_query_key");

        // current page
        $currentPage
            = (isset($_GET[$uriQueryKey]) ?
            $_GET[$uriQueryKey]
            : 1);
        /**
         * $html var to store <ul> tag
         */
        $html = '';
        if ($this->_colsCount > 0) // check if columns count is not 0 or less
        {
            $operator = $this->checkAndGetUriQuery($uriQueryKey);

            $html = "<ul class=\"pagination\">";
            // loop to get all pages
            for ($i = 1; $i <= $this->_colsCount; $i++) {
                // we need other pages link only ..
                if ($i == $currentPage) {
                    $html .= "<li>{$i}</li>";
                } else {
                    $html .= "<li><a href=\"{$link}{$operator}".
                                $uriQueryKey.
                             "={$i}\">{$i}</a></li>";
                }
            }

            $html .= "</ul>";
        }

        return $html;
    }

    /**
     * create pagination list to navigate between pages
     *
     * @return string (html)
     */

    /**
     * check if we have a string query in current uri other (pagination key)
     * if not so return (?) otherwise we want to reorder a string query to keep
     * other keys
     *
     * @return string
     */
    private function checkAndGetUriQuery($uriQueryKey)
    {
        $get = $_GET;
        // remove pagination key from query string
        unset($get[$uriQueryKey]);
        // init query string and set init value (?)
        $queryString = "?";
        // check if we have other pagination key in query string
        if (count($get)) {
            // reorder query string to keep other keys
            foreach ($get as $key => $value) {
                $queryString .= "{$key}".
                                (! empty($value) ? "=" : "").$value."&";
            }

            return $queryString;
        }


        return "?";
    }

}