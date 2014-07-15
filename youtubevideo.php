<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>video</title>
    </head>
    <body>
        <?php
            print_r($_POST["xx"]);
          //  $url=('//www.youtube.com/embed/'.$_POST["xx"]);
        ?>
        
        <div align="center"><iframe width="420" height="315" src=<?=$_POST["xx"]?>> frameborder="0" allowfullscreen></iframe>
</div>
    </body>
</html>
