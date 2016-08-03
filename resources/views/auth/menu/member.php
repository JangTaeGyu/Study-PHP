<?php use App\Core\{ Config, Csrf }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/top.php"; ?>

<h3>회원관리</h3>

<?php if (!$output['result']): ?>
<p class="box-danger mb10"><?=$output['message']?></p>
<?php endif ?>

<div class="">
    <form name="work" method="post" action="/menu/member/create" accept-charset="utf-8">
        <input type="hidden" name="mode" value="<?=elm($input, 'mode')?>" />
        <input type="hidden" name="idx" />

        <table class="basic_table">
            <colgroup>
                <col width="170px"><col width="170px"><col width="170px"><col width="330px"><col width="100px;"><col width="100px;"><col width="*">
            </colgroup>
            <thead>
                <tr>
                    <th class="none">회사</th>
                    <th>부서</th>
                    <th>직급</th>
                    <th>이름</th>
                    <th>내선</th>
                    <th>상태</th>
                    <th>기능</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="none taC">
                        <select name="company" style="width: 159px;">
                            <?php foreach ($output['code']['company'] as $ckey => $cvalue): ?>
                            <option value="<?=$ckey?>" <?=$ckey === elm($input, 'company') ? 'selected' : ''?>><?=$cvalue?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td class="taC">
                        <select name="department" style="width: 159px;">
                            <?php foreach ($output['code']['department'] as $dkey => $dvalue): ?>
                            <option value="<?=$dkey?>" <?=$dkey === elm($input, 'department') ? 'selected' : ''?>><?=$dvalue?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td class="taC">
                        <select name="rank" style="width: 159px;">
                            <?php foreach ($output['code']['rank'] as $rkey => $rvalue): ?>
                            <option value="<?=$rkey?>" <?=$rkey === elm($input, 'rank') ? 'selected' : ''?>><?=$rvalue?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td class="taC"><input type="text" name="name" style="width: 301px;" maxlength="25" placeholder="이름" /></td>
                    <td class="taC"><input type="text" name="call" style="width: 71px;" maxlength="25" placeholder="내선" /></td>
                    <td class="taC">
                        <select name="state" style="width: 89px;">
                            <option value="Y">재직</option>
                            <option value="N">퇴사</option>
                        </select>
                    </td>
                    <td class="taC"><button type="submit" class="c-success"><i class="fa fa-pencil fa-2x"></i></button></td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="<?=Config::get('csrf/token_name')?>" value="<?=Csrf::generate()?>" />
    </form>

    <?php if (count($output['member']) > 0): ?>
    <table class="basic_table mb50">
        <colgroup>
            <col width="170px"><col width="170px"><col width="170px"><col width="330px"><col width="100px;"><col width="100px;"><col width="*">
        </colgroup>
        <tbody>
        <?php foreach ($output['member'] as $mvalue): ?>
            <tr>
                <td class="none taC"><?=$output['code']['company'][$mvalue['company']]?></td>
                <td class="taC"><?=$output['code']['department'][$mvalue['department']]?></td>
                <td class="taC"><?=$output['code']['rank'][$mvalue['rank']]?></td>
                <td><?=$mvalue['name']?></td>
                <td class="taC"><?=$mvalue['call']?></td>
                <td class="taC"><?=$mvalue['state'] === 'Y' ? '재직' : '퇴사' ?></td>
                <td class="taC">
                    <button type="button"
                            class="c-warning btn_update"
                            data-index="<?=$mvalue['idx']?>"
                            data-company="<?=$mvalue['company']?>"
                            data-department="<?=$mvalue['department']?>"
                            data-rank="<?=$mvalue['rank']?>"
                            data-name="<?=$mvalue['name']?>"
                            data-call="<?=$mvalue['call']?>"
                            data-state="<?=$mvalue['state']?>"><i class="fa fa-edit fa-2x mr10"></i></button>
                    <button type="button" class="c-danger btn_delete" data-index="<?=$mvalue['idx']?>"><i class="fa fa-trash-o fa-2x"></i></button>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php endif ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        var $form = $("form[name='work']");

        $(".btn_update").click(function(e) {
            e.stopImmediatePropagation();

            var index = $(this).data("index");
            var company = $(this).data("company");
            var department = $(this).data("department");
            var rank = $(this).data("rank");
            var name = $(this).data("name");
            var call = $(this).data("call");
            var state = $(this).data("state");

            $form.find("input[name='mode']").val("update");
            $form.find("input[name='idx']").val(index);
            $form.find("select[name='company']").val(company);
            $form.find("select[name='department']").val(department);
            $form.find("select[name='rank']").val(rank);
            $form.find("input[name='name']").val(name);
            $form.find("input[name='call']").val(call);
            $form.find("select[name='state']").val(state);
            $form.attr("action", "/menu/member/update");
        });

        $(".btn_delete").click(function(e) {
            e.stopImmediatePropagation();

            var state = confirm("정말로 삭제 하시겠습니까?");
            if (state) {
                var index = $(this).data("index");

                $form.find("input[name='mode']").val("delete");
                $form.find("input[name='idx']").val(index);
                $form.attr("action", "/menu/member/delete");
                submit();
            }
        });

        function submit() {
            $form.submit();
        }
    });
</script>

<?php include_once PATH_ROOT_VIEWS . "/templates/bottom.php"; ?>