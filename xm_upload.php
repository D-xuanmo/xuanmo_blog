<?php
  header('Content-type:text/json');
  date_default_timezone_set("PRC");

  // 获取当前文件物理路径
  define("DIR_ROOT", dirname(__FILE__));

  chmod(__FILE__, 0777);

  // 在根目录下增加tmp目录的路径
  $addFilePath = DIR_ROOT . "/images/comments/";

  if(!is_dir($addFilePath)) mkdir($addFilePath, 0700);

  // 上传文件大小
  $fileSize = filesize($_FILES["file"]["tmp_name"]);

  // 截取后缀名
  $fileEx = strtolower(substr(strrchr($_FILES["file"]["name"], "."), 1));

  // 生成随机文件名
  $fileName = date("YmdHis") . substr(rand(), 0, 6) . "." . $fileEx;

  // 移动文件到指定目录
  move_uploaded_file($_FILES["file"]["tmp_name"], $addFilePath . $fileName);

  // 输出结果
  $result = array(
    "name"  => urlencode($fileName),
    "size"  => ceil($fileSize / 1024) . "Kb",
    "type"  => urlencode($_FILES["file"]["type"]),
    "path"  => urlencode($addFilePath . $fileName),
    "code"  => 2
  );

  echo urldecode(json_encode($result));

?>
