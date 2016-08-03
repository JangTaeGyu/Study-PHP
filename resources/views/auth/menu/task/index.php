<?php use App\Core\{ Config, Csrf }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/top.php"; ?>

<h3>전체업무</h3>

<?php if (!$output['result']): ?>
<p class="box-danger mb10"><?=$output['message']?></p>
<?php endif ?>

<div class="mb10">
    <form name="work" method="get" action="/menu/task" accept-charset="utf-8">
        <table class="basic_table" style="border-top: 2px solid #3c8dbc;">
            <colgroup>
                <col width="140px"><col width="400px"><col width="140px"><col width="400px"><col width="*">
            </colgroup>
            <tbody>
                <tr>
                    <th class="none">업무대상</th>
                    <td>
                        <select name="target" style="width: 160px;">
                            <option value="">전체</option>
                            <?php foreach ($output['code']['target'] as $tkey => $tvalue): ?>
                            <option value="<?=$tkey?>" <?=$tkey === elm($input, 'target') ? 'selected' : ''?>><?=$tvalue?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <th>업무분류</th>
                    <td>
                        <select name="kind" style="width: 160px;">
                            <option value="">전체</option>
                            <?php foreach ($output['code']['kind'] as $kkey => $kvalue): ?>
                            <option value="<?=$kkey?>" <?=$kkey === elm($input, 'kind') ? 'selected' : ''?>><?=$kvalue?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td class="taC" rowspan="3"><button type="submit" class="c-primary"><i class="fa fa-search fa-3x"></i></button></td>
                </tr>
                <tr>
                    <th class="none">제목</th>
                    <td><input type="text" name="title" style="width: 371px;" maxlength="100" value="<?=elm($input, 'title')?>" /></td>
                    <th>요청자</th>
                    <td>
                        <select name="member_idx" style="width: 160px;">
                            <option value="">전체</option>
                            <?php foreach ($output['member'] as $mvalue): ?>
                            <option value="<?=$mvalue['idx']?>" <?=$mvalue['idx'] === elm($input, 'member_idx') ? 'selected' : ''?>>[<?=$mvalue['call']?>] <?=$mvalue['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </td>

                <tr>
                    <th class="none">상태</th>
                    <td>
                        <select name="state" style="width: 160px;">
                            <option value="">전체</option>
                            <option value="W" <?=elm($input, 'state') === 'W' ? 'selected' : ''?>>대기</option>
                            <option value="S" <?=elm($input, 'state') === 'S' ? 'selected' : ''?>>완료</option>
                            <option value="N" <?=elm($input, 'state') === 'N' ? 'selected' : ''?>>취소</option>
                        </select>
                    </td>
                    <th>등록일자</th>
                    <td>
                        <input type="text" name="sdate" style="width: 63px;" maxlength="10" value="<?=elm($input, 'sdate')?>" />
                        <span style="padding: 0 5px">~</span>
                        <input type="text" name="edate" style="width: 63px;" maxlength="10" value="<?=elm($input, 'edate')?>" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<div class="wp100 mb10">
    <a href="/menu/task/input" class="c-success"><i class="fa fa-pencil fa-2x"></i></a>
</div>

<?php include_once PATH_ROOT_VIEWS . "/auth/menu/task/list.php"; ?>

<script type="text/javascript">
    $(document).ready(function() {

        var $form = $("form[name='work']");

        $form.find("select[name='target']").change(submit);
        $form.find("select[name='kind']").change(submit);
        $form.find("select[name='member_idx']").change(submit);
        $form.find("select[name='state']").change(submit);

        function submit() {
            $form.submit();
        }
    });
</script>

<?php include_once PATH_ROOT_VIEWS . "/templates/bottom.php"; ?>