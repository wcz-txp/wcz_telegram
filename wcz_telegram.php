if (txpinterface == 'admin')
{
    add_privs('wcz_telegram_post', '1');

    register_callback('wcz_telegram', 'article', 'edit');

    register_callback('wcz_telegram_ui', 'article_ui', 'status');

}

// Register tags.
Txp::get('\Textpattern\Tag\Registry')
    ->register('wcz_telegram');




function wcz_telegram_ui($event, $step, $data, $rs1) {

    $var = gps('wcz_telegram__this');
    $content  = tag(yesnoRadio('wcz_telegram__this', '0', '', 'wcz_telegram__this'),'p');
    return $data.fieldset($content, 'Send to Telegram', 'wcz_telegram');

}




function wcz_telegram() {


    $article_id = empty($GLOBALS['ID']) ? gps('ID') : $GLOBALS['ID'];
    $Status = empty($GLOBALS['Status']) ? gps('Status') : $GLOBALS['Status'];
    $wcz_telegram__this = empty($GLOBALS['wcz_telegram__this']) ? gps('wcz_telegram__this') : $GLOBALS['wcz_telegram__this'];

    if ($Status == 4 and $wcz_telegram__this == 1)
    {


    if (!empty($article_id)) 
    {

        include_once txpath.'/publish/taghandlers.php';

        $article = safe_row("ID, Posted, LastMod, Title, Excerpt, Keywords, Status", 'textpattern',"ID={$article_id}");
    }


    $article_url = permlinkurl($article);
    $title = $article['Title'];
    $excerpt = $article['Excerpt'];
    $keywords = $article['Keywords'];

// the token of your Telegram bot
    $wcz_telegram_token = '';

// the chatid of your Telegram channel
    $wcz_telegram_chatid = '';

// If you have already a Telegram Instant View template (https://instantview.telegram.org/#publishing-templates), but this is not really public
// you should consider to use this option. Set it to '1' and copy the rhash, so Telegram users will see your article on their mobiles
// inside Telegram rendered with your template.
    $wcz_telegram_iv = '0';
    $wcz_telegram_rhash = '';

// give traffic analytics some stuff "&utm_source=tg&utm_medium=social"
    $wcz_telegram_utm = '0';

// If something doesn't work, switch it to '1' and you'll get the complete request inside of textpattern/tmp/telegram_request.txt
    $wcz_telegram_debug = '0';

    if ($wcz_telegram_iv == '1') 
    {
        $article_url = "https://t.me/iv?url=".$article_url."&rhash=".$wcz_telegram_rhash;
    } 

    if ($wcz_telegram_utm == '1') 
    {
            if( strpos( $article_url, '?' ) !== false) {
                  $article_url = $article_url."&utm_source=tg&utm_medium=social";
            } else {
              $article_url = $article_url."?utm_source=tg&utm_medium=social";
             }
    }

//  escape Markdown formatting character "_"
    $article_url = str_replace("_","\_",$article_url);

//  convert Textile "??" to Markdown formatting character "_"
        $excerpt = str_replace("??","_",$excerpt);

//  prepare keywords
    $keywords = explode(",",$keywords);
    foreach ($keywords as &$keyword) 
    {
        $keyword = preg_replace('/[\s_-]+/', '',mb_convert_case($keyword, MB_CASE_TITLE));
    }

        sort($keywords);

    $keywords = implode(" ",preg_filter('/^/', '#', $keywords));

// 4096 characters is the maximum post length

    $excerptlen = 4080 - strlen($title) - strlen($keywords) - strlen($article_url);

    if (strlen($excerpt) > $excerptlen) 
    {
        $pos=strpos($excerpt, ' ', $excerptlen);
        $excerpt = '"'.substr($excerpt,0,$pos).'" ...';
    }

        $message = preg_replace('/\?\?/', '__',$message);

    $message = "*".$title."*\n\n".$excerpt."\n\n".$keywords."\n\n".$article_url;

    $url = 'https://api.telegram.org/bot'.$wcz_telegram_token.'/sendMessage?chat_id='.$wcz_telegram_chatid.'&parse_mode=Markdown&text=';
    $url .= urlencode($message);


// some debug stuff
    if ($wcz_telegram_debug == "1") 
    {
        file_put_contents(txpath."/tmp/telegram_request.txt","Sent URL: \n".$url."\n\nPrepared message:\n".$message."\n\nExcerpt length:\n".$excerptlen."\n\nCurl output:\n");
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// some debug stuff
    if ($wcz_telegram_debug == "1") 
    {
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $curl_verbose = fopen(txpath.'/tmp/telegram_request.txt', 'a+');
        curl_setopt($ch, CURLOPT_STDERR, $curl_verbose);
    }

    $response = curl_exec($ch);
    curl_close($ch);

    }
}
