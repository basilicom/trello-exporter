<html>
<head>
    <title>Trello Export: Cards</title>
<style type="text/css">
body {
        font-family: sans-serif;
}
.open {
    color: green;
}
.closed {
    color: #808080;
}
div.card {
    border: solid 1px #808080;
    background-color: #e0e0e0;
    padding: 3px;
    margin: 3px;
}
a {
    text-decoration: none;
}
</style>
</head>
<body>
<h1>Cards</h1>
    <? $i=0; foreach ($data as $card): $i++; ?>
    <div class="card <?=($card->closed)?'closed':'open'?>">
        <div class="name">#<?=$i?> <a target="content" href="card-<?=$card->id?>.html"><?=$card->name?></a></div>
        <div class="date"><?=date('Y-m-d H:i:s', $card->modified)?></div>
    </div>
    <? endforeach; ?>
</body>
</html>
