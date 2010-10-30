<?php
require_once 'func.php';
SetCfg ('encrypted.str', 'gu217');
SetCfg ( 'debug', TRUE );
SetCfg ( 'date.format', 'Y-m-d H:i:s' );
SetCfg ( 'date.timezone', 'Asia/Shanghai' );
SetCfg ( 'tidy', FALSE );
//目录设置为相对于网站根目录的,考虑放在网站外面
SetCfg ('cache_dir', $_SERVER['DOCUMENT_ROOT'].'');
//相对于网站根目录
SetCfg ('dir.upload.image','upload_files/images/');
//SetCfg ('upload.image.tr.dir','upload_files/previous_leaders/');
//====>如果上传文件不再网站目录下,删除时可以设置专门的get_dir参数设置不同文件的位置

SetCfg ( 'db.free.host', 'localhost' );
SetCfg ( 'db.free.username', 'root' );
SetCfg ( 'db.free.password', '' );
SetCfg ( 'db.free.dbname', 'bjmu_lab' );
SetCfg ( 'db.free.lang', 'utf8' );

?>
