<?php
$ai_content = $_POST['ai_response'];
$date = date('Y-m-d H:i:s');
$save_data = "【保存日時：$date】\n" . $ai_content . "\n\n---------------------------------\n\n";

file_put_contents(__DIR__ . '/data.txt', $save_data, FILE_APPEND);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>保存完了</title>
</head>
<body style="text-align:center; padding-top:50px; font-family:sans-serif;">
    <h2 style="color: #34a853;">保存が完了しました</h2>
    <p>現在のマーケティング戦略をdata.txtに記録しました。</p>
    <a href="../index.html" style="color: #4285f4; text-decoration: none; font-weight: bold;">← アプリに戻る</a>
</body>
</html>