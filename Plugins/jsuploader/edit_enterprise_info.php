<script type="text/javascript" src="/js/backend/tiny/mce/tiny_mce_src.js"></script>
<script type='text/javascript' src="/js/backend/tiny/mce/tiny_mce.php"></script>
<script type="text/javascript" src="/js/backend/jquery.damnUploader.js"></script>
<script type="text/javascript" src="/js/backend/interfaceFileApi.js"></script>
<script type="text/javascript" src="/js/backend/interfaceImageApi.js"></script>
<div class="conteiner">
<?php if ($object->loaded()):?>
	<h1>Редактирование предприятия -> <a href="<?php echo href('backend_enterprises_list');?>">Список предприятий</a></h1>
<?php else:?>
	<h1>Добавление нового предприятия</h1>
<?php endif;?>
	<form class="form_add" id="commentForm" name="form_add" method="post" action="" enctype="multipart/form-data">
		<div class="textright contorl_edit">
			<input class="b middle green" type="submit" name="save" value="Сохранить">
			<input class="b middle" type="submit" name="save_and_back" value="Сохранить и вернуться к списку">
			<input class="b middle red" type="submit" name="close" value="Закрыть">
			<input class="b middle orange" type="submit" name="save_and_go" value="Перейти на редактируемую страницу">
		</div>
		<div class="items_wrap">
			<div class="item_f_wrap">
				<div class="item_col">
					<label class="title_1">
						Статус:
					</label>
                    <?php $public = isset($_POST['status']) ? $_POST['status'] : $object->status ;?>
					<select name='status'>
						<option value="0" <?php if ($public==0) echo 'selected';?>>
							Непубликовать
						</option>
						<option value="1" <?php if ($public==1) echo 'selected';?>>
							Публиковать
						</option>
					</select>
				</div>
				
                <div class="item_col" style="width:550px">
					<label class="title_1">
						URL своего ресурса:
					</label>
						<?php $public = Arr::get($_POST,'link_company') ? Arr::get($_POST,'link_company') : $object->link_company ;?>
                    <input type="text" value="<?php echo $public;?>" class="input_1" style="width:350px;" name="link_company" />
                    <?php //echo FORM::admin_error('link_company',$errors);?>
				</div>
                
                <div class="item_col" style="width:450px">
					<label class="title_1">
						Район:
					</label>
						<?php $public = Arr::get($_POST,'district') ? Arr::get($_POST,'district') : $object->district ;?>
                    <select name="district">
                    	<?php foreach($districts as $district): ?>
                    		<option value="<?php echo $district->id; ?>" <?php if($public == $district->id) echo 'selected'; ?>><?php echo $district->name; ?></option>
                        <?php endforeach;?>
                    </select>
                    <?php echo FORM::admin_error('link',$errors);?>
				</div>
                <div class="item_col">
                	<label class="title_1">
						Позиция для сортировки (Вывод MAX->MIN):
					</label>
                    <?php $public = Arr::get($_POST,'sort_adm') ? Arr::get($_POST,'sort_adm') : $object->sort_adm ;?>
                    <input type="text" value="<?php echo $public;?>" class="input_1" style="width:100px;" name="sort_adm" />
                </div>
			</div>
			<br />
            <div class="item_f_wrap">
                <div class="item_col" style="display:inline-block;">
                	<label class="title_1">
						Используемые:
					</label>
                    <br>
                    <?php $arr = array();?>
                    <select name="activites[]" multiple="multiple" style="min-height:100px;min-width:150px;" id="activites">
					<?php foreach($activites as $active): ?>
                    	<?php $arr[$active->id]=$active->name;?>
                        <option value="<?php echo $active->id; ?>"><?php echo $active->name; ?></option>
                    <?php endforeach; ?>
                    </select>
                    <br>
                    <a href="#" class="del_act">Удалить</a>
				</div>
               
                
				<div class="item_col" style="width:450px;height:100px;">
					<label class="title_1" style="vertical-align:top;">
						Список:
					</label>
                    <br>
					<select multiple="multiple" style="min-height:100px;" id="activites_list">
                    	<?php foreach($activites_all as $active): ?>
                        	<?php if(array_search($active->name,$arr)) continue;?>
                        	<option value="<?php echo $active->id; ?>"><?php echo $active->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <a href="#" class="add_act">Добавить</a>
				</div>
			</div>
            
		    <ul class="tabs lang_wrap">
                        <li><a class="current" href="#">Содержимое страницы</a></li>
                        <li><a href="#">Контактная информация</a></li>
                        <li><a href="#">Логотип</a></li>
                        <li><a href="#">Прайсы</a></li>
                        <li><a href="#">Галерея</a></li>
                        <li><a href="#">GoogleMaps</a></li>
			<li><a href="#">SEO</a></li>
                        <li><a href="#">Настройки</a></li>
		    </ul>
			<div class="box">
				<div class="item_f_wrap">
					<div class="item_1">
						<label class="block title_1">
							Название предприятия <span class="red">*</span>:
						</label>
						<?php $public = isset($_POST['name']) ? $_POST['name'] : $object->name ;?>
						<input name="name" type="text" class="s_name input_1" value="<?php echo $public;?>" id="name">
         	           <?php echo FORM::admin_error('name',$errors);?>
					</div>
					
                    <div class="item_1">
                        <label class="block title_1">
                            URL:
                        </label>
                            <?php $public = Arr::get($_POST,'link') ? Arr::get($_POST,'link') : $object->link ;?>
                        <input type="text" value="<?php echo $public;?>" class="input_1" name="link"/>
                        <?php echo FORM::admin_error('link',$errors);?>
                    </div>
                
                
                    <div class="item_1">
						<label class="block title_1">
							Краткое содержание:
						</label>
						<?php $public = isset($_POST['short_info']) ? $_POST['short_info'] : $object->short_info ;?>
						<textarea name="short_info" id="content1" rows="10" class="text_1 tiny"><?php echo $public;?></textarea>
                        <?php echo FORM::admin_error('short_info',$errors);?>
					</div>
                    
					<div class="item_1">
						<label class="block title_1">
							Полное содержание раздела:
						</label>
						<?php $public = isset($_POST['content']) ? $_POST['content'] : $object->content ;?>
						<textarea name="content" id="content" rows="30" class="text_1 tiny"><?php echo $public;?></textarea>
                        <?php echo FORM::admin_error('content',$errors);?>
					</div>
				</div>
			</div>
            <div class="box">
            	<div class="item_f_wrap">
                	<div class="item_1">
						<label class="block title_1">
							Адрес предприятия <span class="red">*</span>:
						</label>
						<?php $public = isset($_POST['address']) ? $_POST['address'] : $object->address ;?>
						<input name="address" type="text" class="s_name input_1" value="<?php echo $public;?>">
         	           <?php echo FORM::admin_error('address',$errors);?>
					</div>
                    
                    <div class="item_1">
						<label class="block title_1">
							Телефоны <span class="red">*</span>:
						</label>
						<?php $public = isset($_POST['phones']) ? $_POST['phones'] : $object->phones ;?>
						<input name="phones" type="text" class="s_name input_1" value="<?php echo $public;?>">
         	           <?php echo FORM::admin_error('phones',$errors);?>
					</div>
                    
                    <div class="item_1">
						<label class="block title_1">
							E-mail :
						</label>
						<?php $public = isset($_POST['email']) ? $_POST['email'] : $object->email ;?>
						<input name="email" type="text" class="s_name input_1" value="<?php echo $public;?>">
         	           <?php echo FORM::admin_error('email',$errors);?>
					</div>
                    
                    <div class="item_1">
						<label class="block title_1">
							Контактное лицо :
						</label>
						<?php $public = isset($_POST['contact_info']) ? $_POST['contact_info'] : $object->contact_info ;?>
						<input name="contact_info" type="text" class="s_name input_1" value="<?php echo $public;?>">
         	           <?php echo FORM::admin_error('contact_info',$errors);?>
					</div>
					
					<div class="item_1">
						<label class="block title_1">
							Служебная информация:
						</label>
						<?php $public = isset($_POST['adm_note']) ? $_POST['adm_note'] : $object->adm_note ;?>
						<textarea name="adm_note" rows="7" class="text_1"><?php echo $public;?></textarea>
					</div>
                </div>
            </div>
            <div class="box">
            	<div class="item_f_wrap">
                	<div class="item_1">
						<label class="block title_1">
							Файл логотипа :
						</label>
						<?php if($object->logo != ''): ?>
                        	
                       		<?php if(File::mime('uploads/enterprises/'.$object->folder.'/logo/'.$object->logo) == 'application/x-shockwave-flash'): ?>
                                <object width="100%" height="200px"  id="test" data="/uploads/enterprises/<?php echo $object->folder.'/logo/'.$object->logo; ?>" type="application/x-shockwave-flash" >
                                    <param name="movie" value="/uploads/<?php echo $object->folder.'/logo/'.$object->logo;?>" />
                                    <param name="wmode" value="transparent">
                                    <!--[if !IE]>-->
                                        <object type="application/x-shockwave-flash" data="/uploads/enterprises/<?php echo $object->folder.'/logo/'.$object->logo; ?>" width="100%" height="175" >
                                        <param name="movie" value="/uploads/enterprises/<?php echo $object->folder.'/logo/'.$object->logo;?>" />
                                        <param name="wmode" value="transparent">                   
                                        </object>
                                    <!--<![endif]-->
                                </object>
    						<?php else: ?>
                                <img src="/uploads/enterprises/<?php echo $object->folder.'/logo/'.$object->logo; ?>" style="border:1px solid;">
                            <?php endif; ?>
                            	<br>
                                <input name="file" type="hidden" class="s_name input_1" value="<?php echo $object->logo; ?>">
                                <div class="textright contorl_edit" style="text-align:left;">
                                <input class="b middle red" type="submit" name="delete_file" value="Удалить файл">
                                </div>
                        <?php else: ?>
                        	<input name="file" type="file" class="s_name input_1">
						<?php endif; ?>
                        <?php echo FORM::admin_error('file',$errors);?>
					</div>
                  </div>
            </div>
                        <div class="box">
                            <div class="item_f_wrap">
                                <?php if($object->folder != ''): ?>
                                <div class="item_1" style="width:35%;display: inline-block; vertical-align: top;">
                                    <div>
                                        <?php if(count($prices)):?>
                                        <table class="table1" width="100%">
                                            <th>Файл</th>
                                            <th>Операции</th>
                                            
                                            <tbody>
                                                <?php foreach($prices as $price):?>
                                                    <tr id="tr<?php echo $price->id; ?>">
                                                        <td><?php echo $price->name; ?></td>
                                                        <td>
                                                            <a href="/uploads/enterprises/<?php echo $object->folder.'/prices/'.$price->file_name; ?>" target="_blank">Просмотр</a> 
                                                            | 
                                                            <a href="#" data-id="<?php echo $price->id; ?>" class="delete_price">Удалить</a></td>
                                                    </tr>
                                                <?php endforeach;?>
                                            </tbody>
                                        </table>
                                        <?php else: ?>
                                            <strong>У данного предприятия нет загруженных прайсов.</strong>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="item_1" style="width:20%;display: inline-block;vertical-align: top;">
                                    <div id="leftpanel" style="padding-left:25px;">
                                        <div id="actions">
                                            <span id="info-count">Изображений не выбрано</span><br/>
                                            Общий размер: <span id="info-size">0</span> Кб<br/><br/>
                                            <button id="upload-all">Загрузить все</button>
                                            <button id="cancel-all">Отменить все</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="item_1" style="width:35%;display: inline-block;vertical-align: top;">
                                    <div>
                                        Чтобы добавить картинки, выбери их в поле<br/><br/>
                                        <input type="file" name="price" id="file-field" /><br/><br/>
                                        <span id="dropBox-label">... или просто перетащите их в область ниже &dArr;</span>
                       
                                    </div>
                                    <div id="img-container">
                                        <ul id="img-list"></ul>
                                    </div>
                                    
                                </div>
                                <?php else: ?>
                                    Для загрузки файлов необходимо создать директорию у данного предприятия
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="box">
					<div class="item_f_wrap">
                                            <?php if($object->folder != ''):?>
                                            <div style="display:inline-block;width:30%; vertical-align: top;">
                                                <h2><a class="create_album" style="cursor:pointer;">Создать альбом</a></h2>
                                                <?php if(count($albmus) > 0):?>
                                                <table class="table1" width="100%">
                                                    <th>Название</th>
                                                    <th>Операции</th>
                                                    
                                                    <tbody>
                                                <?php foreach ($albums as $album):?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $album->name; ?>
                                                        </td>
                                                   
                                                        <td>
                                                            <a style="cursor:pointer;" class="op_album" data-id="<?php echo $object->id; ?>" data-action="1">Фотографии</a>
                                                            |
                                                            <a style="cursor:pointer;" class="edit_album" data-id="<?php echo $object->id; ?>">Редактировать</a>
                                                            |
                                                            <a style="cursor:pointer;" class="add_photo" data-id="<?php echo $object->id; ?>">Добавить фотографии</a>
                                                       </td>
                                                            </tr>
                                                  
                                                <?php endforeach; ?>
                                                    </tbody>
                                                    </table>
                                               <?php endif; ?>
                                            </div>
                                            <div style="display:inline-block;width:69%; vertical-align: top; height: 1200px;">
                                                <div id="edit_album" style="height:1200px;">
                                                </div>
                                            </div>
                                            <?php else: ?>
                                                <h2>Необходимо создать директорию для предприятия!</h2>
                                            <?php endif; ?>
                                            </div>
                        </div>
                        <div class="box">
                                <div class="item_f_wrap">
                                   <div style="display:inline-block; vertical-align: top;width:25%;">
                                        <?php if(!$map->loaded()):?>
                                            <h2><a style="cursor:pointer;" class="create_map">Создать карту</a></h2>
                                        <?php else: ?>
                                            <h2><a class="edit_map" style="cursor:pointer;" data-id="<?php echo $map->id; ?>">Редактировать карту</a></h2>
                                            <h2><a class="map_markers" style="cursor:pointer;" data-id="<?php echo $map->id; ?>">Все маркеры</a></h2>
                                            <h2><a class="create_marker" style="cursor:pointer;" data-id="<?php echo $map->id; ?>">Создать маркер</a></h2>
                                            <h2><a class="ps_coll delete_this" href="<?php echo href('backend_maps_delete', array('id' => $map->id, 'hash' => md5('sss'.$map->id.'sss')));?>">Удалить карту</a></h2>
                                        <?php endif; ?>
                                    </div>
                                    <div style="width:74%;display: inline-block; vertical-align: top;height:900px;">
                                        <div id="map_conteiner" style="height:900px;"></div>
                                    </div>
                                </div>
                            </div>
				<div class="box">
					<div class="item_f_wrap">
                    	<div class="item_1">
							<label class="block title_1">
								H1:
							</label>
							<?php $public = isset($_POST['page_h1']) ? $_POST['page_h1'] : $object->page_h1 ;?>
							<input type="text" name="page_h1" class="input_1" value="<?php echo $public;?>">
                            <?php echo FORM::admin_error('page_h1',$errors);?>
						</div>
						<div class="item_1">
							<label class="block title_1">
								Заголовок страницы:
							</label>
							<?php $public = isset($_POST['page_title']) ? $_POST['page_title'] : $object->page_title ;?>
							<input type="text" name="page_title" class="input_1" value="<?php echo $public;?>">
                            <?php echo FORM::admin_error('page_title',$errors);?>
						</div>
						<div class="item_1">
							<label class="block title_1">
								Ключевые слова:
							</label>
							<?php $public = isset($_POST['page_keywords']) ? $_POST['page_keywords'] : $object->page_keywords ;?>
							<textarea name="page_keywords" rows="7" class="text_1"><?php echo $public;?></textarea>
                            <?php echo FORM::admin_error('page_keywords',$errors);?>
						</div>
						<div class="item_1">
							<label class="block title_1">
								Описания:
							</label>
							<?php $public = isset($_POST['page_description']) ? $_POST['page_description'] : $object->page_description ;?>
							<textarea rows="7" class="text_1" name="page_description"><?php echo $public;?></textarea>
                            <?php echo FORM::admin_error('page_description',$errors);?>
						</div>
					</div>
				</div>
			
            <div class="box">
					<div class="item_f_wrap">
                    	<div class="item_1">
							<label class="block title_1">
								Директория:
							</label>
							<?php $public = isset($_POST['folder']) ? $_POST['folder'] : $object->folder ;?>
							<input type="text" name="folder" class="input_1" value="<?php echo $public;?>" readonly="readonly" disabled="disabled">
                            <?php echo FORM::admin_error('folder',$errors);?>
						</div>
                        
                         <div class="item_col">
                            <label class="title_1">
                                Создать директорию для персональной страницы:
                            </label>
                            <?php $public = isset($_POST['personal']) ? $_POST['personal'] : $object->personal ;?>
                            <select name='personal'>
                                <option value="0" <?php if ($public==0) echo 'selected';?>>
                                    Нет
                                </option>
                                <option value="1" <?php if ($public==1) echo 'selected';?>>
                                    Да
                                </option>
                            </select>
                        </div>
                    </div>
            </div>
			
		</div>
		
			
			
			
			
		
		<div class="textright contorl_edit">
			<input class="b middle green" type="submit" name="save" value="Сохранить">
			<input class="b middle" type="submit" name="save_and_back" value="Сохранить и вернуться к списку">
			<input class="b middle red" type="submit" name="close" value="Закрыть">
			<input class="b middle orange" type="submit" name="save_and_go" value="Перейти на редактируемую страницу">
		</div>
		
	</form>
	
	
