<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>DEMO</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body id="wrapper">
    <?php include 'header.php'; ?>

    <div id="contents">
        <form name="form" action="http://127.0.0.1/insertEvent.php" method="POST" accept-charset="UTF-8" align="center">
            <div class="detail_box clearfix">
                <div class="link_box">
                    <h3>新增資料</h3>
                    <ul>
                        <li>
                            姓名:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type="varchar(50)" name="name"
                                size="30" />&nbsp <br /><br />
                        </li>
                        <li>
                            電話:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type="varchar(50)" name="phone"
                                size="30" />&nbsp <br /><br />
                        </li>

                        <li>
                            電子信箱:&nbsp&nbsp <input type="varchar(50)" name="mail" size="30" />&nbsp <br /><br />
                        </li>

                        <li>
                            意見回饋:&nbsp&nbsp
                            <textarea name="comment" rows="5" cols="33"></textarea>

                        </li>
                        <input type="reset" value="清除表單">
                        <input type="submit" align="right" value="送出">
                        </br>
                    </ul>
                </div>
            </div>
        </form>
</body>

</html>