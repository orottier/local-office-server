<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>De Kantoortuin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/style.css">
</head>

<body>

<div id='app'>
    <h1>Welcome to the office!</h1>

    <!-- route outlet -->
    <router-view transition="expand" transition-mode="out-in">
    </router-view>
</div>

<script src="/vue.js"></script>
<script src="/vue-router.js"></script>
<script src="/vue-resource.js"></script>

<script src="/Component/RequestToken.js"></script>
<script src="/Component/EnterToken.js"></script>
<script src="/Component/Dashboard.js"></script>

<script src="/script.js"></script>
</body>

</html>
