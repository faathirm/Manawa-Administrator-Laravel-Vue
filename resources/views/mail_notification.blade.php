<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Ini Title</title>
    <style>
        body{
            font-family: 'Poppins', sans-serif;
            background-color:#eae9e9;
            width: 350px;
        }
        .card{
            background-color:#ffff;
            margin: 10px;
            padding: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="content">
        {!! $detail['message'] !!}
    </div>
</div>
</body>
</html>
