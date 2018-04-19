<?php


class LinkParser
{
    /**
     * @var string  what we parce
     */
    public $parcing_site = 'https://www.ua-region.info';
    /**
     * @var string url where are categories
     */
    public $first_url = 'https://www.ua-region.info/kved/';
    /**
     * @var string Additional param 4 pager, recurse get pages
     */
    public $pager_param = '?start_page=';
    /**
     * @var array of catalogs (kveds)
     */
    public $array_of_kveds = array();
    /**
     * @var array resulting array of companies
     */
    public $array_of_companies = array();
    /**
     * @var array of getting links
     */
    public $array_of_links = array();
    /**
     * @var string folder. where to store data
     */
    private $download_folder = 'download/';
    /**
     * @var string file. where sore kveds
     */
    private $file_kveds = "kveds.json";
    /**
     * @var string  where storing data with URLs and emails
     */
    private $file_urls = "urls.txt";
    /**
     * @var string file. log file
     */
    private $file_logs = "log.txt";

//// print_r($html);

    public function __construct()
    {
        include('simplehtmldom_1_5/simple_html_dom.php');
        include('getcurl.php');
    }

    /**
     * get HTML via CURL
     * @param String $url
     * @return string
     */
    function getHtml($url)
    {
//        echo '<p>Getting HTML...</p>';
        $html = curl_get($url);
        return $html;
    }

    /**
     * get DOM from HTML
     * @param String $html
     * @return bool|simple_html_dom
     */
    function getDom($html)
    {
//        echo '<p>Getting DOM...</p>';
        $dom = str_get_html($html);
        return $dom;
    }

    /**
     * get all links from DOM
     * @param String $dom
     * @return array
     */
    function getLinks($dom)
    {
//        echo '<p>Searching 4 links...</p>';
        $array_of_links = array();
        foreach ($dom->find('a') as $e) {
            $href_url = $e->href;
            $array_of_links[] = $href_url;
//            echo $href_url . '<br>';
///  if(!file_exists('download/'.$imagename)){copy($imgurl,"download/$imagename");}
        }
        return $array_of_links;
    }

    /**
     * get H1 caption of category
     * @param $dom
     * @return string
     */
    /*private function getH1ofCompanies($dom)
    {
//        echo '<p>Get H1 of category...</p>';
        $h1 = $dom->find('h1.b-company-rating-header');
        return $h1[0];
    }*/

    /**
     * get H1 caption of category
     * @param $dom
     * @return Integer
     */
    private function getH2ofCompanies($dom)
    {
//        echo '<p>Searching names of companies count...</p>';
        $h2 = $dom->find('h2[itemprop="name"]');
        return count($h2);
    }

    /**
     * put only links 4 kved
     * @param Array $array_of_links
     * @return array
     */
    function sortKveds($array_of_links)
    {
//        echo '<p>Sorting Categories (kveds)...</p>';
        $kveds = array();
        $array_of_kveds = preg_grep("/^[\/]kved\/[\w\d]+\./", $array_of_links);
        foreach ($array_of_kveds as $kved) {
            $kveds[] = $kved;
        }
        if (!file_put_contents($this->download_folder . $this->file_kveds, json_encode($kveds))) {
            file_put_contents($this->download_folder . $this->file_logs, 'kved not wrote.' . PHP_EOL, FILE_APPEND);
        };
        return $kveds;
    }

    /**
     * sorting emails and url
     * @param $array_of_links
     * @return array
     */
    function sortEmailsAndUrl($array_of_links)
    {
//        $kved_file_urls = ($kved_file_urls) ? preg_replace("/[\/.-:](?:[a-zA-Z]+)[\/.-:]([a-zA-z]+)/", "$1", $kved_file_urls) : $this->file_urls;
//        echo '<p>Getting emails and URLs...</p>';
//        $array_of_url = preg_grep("/(https?:\/\/[^plus][\w]+\.[^ua|fac]+)|(mailto:(?!info))|(^[/][/\d]+\z)/", $array_of_links);
        $array_of_url = preg_grep("/(https?:\/\/(?!(?:www.)?(?:ua-regi[\w.]+|facebook|twitter)|vk|plus.google))|(mailto:(?!info))|(^[\/][\/\d]+\z)/", $array_of_links);
        if (!file_put_contents($this->download_folder . "$this->file_urls", implode(PHP_EOL, $array_of_url), FILE_APPEND)) {
            file_put_contents($this->download_folder . $this->file_logs, 'url not wrote. ' . PHP_EOL, FILE_APPEND);
        };
        return $array_of_url;
    }


// INIT
    function init($url)
    {
//        echo '<p>We are started...</p>';
        $html = $this->getHtml($url);
        $dom = $this->getDom($html);
        $array_of_links = $this->getLinks($dom);
        $array_of_kveds = $this->sortKveds($array_of_links);
        return $array_of_kveds;
//        $this->recurseKvedArray($array_of_kveds);
    }

    /**
     * recursing in pages in kved category
     * @param $kved
     */
    function recurseKvedArray($kved)
    {
//        echo '<p>Kveds recurse started...</p>';
//            $kved = reset($array_of_kveds);
        echo('e');
        for ($i = 1; ; $i++) {
            $url = $this->parcing_site . $kved . $this->pager_param . $i;
            echo "$url<br>";
            $html = $this->getHtml($url);
            $dom = $this->getDom($html);
            $arr_of_companies = $this->getLinks($dom);
            $h2 = $this->getH2ofCompanies($dom);
            $links = $this->sortEmailsAndUrl($arr_of_companies);
            var_dump($links);
            if ($h2 == 0) {
                break;
            }
        }
    }

    /**
     * does kved file exist's
     * @return bool
     */
    public function existingKvedFile()
    {
        if (file_exists($this->download_folder . $this->file_kveds)) {
            return True;
        } else {
            echo "<p>File Kveds <strong>doesn't exists</strong></p>";
            return False;
        }
    }

    /**
     * getting Kved file if exist
     * @return array|mixed
     */
    public function getKvedFile()
    {
        $kveds = array();
        if ($this->existingKvedFile()) {
            try {
                $kveds = json_decode(file_get_contents('download/kveds.json'), true);
                print_r($kveds);
            } catch (Exception $e) {
                echo "<p>$e</p>";
            }
        }
        return $kveds;
    }
}

// LET'S FUNNY

$start_index = $_GET['start_index'];
$end_index = $_GET['end_index'];
$LP = new LinkParser();
// file with kveds exists

if ($start_index and $LP->existingKvedFile()) {
    $kveds = $LP->getKvedFile();
    if (key_exists($start_index, $kveds)) {
        echo "<h3>Parse $kveds[$start_index]</h3>";
        $LP->recurseKvedArray($kveds[$start_index]);
        echo "<p>Well done</p>";
    } else {
        die("<p><strong>$start_index</strong> doesn't exist</p>");
    }
   

} else //new request
{
    $arr_of_kveds = $LP->init($LP->first_url);
//$i++;
    if (count($arr_of_kveds) > 0) {
        foreach ($arr_of_kveds as $kved) {
            $LP->recurseKvedArray($kved);
        }
        echo '<p>Kveds stored. Add <pre>?start_index=7007</pre> to display results</p>';
    } else echo '<p>Nothing do here</p>';
}

