<?php


class LinkParser
{
	/**
	 * @var string  what we parce
	 */
	public $url = 'https://www.google.com.ua/search?q=site:.edu.ua&num=100';
	/**
	 * @var string Additional param 4 pager, recurse get pages
	 */
	private $search_mask = "/(?:https?:\/\/)?(?:www\.)?[-\d\wа-яі\._]+.edu.ua/i";
	/**
	 * @var string param which uses 4 step 2 another page
	 */
	public $pager_param = '&start=';
	/**
	 * @var array of getting links
	 */
	public $array_of_links = array();
	/**
	 * @var string folder. where to store data
	 */
	private $download_folder = 'download/';
	/**
	 * @var string  where storing data with URLs and emails
	 */
	private $file_urls = "urls-eduua.txt";
	/**
	 * @var string file. log file
	 */
	private $file_logs = "log.txt";
	/**
	 * @var int count per page
	 */
	private $start_index;

	public function __construct($start_index)
	{
		include_once('simplehtmldom_1_5/simple_html_dom.php');
		include_once('getcurl.lib');
		$this->start_index = $start_index;
	}

	/**
	 * get HTML via CURL
	 * @param String $url
	 * @return string
	 */
	function getHtml($url)
	{
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
		$array_of_links = array();
		foreach ($dom->find('a') as $e) {
			$array_of_links[] = strtolower($e->href);
///  if(!file_exists('download/'.$imagename)){copy($imgurl,"download/$imagename");}
		}
		return $array_of_links;
	}

