<html>
  <head>
    <meta charset="UTF-8">
    <title>mission_5-1.php</title>
  </head>

  <body>

    <?php
    //MySQL部分
     //データベースへの接続
	

         $dsn='データベース名';      // ・データベース名：
	 $user = 'ユーザー名';                                // ・ユーザー名：
	 $password = 'パスワード';                           // ・パスワード：
	 $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                                                      //データベース操作で発生したエラーの表示

    //データベース内にテーブルを作成する
         $sql = "CREATE TABLE IF NOT EXISTS tbtest"   //もしテーブルがないならばテーブルを作成する
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"        //値の指定がない場合MySQLが自動で値を割り当てる
	. "name varchar(32),"                         //varchar（〇）…〇文字以内の文字配列
	. "comment text"                              //text…制限のない文字配列
	.");";
	$stmt = $pdo->query($sql);

    //テーブルが作成できたかの確認
        $sql ='SHOW TABLES';
	$result = $pdo -> query($sql);                
           //$pdo(データベース）から$sql(テーブル)を取り出してqueryに入れる
           //(queryはデータベースに対して発行する役割)(->…アロー演算子で左の集合から右を取り出すイメージ)
	foreach ($result as $row){
		
		echo '<br>';
	}


   //テーブルの中身が意図した内容になっているかの確認
        $sql ='SHOW CREATE TABLE tbtest';
	$result = $pdo -> query($sql);
	 

    //編集欄に書き込みがあった場合
      if(isset($_POST["hensyu"])&&$_POST["hensyu"]!==""){          //編集欄の値の受け取り
       $hensyu=$_POST["hensyu"];
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> execute();

        $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	   foreach ($results as $row){               //$rowの中にはテーブルのカラム名が入る
		if($row['id']==$hensyu){
		$name=$row['name'];
		$comment=$row['comment'];
	        }
           }
        }
    ?>
     
    <form action="mission_5-1.php" method="post">  
        <input type="text" name="name" value="<?php if(!empty($name)){echo $name;}?>" placeholder="名前"><br> <!--名前の枠-->
        <input type="text" name="comment" value="<?php if(!empty($comment)){echo $comment;}?>" placeholder="コメント">                             <!--コメントの枠-->
        <input type="hidden" name="henban" value="<?php if(!empty($hensyu)){echo $hensyu;}?>"><br>  <!--編集対象番号が表示される枠-->
        <input type="password" name="sinkipass" maxlength="20" placeholder="パスワード">                          <!--パスワードの枠-->
        <input type="submit" name="botan" value="送信"><br><br>                               <!--新規投稿送信ボタン-->
        
         <input type="text" name="delete" placeholder="削除対象番号"><br>                          <!--削除の枠-->
        <input type="password" name="deletepass" maxlength="20" placeholder="パスワード">                        <!--取り消しのパスワード-->
         <input type="submit" name="hen" value="削除"><br><br>                                         <!--削除送信ボタン-->
        
        <input type="text" name="hensyu" placeholder="編集対象番号"><br>                          <!--編集の枠-->
        <input type="password" name="editpass" maxlength="20" placeholder="パスワード">                          <!--編集のパスワード-->
        <input type="submit" name="hen" value="編集">                                         <!--編集送信ボタン-->
    </form>
        
   <?php
//新規投稿パート
   //作成したテーブルにinsertを使ってデータ入力
    if(isset($_POST["name"])&&$_POST["name"]!==""&&isset($_POST["comment"])&&$_POST["comment"]!==""&&$_POST["henban"]==""){        
        
     if(isset($_POST["sinkipass"])&&$_POST["sinkipass"]=="pass"){
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$name =$_POST["name"];
	$comment=$_POST["comment"]; //好きな名前、好きな言葉は自分で決めること
	$sql -> execute();
       

   //query()の場合、ユーザからの入力を利用しない
   //prepare()の場合、ユーザからの入力を利用する	

   //1.ユーザからの入力を受け付ける準備（prepareメソッド）
   //2.ユーザからの入力をSQL文に含める（bindParamメソッド）
   //3.SQL文の実行（executeメソッド）

 
   //入力したデータをselectによって表示する
      
        $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){               //$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
	}
      }else{
        echo "Error:name or comment or password is empty";
}
}

//編集パート
     if(isset($_POST["hensyu"])&&$_POST["hensyu"]!==""){          //編集欄の値の受け取り
       $hensyu=$_POST["hensyu"];
     
     if(isset($_POST["editpass"])&&$_POST["editpass"]=="pass"){

        $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){               //$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
        }
	}else{
     echo "Error:edit number or edit password is empty";
      }
      }

      if(isset($_POST["name"])&&$_POST["name"]!==""&&isset($_POST["comment"])&&$_POST["comment"]!==""&&$_POST["henban"]!==""){
        $hensyu=$_POST["henban"];

      if(isset($_POST["sinkipass"])&&$_POST["sinkipass"]=="pass"){
        $id =$hensyu; //変更する投稿番号
	$name=$_POST["name"];
        $comment=$_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
	
        $sql = 'update tbtest set name=:name,comment=:comment where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

    //入力したデータをselectによって表示する
      
        $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){               //$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';

}
 }else{
        echo "Error:edit name or edit comment or edit password is empty";
       }
      }


//削除パート
   if(isset($_POST["delete"])&&$_POST["delete"]!==""){          //削除欄の数値の受け取り
       $delete=$_POST["delete"];

   if(isset($_POST["deletepass"])&&$_POST["deletepass"]=="pass"){
        $id = $delete;
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

        $sql = 'SELECT * FROM tbtest';            //データの表示
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){               //$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
       
    }
    }else{
       echo "<br>"."Error:delete number or delete password is empty";
    }
    }



 
?>
  </body>
</html>