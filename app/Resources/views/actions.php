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
div.action {
    border: solid 1px #7070f0;
    background-color: #e0e0ff;
    padding: 3px;
    margin: 3px;
}
a {
    text-decoration: none;
}
</style>
</head>
<body>
<h1>Actions</h1>
<? foreach ($data as $action): ?>
<div class="action">
    <div class="date"><?=date('Y-m-d H:i:s', strtotime($action->date))?></div>
    <div><?=$action->type?></div>
    <div><a target="content" href="card-<?=$action->data->card->id?>.html"><?=$action->data->card->name?></a></div>
</div>
<? endforeach; ?>
</body>
</html>
