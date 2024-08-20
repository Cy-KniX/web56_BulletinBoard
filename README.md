# 構築手順

1. 各種ファイルの作成  
nginx、docker-compose.yml、dockerfile、public/index.php 

2. コンテナの起動とテーブルの作成 

コンテナの起動
```
docker compose up 
```
mysqlの実行
```
docker compose exec mysql mysql kyototech 
```
mysqlで以下のテーブルを作成 
```
CREATE TABLE messages ( 

    id INT AUTO_INCREMENT PRIMARY KEY, 

    content TEXT NOT NULL, 

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 

);
```
テーブルが作成されたか確認
```
show databases;
```

