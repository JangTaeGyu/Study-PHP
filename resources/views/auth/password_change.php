<?php use App\Core\{ Config, Csrf }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/top.php"; ?>

<div id="panel_login">
    <div class="head">
        <h1><i class="fa fa-modx fa-lg mr5"></i>업무관리 프로그램</h1>
    </div>

    <form name="work" method="post" accept-charset="utf-8">
        <div class="contents">

            <?php if (!$output['result']): ?>
            <p class="box-danger mb10"><?=$output['message']?></p>
            <?php endif ?>

            <p class="mb10"><input type="password" name="password_current" style="width: 270px;" maxlength="12" placeholder="현재비밀번호" /></p>
            <?php if (array_key_exists('password_current', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['password_current']?></p>
            <?php endif ?>

            <p class="mb10"><input type="password" name="password" style="width: 270px;" maxlength="12" placeholder="비밀번호" /></p>
            <?php if (array_key_exists('password', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['password']?></p>
            <?php endif ?>

            <p class="mb10"><input type="password" name="password_confirm" style="width: 270px;" maxlength="12" placeholder="비밀번호확인" /></p>
            <?php if (array_key_exists('password_confirm', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['password_confirm']?></p>
            <?php endif ?>

            <button type="submit" class="btn-primary" style="width: 290px; padding: 7px 0;"><i class="fa fa-edit fa-lg mr5"></i>비밀번호변경</button>

        </div>

        <input type="hidden" name="<?=Config::get('csrf/token_name')?>" value="<?=Csrf::generate()?>" />
    </form>

</div>

<?php include_once PATH_ROOT_VIEWS . "/templates/bottom.php"; ?>