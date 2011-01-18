<?php
define('IMAGE_DOMAIN', 'http://img.gongye360.com/gongye360');
define('FILE_DOMAIN', 'http://file.gongye360.com/gongye360');
define('MEDIA_DOMAIN', 'http://media.gongye360.com/gongye360');
require_once INC_DIR."/class/phpQuery/phpQuery.php";
function phpQueryFile($file, $charSet = 'utf-8', $retryTimes=5, $retryWaitTime = 1)
{
    $i = 1;
    while ($i++ < $retryTimes) {
        phpQuery::newDocumentFile($file, "text/html;charset={$charSet}");
        if (phpQuery::isValid()) {
            return true;
        }
        sleep($retryWaitTime);
    }

    return false;
}

function getRemoteContent($url, $retryTimes = 5, $timeOut = 20)
{
    $urlInfo = parse_url($url);
    $port = empty($urlInfo['port']) ? 80 : $urlInfo['port'];
    $i=1;
    while ($i++ < $retryTimes){
	    $fp = fsockopen($urlInfo['host'], $port, $errno, $errstr, $timeOut);
	    if (!$fp) {
	        continue;
	    }   
	    stream_set_timeout($fp, $timeOut);
	    $page = $urlInfo['path'] . (empty($urlInfo['query']) ? '' : '?' . $urlInfo['query']);
	    $request = "GET {$page} HTTP/1.1\r\n";
	    $request .= "Host:{$urlInfo['host']}\r\n";
	    $request .= "Connection: Close\r\n\r\n";
	    fwrite($fp, $request);
	    $content = stream_get_contents($fp);
	    if ($content === false) {
	    	fclose($fp);
	    	continue;
	    } else {
	    	fclose($fp);
    		return $content;
	    }
    }
    
    return false;
}

function getFullUrl($url, $currentUrl)
{
    if (preg_match('/^http(s)?:\/\//i', $url)) {
        return $url;
    }
    $urlInfo = parse_url($currentUrl);
    $baseUrl = $urlInfo['scheme'] . '://' . $urlInfo['host'] .
            (empty($urlInfo['port']) ? '' : ":{$urlInfo['port']}");
    if (preg_match('/^\//', $url)) {
            return $baseUrl . $url; 
    }

    return $baseUrl . rtrim($urlInfo['path'], '/') . '/' . $url;
}

function saveImageFile($url, $thumbs)
{
    $date = date('Ymd');
    $imagePath = '/data/fs/img/gongye360/' . $date;
    if (!file_exists($imagePath)){
        if (!mkdir($imagePath, 0777)) {
            return false;
        }
        system("chown -R apache.apache {$imagePath}");
    }
    $urlInfo = parse_url($url);
    $pathInfo = pathinfo($urlInfo['path']);
    $ext = empty($pathInfo['extension']) ? '' : $pathInfo['extension'];
    $file = generateUniqFile($imagePath, $ext);
    $saveFile = $imagePath . '/' . $file;
    $ret = downloadFile($url, $saveFile);
    if (!$ret)
        return false;
    $ret = array();
    $ret['url'] = IMAGE_DOMAIN . '/'  . $file;
    $ret['thumbs'] = array();
    foreach ($thumbs as $key => $thumb) {
        $thumbfile = generateUniqFile($imagePath, $ext);
        if (create_thumb($saveFile, $imagePath . '/' . $thumbfile,
             $thumb['width'], $thumb['height'])) {
                $ret['thumbs'][$key] = IMAGE_DOMAIN . '/' . $date . '/' . $thumbfile;
        } else {
                $ret['thumbs'][$key] = '';
        }
    }

    return $ret;
}

function saveFile($url)
{
    $date = date('Ymd');
    $filePath = '/data/fs/file/gongye360/' . $date;
    if (!file_exists($filePath)){
        if (!mkdir($filePath, 0777)) {
            return false;
        }
        system("chown -R apache.apache {$filePath}");
    }
    $urlInfo = parse_url($url);
    $pathInfo = pathinfo($urlInfo['path']);
    $ext = empty($pathInfo['extension']) ? '' : $pathInfo['extension'];
    $file = generateUniqFile($filePath, $ext);
    $saveFile = $filePath . '/' . $file;
    $ret = downloadFile($url, $saveFile);
    if (!$ret)
        return false;
    return array(
        'url' => FILE_DOMAIN . '/'  . $date . '/' . $file,
        'size' => filesize($saveFile)
        );
}