</div>
<script type="text/javascript" src="/js/backend/jquery.synctranslit.min.js"></script>
<script>
	$(document).ready(function(){
		$('#name').syncTranslit({destination: 'link', /*caseStyle: 'upper',*/ urlSeparator: '_'}); /*ondblclick*/
		
		$('.add_act').click(function(){ 
			$('#activites_list option:selected').each(function(el) { 
				$(this).appendTo('#activites'); 
			});
		});

		$('.del_act').click(function(){ 
			$('#activites option:selected').each(function(el){ 
				$(this).appendTo('#activites_list'); 
            });
		});
		
		$('.b').click(function(){
			$('#activites option').each(function(el) { 
				$(this).attr('selected','selected'); 
			});			
		})
                
            $('.delete_price').click(function(){
               var id = $(this).attr('data-id');
               
               $.ajax({
                   url:'/backend/ajax/delete_price_ent',
                   data:{id:id},
                   type: 'POST',
                   success: function(data){
                        $('#tr'+id).remove();
                   }
               });
               
               return false;
            });
            
            $('.op_album').click(function(){
               /* var id = $(this).attr('data-id');
                var action = $(this).attr('data-action');
                
                if(action == 1) {
                    $(this).text('Закрыть');
                    $(this).attr('data-action',0);
                    $('#gal-' + id).show();
                } else {
                     $(this).text('Фотографии');
                    $(this).attr('data-action',1);
                    $('#gal-' + id).hide();
                }*/
                $('#edit_album').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/' + $(this).attr('data-id') + '"></iframe>').appendTo('#edit_album');
            });
            
            $('.edit_album').click(function(){   
                $('#edit_album').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/' + $(this).attr('data-id') + '"></iframe>').appendTo('#edit_album');
            })
            
            $('.add_photo').click(function(){
                $('#edit_album').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/' + $(this).attr('data-id') + '"></iframe>').appendTo('#edit_album');
            });
            
            $('.create_album').click(function(){
                $('#edit_album').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/albums-inner-add-' + <?php echo $object->id; ?> + '"></iframe>').appendTo('#edit_album');
            })
            
            $('.create_map').click(function(){
                $('#map_conteiner').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/' + <?php echo $object->id; ?> + '-0"></iframe>').appendTo('#map_conteiner');
            })
            
            $('.edit_map').click(function(){
                $('#map_conteiner').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/' + <?php echo $object->id; ?> + '-' + $(this).attr('data-id') + '"></iframe>').appendTo('#map_conteiner');
            })
            
            $('.map_markers').click(function(){
                $('#map_conteiner').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/' + $(this).attr('data-id') + '"></iframe>').appendTo('#map_conteiner');
            })
            
            $('.create_marker').click(function(){
                $('#map_conteiner').empty();
                $('<iframe width="100%" height="100%" src="/images/jsuploader/' + $(this).attr('data-id') + '-0"></iframe>').appendTo('#map_conteiner');
            })
            
             $('.delete_map').click(function(){
                $('#map_conteiner').empty();
                
            })
	})
</script>
<script type="text/javascript">page_maps();</script>
<div id="dialog" style="display:none;"></div>
<div id="message_to_delete" style="display:none;" title="Удаление записи">
	<h2 align="center">Вы уверены?</h2>
</div>