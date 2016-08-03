<?php use App\Core\{ Config, Csrf }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/top.php"; ?>

<h3>이슈업무</h3>

<?php if (!$output['result']): ?>
<p class="box-danger mb10"><?=$output['message']?></p>
<?php endif ?>

<?php include_once PATH_ROOT_VIEWS . "/auth/menu/task/list.php"; ?>

<?php include_once PATH_ROOT_VIEWS . "/templates/bottom.php"; ?>