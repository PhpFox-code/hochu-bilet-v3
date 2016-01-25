<div class="rowSection">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">

                <?php echo Core\View::tpl(array('creators' => array(), 'events' => $events), 'Cassier/Filter'); ?>

                <div class="checkbox-wrap">
                    <?php foreach($afishaGroups as $key => $value): ?>
                        <?php $afisha = $value['afisha']; ?>
                        <div style="margin-bottom: 20px;" class="eventWrapp">
                            <div class="eventName">
                                <a class="fll" href="/backend/afisha/edit/<?php echo $afisha->id; ?>"><?php echo $afisha->name ?></a>
                                <a class="flr printTable" href="#">Распечатать</a>
                            </div>
                            <div class="eventOrders">
                                <?php echo Core\View::tpl(array('result' => $value['orders'], 'afisha' => $afisha,
                                    'pay_statuses' => $pay_statuses), $tpl_folder.'/Orders') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php echo $pager; ?>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.orderLabelStatus').on('click', function(e){
            var it = $(this);
            var id = it.closest('tr').attr('data-id');
            var status = it.attr('data-status');
            var sess = it.attr('data-session');
            $.ajax({
                url: '/backend/ajax/changeStatusOrder.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    sess: sess,
                    status: status
                },
                success: function(data) {
                    if( data.success ) {
                        it.attr( 'data-status', data.st.id );
                        it.attr( 'title', data.st.name );
                        var cl = it.attr( 'data-class' );
                        it.attr( 'data-class', data.st.class );
                        it.removeClass( cl );
                        it.addClass( data.st.class );
                        it.find('span').html( data.st.name );
                    }
                }
            });
        });

        $('.ranges > ul > li.active').removeClass('active');
    });
</script>