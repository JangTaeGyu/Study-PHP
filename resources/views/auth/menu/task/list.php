<div class="mb50">
    <table class="basic_table">
        <colgroup>
            <col width="60"><col width="120px"><col width="*"><col width="90px"><col width="90px"><col width="90px"><col width="90px">
        </colgroup>
        <thead>
            <tr>
                <th class="none">No.</th>
                <th>업무대상</th>
                <th>제목</th>
                <th>요청자</th>
                <th>상태</th>
                <th>완료일자</th>
                <th>등록일자</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($output['task']) === 0): ?>
            <tr>
                <td class="none taC" colspan="7">데이터가 없습니다.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($output['task'] as $tkey => $tvalue): ?>
            <tr style="<?=$tvalue['issue'] === 'Y' ? 'background-color: #f2dede;' : ''?>">
                <td class="none taC"><?=count($output['task']) - $tkey?></td>
                <td class="taC"><?=$output['code']['target'][$tvalue['target']]?></td>
                <td>
                    <span style="font-weight: 600; font-size: 14px;">[<?=$output['code']['kind'][$tvalue['kind']]?>]</span>
                    <a href="/menu/task/input?idx=<?=$tvalue['idx']?>" style="text-decoration: underline;">
                        <?=$tvalue['title']?>
                    </a>
                </td>
                <td class="taC"><?=$tvalue['name']?></td>
                <td class="taC">
                <?php
                    switch ($tvalue['state']) {
                        case 'W': echo '대기'; break;
                        case 'S': echo '완료'; break;
                        case 'N': echo '취소'; break;
                    }
                ?>
                </td>
                <td class="taC"><?=$tvalue['complete_date'] === '0000-00-00' ? '' : $tvalue['complete_date']?></td>
                <td class="taC"><?=date('Y-m-d', strtotime($tvalue['date']))?></td>
            </tr>
            <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
</div>