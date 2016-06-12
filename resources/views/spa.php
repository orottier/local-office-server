<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>De Kantoortuin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body>

<div id='app'>
    <statusbar></statusbar>
    <error-bar></error-bar>
    <div class="container">
        <h1>Welcome to the office!</h1>
        <div class="wrapper">
            <router-view transition="expand" transition-mode="out-in">
            </router-view>
        </div>
    </div>
</div>

<script src="/assets/js/vue.js"></script>

<script src="/assets/js/components.js"></script>


<script src="/script.js"></script>
</body>

</html>
