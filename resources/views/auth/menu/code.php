<?php use App\Core\{ Config, Csrf }; ?>
<?php include_once PATH_ROOT_VIEWS . "/templates/top.php"; ?>

<h3>코드관리</h3>

<?php if (!$output['result']): ?>
<p class="box-danger mb10"><?=$output['message']?></p>
<?php endif ?>

<div class="wp20 fL">
    <table class="basic_table">
        <colgroup>
            <col width="120px"><col width="*">
        </colgroup>
        <thead>
            <tr>
                <th class="none">메인코드</th>
                <th>명칭</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($output['main'] as $mvalue): ?>
            <tr>
                <td class="none taC"><?=$mvalue['sub']?></td>
                <td>
                    <a href="/menu/code?code=<?=$mvalue['sub']?>"><?=$mvalue['name']?>
                    <?php if ($mvalue['sub'] === $input['code']): ?>
                    <i class="fR mr5 c-primary fa fa-circle"></i>
                    <?php endif ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<div class="wp75 fR">
    <form name="work" method="post" action="/menu/code/create" accept-charset="utf-8">
        <input type="hidden" name="mode" value="<?=elm($input, 'mode')?>" />
        <input type="hidden" name="main" value="<?=elm($input, 'code')?>" />
        <input type="hidden" name="idx" />

        <table class="basic_table">
            <colgroup>
                <col width="120px"><col width="150px"><col width="430px"><col width="90px"><col width="*">
            </colgroup>
            <thead>
                <tr>
                    <th class="none">서브코드</th>
                    <th>명칭</th>
                    <th>상세내용</th>
                    <th>상태</th>
                    <th>기능</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="none taC"><input type="text" name="sub" style="width: 92px" maxlength="25" placeholder="서브코드" /></td>
                    <td class="taC"><input type="text" name="name" style="width: 121px;" maxlength="25" placeholder="명칭" /></td>
                    <td class="taC"><input type="text" name="detail" style="width: 401px;" maxlength="100" placeholder="상세내용" /></td>
                    <td class="taC">
                        <select name="state" style="width: 79px;">
                            <option value="Y">활성화</option>
                            <option value="N">비활성화</option>
                        </select>
                    </td>
                    <td class="taC"><button type="submit" class="c-success"><i class="fa fa-pencil fa-2x"></i></button></td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="<?=Config::get('csrf/token_name')?>" value="<?=Csrf::generate()?>" />
    </form>

    <?php if (count($output['sub']) > 0): ?>
    <table class="basic_table">
        <colgroup>
            <col width="120px"><col width="150px"><col width="430px"><col width="90px"><col width="*">
        </colgroup>
        <tbody>
        <?php foreach ($output['sub'] as $svalue): ?>
            <tr>
                <td class="none taC"><?=$svalue['sub']?></td>
                <td class="taC"><?=$svalue['name']?></td>
                <td><?=$svalue['detail']?></td>
                <td class="taC"><?=$svalue['state'] === 'Y' ? '활성화' : '비활성화' ?></td>
                <td class="taC">
                    <button type="button"
                            class="c-warning btn_update"
                            data-index="<?=$svalue['idx']?>"
                            data-name="<?=$svalue['name']?>"
                            data-sub="<?=$svalue['sub']?>"
                            data-detail="<?=$svalue['detail']?>"
                            data-state="<?=$svalue['state']?>"><i class="fa fa-edit fa-2x mr10"></i></button>
                    <button type="button" class="c-danger btn_delete" data-index="<?=$svalue['idx']?>"><i class="fa fa-trash-o fa-2x"></i></button>
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
            var sub = $(this).data("sub");
            var name = $(this).data("name");
            var detail = $(this).data("detail");
            var state = $(this).data("state");

            $form.find("input[name='mode']").val("update");
            $form.find("input[name='idx']").val(index);
            $form.find("input[name='sub']").val(sub);
            $form.find("input[name='name']").val(name);
            $form.find("input[name='detail']").val(detail);
            $form.find("select[name='state']").val(state);
            $form.attr("action", "/menu/code/update");
        });

        $(".btn_delete").click(function(e) {
            e.stopImmediatePropagation();

            var state = confirm("정말로 삭제 하시겠습니까?");
            if (state) {
                var index = $(this).data("index");

                $form.find("input[name='mode']").val("delete");
                $form.find("input[name='idx']").val(index);
                $form.attr("action", "/menu/code/delete");
                submit();
            }
        });

        function submit() {
            $form.submit();
        }
    });
</script>

<?php include_once PATH_ROOT_VIEWS . "/templates/bottom.php"; ?>