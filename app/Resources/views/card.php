<html>
<head>
    <title>Trello Export: Card</title>
<style type="text/css">
body {
        font-family: sans-serif;
}
li.card div.name {
    font-family: sans-serif;
    font-size: 14px;
    font-weight: bold;
}
li.card {
    margin-top: 14px;
}
.open {
    color: green;
}
.closed {
    color: #808080;
}
.date {
    color: navy;
}
.attachments {
    margin-top: 16px;
}

</style>
</head>
<body>
<h1 class="<?=($data->closed)?'closed':'open'?>"><?=$data->name?></h1>
<div class="card">
    <div class="description"><?=nl2br($data->desc)?></div>
    <div>
        <div>Changelog:</div>
        <ul>
            <? foreach ($data->actions as $action): ?>
            <li class="action">
                <div>
                    <span class="date"><?=date('Y-m-d H:i:s', strtotime($action->date))?></span> <span class="type"><?=$action->type?></span>
                </div>
                <? if ($action->type == 'commentCard'):?>
                <div class="comment">
                    <?=$action->data->text?>
                </div>
                <? endif; ?>
            </li>
            <? endforeach; ?>
        </ul>
    </div>
    <div class="attachments">
    <? foreach ($data->actions as $action): if ($action->type == 'addAttachmentToCard'): ?>
        <div class="action attachment">
            <code><?=$action->data->attachment->name?>:</code><br />
            <img class="action" src="<?=$action->data->attachment->url?>" title="<?=$action->data->attachment->name?>"/>
        </div>
    <? endif; endforeach; ?>
    </div>
</div>
</body>
</html>