function saveVideo($url)
{
    $date = date('Ymd');
    $filePath = '/data/fs/media/gongye360/' . $date;
    if (!file_exists($filePath)){
        if (!mkdir($filePath, 0777)) {
            return false;
        }
        system("chown -R apache.apache {$filePath}");
    }
    $urlInfo = parse_url($url);
    $pathInfo = pathinfo($urlInfo['path']);
    $ext = empty($pathInfo['extension']) ? '' : $pathInfo['extension'];
    $file = generateUniqFile($filePath, $ext);
    $saveFile = $filePath . '/' . $file;
    $ret = downloadFile($url, $saveFile);
    if (!$ret)
        return false;
    $imagePath = '/data/fs/img/gongye360/' . $date;
    if (!file_exists($imagePath)){
        if (!mkdir($imagePath, 0777)) {
            return false;
        }
        system("chown -R apache.apache {$imagePath}");
    }
    $thumbnail = generateUniqFile($imagePath, 'jpg');
    $rs = createVideoThumbnail($saveFile, $imagePath . '/' . $thumbnail);
    return array(
        'source_file' => $saveFile,
        'url' => MEDIA_DOMAIN . '/'  . $date . '/' . $file,
        'thumbnail' => IMAGE_DOMAIN . '/' . $date . '/' . $thumbnail
        );
}

function downloadFile($url, $saveFile)
{
    system("wget -t3 -T15 -q -N '{$url}' -O {$saveFile}", $ret);
    if ($ret != 0) {
        return false;
    }

    return true;
}

function generateUniqFile($path, $ext)
{
    do {
        $file = generateFilename($ext);
    } while (file_exists($path . '/' . $file));

    return $file;
}

function generateFilename($ext)
{
   return date('YmdHis') . rand(100,999) . ".{$ext}";
}

function create_thumb($orgfile,$dstfile, $width,$height)
{
    $waterimg = '/data/share/apps/1.9/common/image/vogel_water.png';
    $waterimg_small = '/data/share/apps/1.9/common/image/vogel_water.png';
    $im = null;
    $imginfo = getimagesize($orgfile);
    switch ($imginfo[2]) {
    case 1:
        $ext = 'gif';
        $im = imagecreatefromgif($orgfile);
        break;
    case 2:
        $ext = 'jpg';
        $im = imagecreatefromjpeg($orgfile);
        break;
    case 3:
        $ext = 'png';
        $im = imagecreatefrompng($orgfile);
        break;
    default:
        return false;
    }
    if(empty($im))
    {
        return false;
    }
    $org_width = imagesx($im);
    $org_height = imagesy($im);
    $thumb_file = $dstfile;

    if($org_width<=$width && $org_height <=$height)
    {
        $dest_width = $org_width;
        $dest_height = $org_height;
    }
    else if(($org_width/$width)>=($org_height/$height))
    {
        $dest_width = $width;
        $dest_height = $org_height*$width/$org_width;
    }
    else
    {
        $dest_width = $org_width*$height/$org_height;
        $dest_height = $height;
    }
    if ($ext == 'gif')
    {
        $thumb_im = @imagecreate($dest_width,$dest_height);
    }
    else
    {
        $thumb_im = @imagecreatetruecolor($dest_width,$dest_height);
    }
    if(!$thumb_im)
    {
        return false;
    }
    if (!imagecopyresampled($thumb_im, $im, 0, 0, 0, 0, $dest_width, $dest_height, $org_width, $org_height))
    {
        return false;
    }
    if($dest_width >=200 && $dest_height>=80)
    {
        if($dest_width >=400 && $dest_height>=160)
        {
            $wm = imagecreatefrompng($waterimg);
        }
        else
        {
            $wm = imagecreatefrompng($waterimg_small);
        }
        if(!empty($wm))
        {
            $water_width = imagesx($wm);
            $water_height = imagesy($wm);
            imagecopy($thumb_im,$wm,$dest_width-$water_width-25,$dest_height-$water_height-15,0,0,$water_width,$water_height);
            imagecopymerge_alpha($thumb_im,$wm,$dest_width-$water_width-25,$dest_height-$water_height-15,0,0,$water_width,$water_height);
        }
    }
    if($ext == 'jpg' || $ext == 'jpeg')
    {
        $ret = imagejpeg($thumb_im,$thumb_file);
    }
    else if($ext == 'gif')
    {
        $ret = imagegif($thumb_im,$thumb_file);
    }
    else if($ext == 'png')
    {
        $ret = imagepng($thumb_im,$thumb_file);
    }
    else
    {
        return false;
    }
    if (!$ret)
    {
        return false;
    }
    return true;
}

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct=0){
    $opacity=$pct;
    // getting the watermark width
    $w = imagesx($src_im);
    // getting the watermark height
    $h = imagesy($src_im);

    // creating a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);
    // copying that section of the background to the cut
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    // inverting the opacity
    $opacity = 100 - $opacity;
    // placing the watermark now
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
}

function addCorpUser($userInfo)
{
    global $_db;
    $detail = $userInfo['corpDetail'];
    $industrys = $userInfo['user_industry'];
    $topics = $userInfo['user_topic'];
    unset($userInfo['corpDetail'], $userInfo['user_industry'], $userInfo['user_topic']);
    $userid = $_db->insert('GlobalDB19.T_GTUser', $userInfo);
    if ($userid <= 0)
        return false;
    $_db->insert('CorpDB19.T_CorpInfo', array(
        'userid' => $userid,
        'corpDetail' => $detail));
    $userIndustrys = array();
    foreach ($industrys as $i) {
        $userIndustrys[] = array('user_id' => $userid,
            'industry_id' => $i,
            'ind_cls_path' => '3_' . $i
        );
    }
    $userTopics = array();
    foreach ($topics as $i) {
        $userTopics[] = array('user_id' => $userid,
            'topic_id' => $i
        );
    }
    $_db->batchInsert('GlobalDB19.user_industry', $userIndustrys);
    $_db->batchInsert('GlobalDB19.T_UserTopic', $userTopics);
    echo "add user: {$userid}\n";
    return $userid;
}

