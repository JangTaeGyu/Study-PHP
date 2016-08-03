<?php use App\Core\Session; ?>

<?php if (Session::exists('success')): ?>
<p class="box-success mb10"><?=Session::flash('success')?></p>
<?php endif ?>

<?php if (Session::exists('fail')): ?>
<p class="box-danger mb10"><?=Session::flash('fail')?></p>
<?php endif ?>