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

            <p class="mb10"><input type="text" name="name" style="width: 270px;" maxlength="12" placeholder="이름" value="<?=elm($input, 'name')?>" /></p>
            <?php if (array_key_exists('name', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['name']?></p>
            <?php endif ?>

            <p class="mb10"><input type="text" name="email" style="width: 270px;" maxlength="50" placeholder="이메일" value="<?=elm($input, 'email')?>" /></p>
            <?php if (array_key_exists('email', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['email']?></p>
            <?php endif ?>

            <button type="submit" class="btn-primary" style="width: 290px; padding: 7px 0;"><i class="fa fa-edit fa-lg mr5"></i>정보수정</button>

        </div>

        <input type="hidden" name="<?=Config::get('csrf/token_name')?>" value="<?=Csrf::generate()?>" />
    </form>

</div>

<?php include_once PATH_ROOT_VIEWS . "/templates/bottom.php"; ?>