function userExists($username)
{
    global $_db;
    return $_db->fetchOne("select userid from T_GTUser where username='{$username}'  and del_flag=0");
}

function getCorpUserInfo($username , $site)
{
    global $_db;
    $info = $_db->fetchRow("select userid,corp_name,site_url from T_GTUser where username='{$username}' and user_type_id=4 and site_url='{$site}' and del_flag=0");
    if (empty($info))
        return false;
    $info['user_industry'] = $_db->fetchCol("select ind_cls_path from user_industry where user_id={$info['userid']}");
    return $info;
}

//根据catepath获取行业ID
function getIndustryIdFromPath($path)
{
    $pos = strpos($path, '_');
    if ($pos === false)
            return 0;
    return (int)substr($path, $pos + 1);  
}

//根据catepath获取专题ID
function getTopicIdFromPath($path)
{
    $cls = explode('_', $path); 
    return (empty($cls[2]) ? 0 : (int)$cls[2]);
}

//根据catepath获取产品分类ID(最后一级)
function getProdClsIdFromPath($path)
{
    $cls = explode('_', $path); 
    return array_pop($cls);
}

function createPassword($username)
{
    $len = strlen($username);
    if ($len > 20)
        $password = substr($username, 0, 20);
    elseif ($len < 6)
        $password = str_pad($username, 6, $username[$len - 1]);
    else
        $password = $username;

    //return $password;
    return md5($password);
}
function formatContent($content, $convertImage = false, $imgWidth=450,$imgHeight=450, $url = '')
{
    $content = strip_tags($content, '<p><br><img><div><table><tr><td><ul><li><tbody><dl><dt><dd><strong>');
    if ($convertImage) {
        phpQuery::newDocumentHTML($content);
        $images = pq('img');
        $thumbs = array(
            array(
                'width' => $imgWidth,
                'height' => $imgHeight
            )
        );
        foreach ($images as $img) {
            $src =  pq($img)->attr('src');
            if (!empty($src)) {
                $fullUrl = getFullUrl($src, $url);
                $ret = saveImageFile($fullUrl, $thumbs);
                if ($ret) {
                    $imgurl = $ret['thumbs'][0];
                } else {
                    $imgurl = '';
                }
                $content = str_replace($src, $imgurl, $content);
            }
        }
    }

    return $content;
}

function getUserNameFromSite($site)
{
    if (preg_match('/https?:\/\/(?:www\.)?([^\.]+)/i', $site, $match)) {
        return $match[1];
    }
    return;
}

function isCorpSite($site)
{
    return preg_match('/^https?:\/\/www\.[a-z0-9-]+\./i', $site);
}

function addNews($info)
{
    global $_db;
    echo "add news: {$info['user_id']}\n";
    return $_db->insert('GlobalDB19.T_NewsCont', $info);
}

function addTec($info)
{
    global $_db;
    echo "add tec: {$info['uid']}\n";
    return $_db->insert('GlobalDB19.T_Record_Info', $info);
}

function addDownload($info)
{
    global $_db;
    echo "add download: {$info['uid']}\n";
    return $_db->insert('GlobalDB19.T_Record_Papers', $info);
}

function addProd($info)
{
    global $_db;
    $images = $info['images'];
    unset($info['images']);
    $prodid = $_db->insert('CorpDB19.T_CorpProds', $info);
    if (!$prodid > 0)
        return false;
    foreach ($images as &$img) {
        if (empty($img['image_url']) || empty($img['thumb_url'])) {
            unset($img);
            continue;
        }
        $img['prod_id'] = $prodid;
    }
    if (!empty($images)) {
        $_db->batchInsert('CorpDB19.T_ProdImage', $images);
    }
    echo "add prod: {$info['userid']}\n";
    return $prodid;
}

function addVideo($info)
{
    global $_db;
    return $_db->insert('GlobalDB19.video_info', $info);
}
function createVideoThumbnail($source, $destination, $width = 540, $height = 180, $quality=85)
{   
    global $CONFIG;
    $exec_thumb = "/bin/nice -19 /usr/local/bin/ffmpeg -i \"".$source."\" -y -ss 1 -t 0.01 -f mjpeg - | \
        /bin/nice -19 /usr/bin/convert - -resize '{$width}x{$height}' -gravity center \
        -crop $width"."x"."$height+0+0 +repage -format jpg -quality $quality \"jpg:$destination\"";
    exec("echo $exec_thumb >> /tmp/convert.log");
    if (exec($exec_thumb)) {
        return true;    
    } else {
        return false;
    }   
}   

?>
