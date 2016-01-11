<?php
require_once __DIR__ . '/../autoload.php';

use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;

// 去我们的portal 后台来获取AK, SK
$accessKey = '4brtJLyWA3bplJKlkn7ZypPbzKcS-58MsF1cnsF4';
$secretKey = 'jt8qbHTrBFAl6HZNt9Mmd2pcx122aJlJ-5mgS-7g';
$auth = new Auth($accessKey, $secretKey);


$bucket = 'rwxf';
$key = '1.png';

// 异步任务的队列， 去后台新建： https://portal.qiniu.com/mps/pipeline
$pipeline = 'abc';

$pfop = new PersistentFop($auth, $bucket, $pipeline);

// 进行zip压缩的url
$url1 = 'http://rwxf.qiniudn.com/php-logo.png';
$url2 = 'http://rwxf.qiniudn.com/1.png';

//压缩后的key
$zipKey = 'test.zip';

$fops = 'mkzip/2/url/' . \Qiniu\base64_urlSafeEncode($url1);
$fops .= '/url/' . \Qiniu\base64_urlSafeEncode($url2);
$fops .= '|saveas/' . \Qiniu\base64_urlSafeEncode("$bucket:$zipKey");

list($id, $err) = $pfop->execute($key, $fops);

echo "\n====> pfop mkzip result: \n";
if ($err != null) {
    var_dump($err);
} else {
    echo "PersistentFop Id: $id\n";
    
    $res = "http://api.qiniu.com/status/get/prefop?id=$id";
    echo "Processing result: $res";
}
