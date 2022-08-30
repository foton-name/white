<div class='content'>
    <script>
        <?=$data['js_i'];?>
    </script>
    <style>
        <?=$data['css_i'];?>
    </style>

    <h1 class='h1_razdel'><?= $data['h1']; ?></h1>

    <? if ($data['is_list']){
        if (isset($data['update']) && $data['update'] !== false) {
            ?>
            <a href='/list/<?= $this->request->g['2'] . '/' . $this->request->g['3']; ?>' class='create_i'>Вернутся</a>
            <form action='' enctype='multipart/form-data' method='post' class="short_table_inc_one forminc">
                <? foreach ($data['update'][0] as $k => $v) {
                    if ($k == 0) {
                        ?><input type='hidden' name='id' value='<?= $v; ?>'>
                    <? } else {
                        echo $v;
                    }
                }
                ?>
                <br><br>
                <input type="submit" name="delete-insert-include" value="Удалить"><input type="submit" class='id-up'
                                                                                         value="Обновить"><br>
            </form>


        <?
        } else if (isset($data['create']) && $data['create'] !== false) { ?>
            <a href='/list/<?= $this->request->g['2'] . '/' . $this->request->g['3']; ?>' class='create_i'>Вернутся</a>
            <form action='' enctype='multipart/form-data' method='post' class="short_table_inc_one">
                <? foreach ($data['create'][0] as $k => $v) {
                    echo $v;
                }
                ?>
                <br><br>
                <input type='hidden' name='foton_create' value='0'>
                <input type="submit" name="id" id='create_one' value="Создать"><br>
            </form>
        <?
        } else {
            ?>

            <a href='/list/<?= $this->request->g['2'] . '/' . $this->request->g['3']; ?>/create/' class='create_i'>Создать</a>

            <form action='' method='post' id="i_f_filtr">
                <? foreach ($data['filter_find'][0] as $key_i => $f_item) { ?>
                    <? $f_item = str_replace("name='", "name='find_", $f_item);
                    echo $f_item; ?>
                <?
                } ?>
                <input type="submit" value="Найти">
            </form>


            <div class='form-red-inc '>
                <div class="short_table_inc sort_bx">
                    <? foreach ($data['filter_sort'][0] as $key_i => $s_item) {
                        if ($s_item != '') { ?>
                            <form action='' method='post'>
                                <input type="hidden" name="foton_sort" value="<?= $data['sort_field']; ?>">
                                <?= $s_item; ?>

                            </form>
                        <?
                        }
                    } ?>
                </div>
                <? foreach ($data["echo_lists"] as $k => $v) { ?>
                    <form action='' enctype='multipart/form-data' method='post' class="short_table_inc">
                        <?
                        foreach ($v as $k_i => $v_i) {
                            if ($k_i == 0) {
                                ?>
                                <a href="<?= '/list/' . $this->request->g['2'] . '/' . $this->request->g['3']; ?>/<?= $v_i; ?>">Перейти</a>
                                <input type='hidden' name='id' value='<?= $v_i; ?>'>
                            <? } else {
                                ?>
                                <?= $v_i; ?>
                            <?
                            }
                        } ?>

                        <input type="submit" name="delete-insert-include" value="Удалить">
                    </form>
                <?
                } ?>

            </div>
            <?php  if($data['pagination']>0 && isset($this->request->g['2']) && isset($this->request->g['3'])){ ?>
  <nav aria-label="...">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="/list/<?=$this->request->g['2'];?>/<?=$this->request->g['3'];?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?for($i=1;$i<$data['pagination']+1;$i++){?>
    <?if($i-1!=$data['page']){
    if($i>1){?>
    <li class="page-item"><a class="page-link" href="/list/<?=$this->request->g['2'];?>/<?=$this->request->g['3'];?>/pagin/<?=$i;?>/"><?=$i;?></a></li>
    <?}else{?>
        <li class="page-item"><a class="page-link" href="/list/<?=$this->request->g['2'];?>/<?=$this->request->g['3'];?>/"><?=$i;?></a></li>
    <?}?>
    <?}else{?>
    <li class="page-item active" aria-current="page">
      <span class="page-link">
        <?=$i;?>
      </span>
    </li>
    <?}}?>
       <li class="page-item">
      <a class="page-link" href="/list/<?=$this->request->g['2'];?>/<?=$this->request->g['3'];?>/pagin/<?=$i-1;?>/" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
<?php  }?>
            <?
        }
    } else{ ?>
    <div class='form-red-inc'>
        <form action='' enctype='multipart/form-data' method='post'>
            <? foreach ($data['create'][0] as $k => $v) {
                echo $v;
            }
            ?>
            <br><br>
            <input type='hidden' name='foton_create' value='0'>
            <input type="submit" name="id" value="Создать"><br>
        </form>
        <? foreach ($data['list_one'] as $item) { ?>
            <form action='' enctype='multipart/form-data' method='post'>
                <? foreach ($item as $k => $v) {
                    if ($k == 0) {
                        ?>
                        <input type='hidden' name='id' value='<?= $v; ?>'>
                    <? } else {
                        echo $v;
                    }
                }
                ?>
                <br><br>
                <input type="submit" name="delete-insert-include" value="Удалить"><input type="submit" class='id-up-one'
                                                                                         value="Обновить"><br>
            </form>
        <?
        } ?>
    </div>
<?php
  }
  
  