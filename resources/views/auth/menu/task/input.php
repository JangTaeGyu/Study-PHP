<?php use App\Core\{ Config, Csrf, Input }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/top.php"; ?>

<h3>업무 등록 및 수정</h3>

<?php if (!$output['result']): ?>
<p class="box-danger mb10"><?=$output['message']?></p>
<?php endif ?>

<div class="mb10">
    <form name="work" method="post" action="<?=$output['action']?>" accept-charset="utf-8">
        <input type="hidden" name="mode" value="<?=elm($input, 'mode')?>" />
        <input type="hidden" name="idx" value="<?=elm($output['info'], 'idx')?>" />
        <input type="hidden" name="before_state" value="<?=elm($output['info'], 'state')?>" />

        <table class="basic_table" style="border-top: 2px solid #3c8dbc;">
            <colgroup>
                <col width="140px"><col width="400px"><col width="140px"><col width="400px"><col width="*">
            </colgroup>
            <tbody>
                <tr>
                    <th class="none">업무대상</th>
                    <td>
                        <select name="target" style="width: 160px;">
                            <?php foreach ($output['code']['target'] as $tkey => $tvalue): ?>
                            <option value="<?=$tkey?>" <?=$tkey === elm($output['info'], 'target') ? 'selected' : ''?>><?=$tvalue?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <th>업무분류</th>
                    <td>
                        <select name="kind" style="width: 160px;">
                            <?php foreach ($output['code']['kind'] as $kkey => $kvalue): ?>
                            <option value="<?=$kkey?>" <?=$kkey === elm($output['info'], 'kind') ? 'selected' : ''?>><?=$kvalue?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td class="taC" rowspan="4">
                        <button type="submit" class="c-success"><i class="fa fa-pencil fa-3x"></i></button><br /><br /><br /><br />
                        <a href="<?=Input::server('HTTP_REFERER')?>" class="c-default"><i class="fa fa-list fa-3x"></i></a>
                    </td>
                </tr>
                <tr>
                    <th>제목</th>
                    <td colspan="3">
                        <input type="checkbox" name="issue" value="Y" <?=elm($output['info'], 'issue') === 'Y' ? 'checked' : ''?> style="width: 32px; height: 32px; background-color: #f2dede;" />
                        <input type="text" name="title" style="width: 865px;" value="<?=elm($output['info'], 'title')?>" />
                    </td>
                </tr>
                <tr>
                    <th>내용</th>
                    <td colspan="3">
                        <textarea name="contents" style="width: 906px; height: 250px; margin: 5px 0;"><?=elm($output['info'], 'contents')?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>요청자</th>
                    <td>
                        <select name="member_idx" style="width: 160px;">
                            <?php foreach ($output['member'] as $mvalue): ?>
                            <option value="<?=$mvalue['idx']?>" <?=$mvalue['idx'] === elm($output['info'], 'member_idx') ? 'selected' : ''?>>[<?=$mvalue['call']?>] <?=$mvalue['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <th class="none">상태</th>
                    <td>
                        <select name="state" style="width: 160px;">
                            <option value="W" <?=elm($output['info'], 'state') === 'W' ? 'selected' : ''?>>대기</option>
                            <option value="S" <?=elm($output['info'], 'state') === 'S' ? 'selected' : ''?>>완료</option>
                            <option value="N" <?=elm($output['info'], 'state') === 'N' ? 'selected' : ''?>>취소</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="<?=Config::get('csrf/token_name')?>" value="<?=Csrf::generate()?>" />
    </form>
</div>

<?php if (array_key_exists('task', $output)): ?>

<h3>동일업무대상</h3>
<?php include_once PATH_ROOT_VIEWS . "/auth/menu/task/list.php"; ?>

<?php endif ?>

<?php include_once PATH_ROOT_VIEWS . "/templates/bottom.php"; ?>