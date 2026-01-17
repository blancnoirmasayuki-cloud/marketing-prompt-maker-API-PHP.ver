<?php
// 1. URLからUID（本人確認用）を受け取る
$uid = isset($_GET['uid']) ? $_GET['uid'] : '';

if ($uid === '') {
    exit('エラー：ログイン情報が確認できません。index.phpからアクセスしてください。');
}

// 2. DB接続
try {
    $db_name = 'marketing_prompt';
    $db_id   = 'root';
    $db_pw   = '';
    $db_host = 'localhost';
    $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
} catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
}

// 3. SQL作成（自分(UID)のデータだけを取得）
$stmt = $pdo->prepare("SELECT * FROM marketing_prompt_table WHERE uniqueid = :uid ORDER BY date DESC");
$stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
$status = $stmt->execute();

// 4. 表示用データの作成
$view = '';
if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $view .= '<div style="border-bottom: 1px solid #eee; padding: 15px; display: flex; justify-content: space-between; align-items: center;">';
    $view .= '  <div>';
    $view .= '    <span style="color: #666; font-size: 0.8em;">' . $result['date'] . '</span><br>';
    
    // ★ここを修正：詳細画面(detail.php)へのリンクにし、idを渡す
    $view .= '    <a href="detail.php?id=' . $result['id'] . '" style="text-decoration:none; color:#333; font-weight:bold;">';
    $view .= '      ' . htmlspecialchars($result['title'], ENT_QUOTES) . ' ＞';
    $view .= '    </a>';
    
    $view .= '  </div>';
    $view .= '  <a href="delete.php?id=' . $result['id'] . '&uid=' . $result['uniqueid'] . '" style="color:red; text-decoration:none;" onclick="return confirm(\'本当に削除しますか？\')">🗑 削除</a>';
    $view .= '</div>';
}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>マイプロンプト履歴</title>
</head>
<body style="font-family: sans-serif; background: #fdfdfd; padding: 20px;">
    <header style="margin-bottom: 30px;">
        <a href="index.php" style="text-decoration:none; color:#4285f4;">← プロンプト作成に戻る</a>
        <h1>📂 自分の保存履歴</h1>
    </header>

    <main style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 10px;">
        <?= $view === '' ? '<p style="padding: 20px;">保存された履歴はまだありません。</p>' : $view ?>
    </main>
</body>
</html>