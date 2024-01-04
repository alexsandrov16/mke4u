<html>

<head>
    <title>Whoops!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: #636363;
            background-color: #e7e7e7af;
        }

        h1,h2{
            font-weight: 400;
        }
        h1 {
            font-size: 3em;
            /*text-transform: uppercase;*/
        }
        h2{
            font-size: 2em;
        }
    </style>
</head>

<body>
    <center>
        <h1>Error <?= $code ?></h1>
        <h2><?= $message ?></h2>
    </center>
</body>

</html>