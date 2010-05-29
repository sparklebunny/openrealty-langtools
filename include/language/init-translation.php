

<?php
/* 
 * The goal of this tools is to give someone a starting point for the large amount of
 * translation that needs to be done in order to translate Open Reatly into a different languge.
 * It loads then english language template and uses google translator ajax api to
 * translate and create a new language file.
 * Usage:
 * 0. put this file in the /include/language directory.
 * 1. set your original language and translation language.  Us the codes as documented here:
 *     http://code.google.com/apis/ajaxlanguage/documentation/reference.html#LangNameArray
 * 2. Point Your browser to http://localhost/include/language/init-translation.php to run the
 *     translation of the original.inc.php file.  This will take a while.  Save the file in the
 *     appropriate folder.  /include/{lang}/versions/orginal.inc.php
 * 3. Comment out the original.inc.php include and uncomment the 2_5_6.inc.php include.
 *     Reload the page and save the resulting file in /include/{lang}/versions/2_5_6.inc.php.
 * 4. Repeate with the 2_5_8.inc.php version
 * 5. Have someone who knows the language review and edit the files.
 */

    header("Content-Type: text/utf8");
    $original_language = "en";
    $tranlation_language = "ar";
    //takes a few minutes
    require_once(dirname(__FILE__) . '/' .$original_language. '/versions/original.inc.php');
    //takes a couple of minutes
//    require_once(dirname(__FILE__) . '/' .$original_language. '/versions/2_5_6.inc.php');
    //takes a minute
//    require_once(dirname(__FILE__) . '/' .$original_language. '/versions/2_5_8.inc.php');


 function do_post_request($url, $data = null, $optional_headers = null)
    {
      $params = array('http' => array('method' => 'GET'));
      if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
      }
      $ctx = stream_context_create($params);
      $fp = @fopen($url, 'rb', false, $ctx);
      if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
      }
      $response = @stream_get_contents($fp);
      if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
      }
      return $response;
    }

    function translate($item, $from, $to) {

        $url = str_replace(" ", "%20", "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=".$item."&langpair=".$from."%7C".$to);

        $result = do_post_request($url);

        // now, process the JSON string
        return json_decode($result)->{'responseData'}->{'translatedText'};
        // now have some fun with the results...
    }

    foreach ($lang as $key => $val) {
        $result = translate($val, $original_language, $tranlation_language);
        echo "\$lang['".$key."'] = \"".$result."\";// ".$val."\n" ;
        flush();
    }

?>

