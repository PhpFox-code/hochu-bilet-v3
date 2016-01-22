<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa-reorder"></i>
                    <?php echo $pageName; ?>
                </div>
            </div>

            <div class="widgetContent">
                <div class="checkbox-wrap">
                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Номер</th>
                                <th>Имя</th>
                                <th>Дата регистрации</th>
                                <th>Подробнее</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $result as $obj ): ?>
                                <tr>
                                    <td class="hidden-ss"><a href="/backend/admins/edit/<?php echo $obj->id; ?>"><?php echo $obj->id; ?></a></td>
                                    <td><a href="/backend/organizer/inner/<?php echo $obj->id; ?>"><?php echo $obj->name; ?></a></td>
                                    <td><?php echo date( 'd.m.Y H:i', $obj->created_at ); ?></td>
                                    <td><a href="/backend/organizer/inner/<?php echo $obj->id; ?>">Подробнее</a></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
