<div class="widget box">
    <div class="widgetHeader">
        <div class="widgetTitle">
            <i class="fa-reorder"></i>
            Фильтр
        </div>
    </div>
    <div class="widgetContent">
        <div class="filter">
            <form action="" method="get" accept-charset="utf-8">
                <div style="display: inline-block;">
                    <label class="control-label" style="display: block;">Мероприятие</label>
                    <select name="event">
                        <option value="0">Все</option>
                        <?php foreach ($events as $key => $value): ?>
                            <option value="<?php echo $value->id ?>" <?php echo $_GET['event'] == $value->id ? 'selected' : '' ?>><?php echo $value->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
<!--                <div style="display: inline-block;vertical-align: top;">-->
<!--                    <label class="control-label" style="display: block;">Дата от</label>-->
<!--                    <input name="date_s" class="form-control fPicker" value="--><?php //echo Core\Arr::get($_GET, 'date_s', NULL); ?><!--">-->
<!--                </div>-->
<!--                <div style="display: inline-block;vertical-align: top;">-->
<!--                    <label class="control-label" style="display: block;">Дата до</label>-->
<!--                    <input name="date_po" class="form-control fPicker" value="--><?php //echo Core\Arr::get($_GET, 'date_po', NULL); ?><!--">-->
<!--                </div>-->
                <div style="display: inline-block;">
                    <label class="control-label" style="display: block;">Статус</label>
                    <select name="status">
                        <option value="null">Все</option>
                        <option value="1" <?php echo $_GET['status'] == "1" ? 'selected' : '' ?>>Опубликовано</option>
                        <option value="0" <?php echo $_GET['status'] == "0" ? 'selected' : '' ?>>Не опубликовано</option>
                    </select>
                </div>
                <input class="button btn-primary" style="display: inline-block;" type="submit" name="send" value="Поиск">
                <a href="/backend/organizer/inner/<?php echo Core\Route::param('id'); ?>" style="display: inline-block;">
                    <i class="fa-refresh"></i>
                    <span class="">Сбросить</span>
                </a>
            </form>
        </div>
    </div>
</div>


