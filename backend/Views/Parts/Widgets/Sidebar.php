<div class="sideBar sideBarFixed sideBarSize">
    <div class="mCustomScrollbar fluid sideBarScrollSize">
        <div class="sideBarContent">
            <ul class="navLeft">
                <?php foreach ( $result[0] as $obj ): ?>
                    <?php $ctrls = explode( ';', $obj->ctrls ); ?>
                    <?php if ( in_array( Core\Route::controller(),  $ctrls ) AND !$obj->link ): ?>
                        <?php $li = $obj->id; ?>
                    <?php endif ?>
                    <?php $check = ( Core\Route::controller() . '/' . Core\Config::get( 'action' ) == $obj->link ); ?>
                    <li data-id="<?php echo $obj->id; ?>">
                        <a class="<?php echo $check ? 'cur' : ''; ?>" href="/backend/<?php echo $obj->link; ?>">
                            <i class="<?php echo $obj->icon; ?>"></i>
                            <?php echo $obj->name; ?>
                            <i class="arrow fa-angle-right"></i>
                        </a>
                        <?php if ( isset( $result[ $obj->id ] ) AND count( $result[ $obj->id ] ) ): ?>
                            <ul class="subMenu">
                                <?php foreach ( $result[ $obj->id ] as $_obj ): ?>
                                    <?php $check = Core\Route::controller() . '/' . Core\Route::action() == $_obj->link; ?>
                                    <?php if ( $check AND $li == $obj->id ): ?>
                                        <?php $li = 0; ?>
                                    <?php endif ?>
                                    <li><a class="<?php echo $check ? 'cur' : ''; ?>" href="/backend/<?php echo $_obj->link; ?>"><i class="fa-angle-right"></i><?php echo $_obj->name; ?></a></li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <div class="divider resizeable resizeablePos"></div>
</div>

<?php if ($li): ?>
    <script>
        $(function(){
            $('ul.navLeft > li[data-id="<?php echo $li; ?>"] > a').click();
        });
    </script>
<?php endif ?>