	/**
	 * sorting emails and url
	 * @param $array_of_links
	 * @return array
	 */
	function sortUrl($array_of_links)
	{
		preg_match_all($this->search_mask, $array_of_links, $array_of_url);
		if (!file_put_contents($this->download_folder . "$this->file_urls", implode(PHP_EOL, $array_of_url[0]), FILE_APPEND | LOCK_EX)) {
			file_put_contents($this->download_folder . $this->file_logs, 'url not wrote. ' . PHP_EOL, FILE_APPEND | LOCK_EX);
		};
		return $array_of_url;
	}


// INIT
	function init($url)
	{
		$html = $this->getHtml($url);
		$dom = $this->getDom($html);
		$array_of_links = $this->getLinks($dom);
		# TODO: debug section
//		$array_of_links = "/search?q=site:.org.ua&amp;gbv=1&amp;sei=1MD-WueaAaTt6QSC-ZA4https://www.google.com.ua/intl/uk/options/https://accounts.google.com/ServiceLogin?hl=uk&amp;passive=true&amp;continue=https://www.google.com.ua/search%3Fq%3Dsite:.org.ua%26num%3D10https://www.google.com.ua/webhp?hl=uk&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQPAgD//support.google.com/websearch/answer/186645?hl=uk#/search?q=site:.org.ua&amp;source=lnms&amp;tbm=isch&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQ_AUICigB/search?q=site:.org.ua&amp;source=lnms&amp;tbm=nws&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQ_AUICygChttps://maps.google.com.ua/maps?q=site:.org.ua&amp;num=10&amp;um=1&amp;ie=UTF-8&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQ_AUIDCgD/search?q=site:.org.ua&amp;source=lnms&amp;tbm=vid&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQ_AUIDigA/search?q=site:.org.ua&amp;source=lnms&amp;tbm=bks&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQ_AUIDygBhttps://www.google.com.ua/flights?q=site:.org.ua&amp;source=lnms&amp;tbm=flm&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQ_AUIECgC/search?q=site:.org.ua&amp;source=lnms&amp;tbm=fin&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQ_AUIESgD/preferences/preferences?hl=uk&amp;prev=https://www.google.com.ua/search?q%3Dsite:.org.ua%26num%3D10/preferences?hl=uk&amp;prev=https://www.google.com.ua/search?q%3Dsite:.org.ua%26num%3D10#languages/setprefs?safeui=on&amp;sig=0_1M75zo3HQI8olpKZaWPN34obdgY%3D&amp;prev=https://www.google.com.ua/search?q%3Dsite:.org.ua%26num%3D10/advanced_search?q=site:.org.ua&amp;hl=uk/history/optout?hl=uk//support.google.com/websearch/?source=g&amp;hl=ukhttps://www.google.com/webmasters/tools/home#utm_source=uk-wmxmsg&amp;utm_medium=wmxmsg&amp;utm_campaign=bm&amp;authuser=0https://pasport.org.ua/#https://webcache.googleusercontent.com/search?q=cache:rIyf7Ci5ekoJ:https://pasport.org.ua/+&amp;cd=1&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:https://pasport.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwgqMAAhttp://x1.org.ua/#http://webcache.googleusercontent.com/search?q=cache:Ij8aXEsCW7MJ:x1.org.ua/+&amp;cd=2&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:x1.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwgwMAEhttps://translate.google.com.ua/translate?hl=uk&amp;sl=ru&amp;u=http://x1.org.ua/&amp;prev=searchhttps://toursector.org.ua/#https://webcache.googleusercontent.com/search?q=cache:vEHXYDiSr_QJ:https://toursector.org.ua/+&amp;cd=3&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:https://toursector.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwg4MAIhttps://translate.google.com.ua/translate?hl=uk&amp;sl=ru&amp;u=https://toursector.org.ua/&amp;prev=searchhttps://ba.org.ua/#https://webcache.googleusercontent.com/search?q=cache:HCdT2cHu-x8J:https://ba.org.ua/+&amp;cd=4&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:https://ba.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwhAMAMhttps://www.schoollife.org.ua/#https://webcache.googleusercontent.com/search?q=cache:Av9uELBX1mwJ:https://www.schoollife.org.ua/+&amp;cd=5&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:https://www.schoollife.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwhGMAQhttp://nus.org.ua/#http://webcache.googleusercontent.com/search?q=cache:r9nOpET38ToJ:nus.org.ua/+&amp;cd=6&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:nus.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwhMMAUhttp://naiu.org.ua/#http://webcache.googleusercontent.com/search?q=cache:_6CbCiGViucJ:naiu.org.ua/+&amp;cd=7&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:naiu.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwhSMAYhttp://www.novilidery.org.ua/#http://webcache.googleusercontent.com/search?q=cache:zLEzxejvohUJ:www.novilidery.org.ua/+&amp;cd=8&amp;hl=uk&amp;ct=clnk&amp;gl=uahttp://speedtest.org.ua/#http://webcache.googleusercontent.com/search?q=cache:lj4SvjYN6UMJ:speedtest.org.ua/+&amp;cd=9&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=related:speedtest.org.ua/&amp;tbo=1&amp;sa=X&amp;ved=0ahUKEwjn1MzOm4_bAhWkdpoKHYI8BAcQHwhdMAghttps://translate.google.com.ua/translate?hl=uk&amp;sl=ru&amp;u=http://speedtest.org.ua/&amp;prev=searchhttp://tau.org.ua/#http://webcache.googleusercontent.com/search?q=cache:SEKPZZhNUlUJ:tau.org.ua/+&amp;cd=10&amp;hl=uk&amp;ct=clnk&amp;gl=ua/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=10&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=20&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=30&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=40&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=50&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=60&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=70&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=80&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=90&amp;sa=N/search?q=site:.org.ua&amp;ei=1MD-WueaAaTt6QSC-ZA4&amp;start=10&amp;sa=N#https://support.google.com/websearch?p=ws_settings_location&amp;hl=uk//support.google.com/websearch/?p=ws_results_help&amp;hl=uk&amp;fg=1#//www.google.com.ua/intl/uk_ua/policies/privacy/?fg=1//www.google.com.ua/intl/uk_ua/policies/terms/?fg=1https://myaccount.google.com/?utm_source=OGB&amp;utm_medium=apphttps://www.google.com.ua/webhp?tab=wwhttps://maps.google.com.ua/maps?hl=uk&amp;tab=wlhttps://www.youtube.com/?gl=UAhttps://news.google.com.ua/nwshp?hl=uk&amp;tab=wnhttps://mail.google.com/mail/?tab=wmhttps://drive.google.com/?tab=wohttps://www.google.com/calendar?tab=wchttps://plus.google.com/?gpsrc=ogpy0&amp;tab=wXhttps://translate.google.com.ua/?hl=uk&amp;tab=wThttps://photos.google.com/?tab=wq&amp;pageId=nonehttps://www.google.com.ua/intl/uk/options/https://docs.google.com/document/?usp=docs_alchttps://www.blogger.com/?tab=wjhttps://www.google.com/contacts/?hl=uk&amp;tab=wChttps://hangouts.google.com/https://keep.google.com/https://www.google.com.ua/intl/uk/options/https://DDD.org.ua";
		$sorted_links = $this->sortUrl(implode($array_of_links));
		# TODO: debug section
//		$sorted_links = $this->sortUrl($array_of_links);
		var_dump($sorted_links);
		if (count($sorted_links[0])) {
			$this->nextPage();
		} else die('In init section count is 0');
	}

	/**
	 * recursing in pages
	 *
	 */
	function nextPage()
	{
		$has_links = TRUE;
		$start_index = $this->start_index;
		while ($has_links) {
			$sleep_time = rand(80, 122);
			echo "<p>Sleep $sleep_time - " . date('F-d-H:i:s') . "</p>";
			sleep($sleep_time);
			$start_index += 100;
			$url = $this->url . $this->pager_param . $start_index;
			# TODO: debug section
//			$url = $this->url . $this->pager_param . 500;
			echo "<p><i>$url</i></p>";
			$html = $this->getHtml($url);
			$dom = $this->getDom($html);
			$array_of_links = $this->getLinks($dom);
			$sorted_links = $this->sortUrl(implode($array_of_links));
			var_dump($sorted_links);
			$has_links = count($sorted_links[0]);
		}
		die('Nothing do here');
	}
}

// LET'S FUNNY

$start_index = isset($argv[1]) ? $argv[1] : $_GET['start_index'];
//$end_index = isset($argv[2]) ? $argv[2] : $_GET['end_index'];
$LP = new LinkParser($start_index = 0);
$LP->init($LP->url);

