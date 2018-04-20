<?php
 /**
  * get data from existing site
  *
  * sending Curl reqvest to specific site and getting all content
  * 
  * @param  string $url     site url 
  * @param  string $referer where i came from
  * @param  bool $get_header  getting page header
  * @param  bool $set_ssl verify the certificate's name against host
  * @param  bool $set_nobody do i need body of html
  * @return string $html texting data
  */
 function curl_get(
  $url, 
  $referer='https://www.google.com', 
  $user_agent = 'Mozilla/5.0 (X11; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0', 
  $get_header = false,
  $set_nobody = false,
  $set_ssl = false)
 {
  $ch = curl_init();
  echo $ch;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, $get_header);
  curl_setopt($ch, CURLOPT_NOBODY, $set_nobody);
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
  curl_setopt($ch, CURLOPT_REFERER, $referer);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $set_ssl);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $set_ssl);
  $html = curl_exec($ch);
  curl_close($ch);
  return $html;
}
