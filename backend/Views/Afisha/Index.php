<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    <?php echo $pageName; ?>
                    <span class="label label-primary"><?php echo $count; ?></span>
                </div>
                <div class="filter">
                    <form action="" method="get" accept-charset="utf-8">
                        <div style="display: inline-block;">
                            <label class="control-label" style="display: block;">Название</label>
                            <input name="name" class="form-control" value="<?php echo urldecode(Core\Arr::get($_GET, 'name', NULL)); ?>">
                        </div>
                        <div style="display: inline-block;vertical-align: top;">
                            <label class="control-label" style="display: block;">Дата от</label>
                            <input name="date_s" class="form-control fPicker" value="<?php echo Core\Arr::get($_GET, 'date_s', NULL); ?>">
                        </div>
                        <div style="display: inline-block;vertical-align: top;">
                            <label class="control-label" style="display: block;">Дата до</label>
                            <input name="date_po" class="form-control fPicker" value="<?php echo Core\Arr::get($_GET, 'date_po', NULL); ?>">
                        </div>
                        <div style="display: inline-block;">
                            <label class="control-label" style="display: block;">Место проведения</label>
                            <select name="place">
                                <option value="">Все</option>
                                <option value="null" <?php echo $_GET['place'] == 'null' ? 'selected' : null; ?>>Другое</option>
                                <?php foreach ($places as $place): ?>
                                    <option value="<?php echo $place->id; ?>" <?php echo $_GET['place'] == $place->id ? 'selected' : '' ?>><?php echo $place->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="display: inline-block;">
                            <label class="control-label" style="display: block;">Город</label>
                            <select name="city">
                                <option value="">Все</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city->id; ?>" <?php echo $_GET['city'] == $city->id ? 'selected' : '' ?>><?php echo $city->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="display: inline-block;">
                            <label class="control-label" style="display: block;">Статус</label>
                            <select name="status">
                                <option value="">Все</option>
                                <option value="published" <?php echo $_GET['status'] == "published" ? 'selected' : '' ?>>Опубликовано</option>
                                <option value="no_published" <?php echo $_GET['status'] == "no_published" ? 'selected' : '' ?>>Не опубликовано</option>
                            </select>
                        </div>
                        <input class="button btn-primary" style="display: inline-block;" type="submit" name="send" value="Поиск">
                        <a href="/backend/afisha/index" style="display: inline-block;">
                            <i class="fa-refresh"></i>
                            <span class="">Сбросить</span>
                        </a>
                    </form>
                </div>
            </div>

            <div class="widget">
                <div class="widgetContent">
                    <div class="widget">
                        <div class="widgetContent">
                            <div class="dd pageList" <?php echo Core\User::caccess() == 'edit' ? 'id="myNest"' : null ?>>
                                <ol class="dd-list">
                                    <?php echo Core\View::tpl(array('result' => $result, 'tpl_folder' => $tpl_folder, 'cur' => 0), $tpl_folder.'/Menu'); ?>
                                </ol>
                            </div>
                            <span id="parameters" data-table="<?php echo $tablename; ?>"></span>
                            <input type="hidden" id="myNestJson">
                            <?php echo $pager; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>