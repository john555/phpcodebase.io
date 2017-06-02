<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <style>
    html, body{
        width: 100%;
        height: 100%;
        
    }
    body{
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }
    #canvas{
        //border: 1px solid #eee;
    }
    </style>
</head>
<body>
    <h1> <?=$name?>.</h1>
    <!--{block body}-->
    <script src="/js/main.js"></script>
</body>
</html>