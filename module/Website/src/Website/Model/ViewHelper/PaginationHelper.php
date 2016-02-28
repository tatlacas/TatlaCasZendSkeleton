<?php
namespace Website\Model\ViewHelper;
use Zend\View\Helper\AbstractHelper;

/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/26/2015
 * Time: 11:23 AM
 */
class PaginationHelper  extends AbstractHelper
{
    private $resultsPerPage;
    private $totalResults;
    private $results;
    private $baseUrl;
    private $paging;
    private $page;

    public function __invoke($pagedResults, $page, $baseUrl, $resultsPerPage=10)
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->totalResults = count($pagedResults);
        $this->results = $pagedResults;
        $this->baseUrl = $baseUrl;
        $this->page = $page;

        return $this->generatePaging();
    }

    /**
     * Generate paging html
     */
    private function generatePaging()
    {
        $li = "<li>";
        $close = "</li>";
        # Get total page count
        $pages = ceil($this->totalResults / $this->resultsPerPage);

        # Don't show pagination if there's only one page
        if($pages == 1)
        {
            return;
        }

        # Show back to first page if not first page
        if($this->page != 1)
        {

            $this->paging = ".$li.<a href=" . $this->baseUrl . "page/1".">"."<<"."</a>".$close."";
        }

        # Create a link for each page
        $pageCount = 1;
        while($pageCount <= $pages)
        {
            $this->paging .= ".$li.<a href=" . $this->baseUrl . "page/" . $pageCount . ">" . $pageCount . "</a>".$close."";
            $pageCount++;
        }

        # Show go to last page option if not the last page
        if($this->page != $pages)
        {
            $this->paging .= ".$li.<a href=" . $this->baseUrl . "page/" . $pages . ">".">>"."</a>".$close."";
        }

        return $this->paging;
    }
}
