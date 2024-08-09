<?php
$servername = "mysql";
$username = "root";
$password = "";
$dbname = "kyototech";

// MySQLに接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続をチェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 投稿を削除する処理
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $sql = "DELETE FROM messages WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // 削除後にリロードしてリストを更新
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// POSTリクエストかチェックして投稿を処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SQLインジェクション対策のためにエスケープ
    $content = $conn->real_escape_string($_POST['content']);

    // XSS対策のためにHTMLエンティティをエスケープ
    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    // データベースに挿入
    $sql = "INSERT INTO messages (content) VALUES ('$content')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // ページをリロードしてフォームの再送信を防ぐ
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// 投稿を取得
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin Board</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Bulletin Board</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button class="btn btn-primary" id="openModal">投稿</button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-3">投稿一覧</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text"><?php echo $row['content']; ?></p>
                        <small class="text-muted"><?php echo $row['created_at']; ?></small>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm float-right">削除</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>投稿がまだありません。</p>
        <?php endif; ?>
    </div>

    <div class="modal" id="postModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-header">
                        <h5 class="modal-title">新しい投稿</h5>
                        <button type="button" class="close" id="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="closeModalFooter">キャンセル</button>
                        <button type="submit" class="btn btn-primary">投稿</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/js/modal.js"></script>
</body>
</html>
