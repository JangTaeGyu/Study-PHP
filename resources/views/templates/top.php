<?php use App\Core\{ Input , Session }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/head.php"; ?>

<div class="wrap">

    <div class="panel_info">
        <div class="logo">
            <h1 class="logo"><i class="fa fa-modx fa-4x"></i></h1>
        </div>

        <div class="profile">
            <dl>
                <dt><i class="fa fa-user fa-3x"></i></dt>
                <dd><?=$GLOBALS['session']['name']?></dd>
            </dl>

            <p class="mt25"><a href="/update" title="정보수정"><i class="fa fa-edit fa-lg"></i></a></p>
            <p class="mt25"><a href="/password_change" title="비밀번호변경"><i class="fa fa-lock fa-lg"></i></a></p>
            <p class="mt25"><a href="/logout" title="로그아웃"><i class="fa fa-sign-out fa-lg"></i></a></p>
        </div>

        <div class="menu">
            <ul>
                <li><a href="/main" title="홈"><i class="fa fa-home fa-lg"></i></a></li>
                <li><a href="/menu/task" title="업무"><i class="fa fa-tasks fa-lg"></i></a></li>
                <!-- <li><a href="javascript:void(0)" title="일정"><i class="fa fa-calendar fa-lg"></i></a></li> -->
                <!-- <li><a href="javascript:void(0)" title="통계"><i class="fa fa-tachometer fa-lg"></i></a></li> -->
                <li><a href="/menu/member" title="회원"><i class="fa fa-users fa-lg"></i></a></li>
                <li><a href="/menu/code" title="코드"><i class="fa fa-code fa-lg"></i></a></li>
            </ul>
        </div>
    </div>


    <div class="panel_menu_list">
        <h2><?=$GLOBALS['title']?></h2>

        <?php if ($GLOBALS['title'] === '업무'): ?>
        <ul>
            <li><a href="/menu/task" title="전체업무">전체업무</a> <?=Input::server('REDIRECT_URL') === '/menu/task' ? '<i class="fa fa-circle"></i>' : ''?></li>
            <li><a href="/menu/task/waiting" title="대기업무">대기업무</a> <?=Input::server('REDIRECT_URL') === '/menu/task/waiting' ? '<i class="fa fa-circle"></i>' : ''?></li>
            <li><a href="/menu/task/complete" title="완료업무">완료업무</a> <?=Input::server('REDIRECT_URL') === '/menu/task/complete' ? '<i class="fa fa-circle"></i>' : ''?></li>
            <li><a href="/menu/task/issue" title="이슈업무">이슈업무</a> <?=Input::server('REDIRECT_URL') === '/menu/task/issue' ? '<i class="fa fa-circle"></i>' : ''?></li>
        </ul>
        <?php endif ?>

    </div>

    <div class="panel_contents">

        <?php include_once PATH_ROOT_VIEWS . "/templates/message.php"; ?>
