<form id="myForm" class="rowSection validat" method="post" action="">
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-horizontal row-border">
                    <div class="form-actions" style="display: none;">
                        <input class="submit btn btn-primary pull-right" type="submit" value="Отправить">
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Название</label>
                        <div class="col-md-10">
                            <input class="form-control valid" type="text" name="FORM[name]" value="<?php echo htmlspecialchars( $obj->name ); ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Ссылка</label>
                        <div class="col-md-10">
                            <input class="form-control" id="link" type="text" name="FORM[link]" value="<?php echo str_replace('http://'.Core\Arr::get($_SERVER, 'HTTP_HOST').'/', '', $obj->link); ?>"/>
                            <div class="thisLink"><span class="mainLink"><?php echo 'http://'.Core\Arr::get($_SERVER, 'HTTP_HOST'); ?></span><span class="samaLink"></span></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Опубликовано</label>
                        <div class="col-md-10">
                            <label class="checkerWrap-inline">
                                <input name="status" value="0" type="radio" <?php echo (!$obj->status AND $obj) ? 'checked' : ''; ?>>                            
                                Нет
                            </label>
                            <label class="checkerWrap-inline">
                                <input name="status" value="1" type="radio" <?php echo ($obj->status OR !$obj) ? 'checked' : ''; ?>>
                                Да
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Title</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="FORM[title]" value="<?php echo htmlspecialchars( $obj->title ); ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">H1</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="FORM[h1]" value="<?php echo htmlspecialchars( $obj->h1 ); ?>"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Keywords</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="FORM[keywords]" rows="20"><?php echo $obj->keywords; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Description</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="FORM[description]" rows="20"><?php echo $obj->description; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Seo text</label>
                        <div class="col-md-10">
                            <textarea class="form-control tinymceEditor" name="FORM[text]" rows="20"><?php echo $obj->text; ?></textarea>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php if ( $obj->id ): ?>
        <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
    <?php endif ?>
</form>

<script type="text/javascript">
    function generate_link() {
        var link = $('#link').val();
        if(link != '') {
            if(link[0] != '/') {
                link = '/' + link;
            }
        }
        $('.samaLink').text(link);
    }
    $(document).ready(function(){
        generate_link();
        $('body').on('keyup', '#link', function(){ generate_link(); });
        $('body').on('change', '#link', function(){ generate_link(); });
    });
</script>