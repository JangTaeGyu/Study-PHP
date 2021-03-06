<?php use App\Core\{ Config, Csrf }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/head.php"; ?>

<div id="panel_login">
    <div class="head">
        <h1><i class="fa fa-modx fa-lg mr5"></i>업무관리 프로그램</h1>
    </div>

    <form name="work" method="post" accept-charset="utf-8">
        <div class="contents">

            <?php include_once PATH_ROOT_VIEWS . "/templates/message.php"; ?>

            <?php if (!$output['result']): ?>
            <p class="box-danger mb10"><?=$output['message']?></p>
            <?php endif ?>

            <p class="mb10"><input type="text" name="id" style="width: 270px;" maxlength="12" placeholder="아이디" value="<?=elm($input, 'id')?>" /></p>
            <?php if (array_key_exists('id', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['id']?></p>
            <?php endif ?>

            <p class="mb10"><input type="password" name="password" style="width: 270px;" maxlength="12" placeholder="비밀번호" /></p>
            <?php if (array_key_exists('password', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['password']?></p>
            <?php endif ?>

            <p class="mb10"><input type="password" name="password_confirm" style="width: 270px;" maxlength="12" placeholder="비밀번호확인" /></p>
            <?php if (array_key_exists('password_confirm', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['password_confirm']?></p>
            <?php endif ?>

            <p class="mb10"><input type="text" name="name" style="width: 270px;" maxlength="12" placeholder="이름" value="<?=elm($input, 'name')?>" /></p>
            <?php if (array_key_exists('name', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['name']?></p>
            <?php endif ?>

            <p class="mb10"><input type="text" name="email" style="width: 270px;" maxlength="100" placeholder="이메일" value="<?=elm($input, 'email')?>" /></p>
            <?php if (array_key_exists('email', $output['errors'])): ?>
            <p class="text-danger mb10"><?=$output['errors']['email']?></p>
            <?php endif ?>

            <button type="submit" class="btn-primary" style="width: 290px; padding: 7px 0;"><i class="fa fa-sign-in fa-lg mr5"></i>회원가입</button>

            <p class="text-info mt10 taR">
                <a href="/">로그인</a>
            </p>
        </div>

        <input type="hidden" name="<?=Config::get('csrf/token_name')?>" value="<?=Csrf::generate()?>" />
    </form>

</div>

<?php include_once PATH_ROOT_VIEWS . "/templates/floor.php"; ?>