<div class="listPlaces">
    <?php foreach ($result as $key => $obj): ?>
        <div class="form-block place">
            <h5 style="color: <?php echo $obj->color ?>"><?php echo $obj->name ?></h5>
            <label class="control-label">Опубликовано</label>
            <div class="dib">
                <label class="checkerWrap-inline radioWrap">
                    <input name="SECTOR[<?php echo $obj->id?>][status]" value="0" type="radio" class="show" <?php if(isset($prices) AND $prices[$obj->id]['status'] == 0) echo 'checked' ?>>                            
                    Нет
                </label>
                <label class="checkerWrap-inline radioWrap">
                    <input name="SECTOR[<?php echo $obj->id?>][status]" value="1" type="radio" class="show" <?php if( (isset($prices) AND $prices[$obj->id]['status'] == 1) OR !isset($prices)) echo 'checked' ?>>
                    Да
                </label>
            </div>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            <label class="control-label">Стоимость</label>
            <input type="number" class="sector-cost" name="SECTOR[<?php echo $obj->id ?>][price]" value="<?php echo $prices[$obj->id]['price'] ?>">
        </div>
    <?php endforeach ?>
</div>
