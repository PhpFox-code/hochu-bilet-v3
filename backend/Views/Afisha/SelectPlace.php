<select name="FORM[place_id]" id="place">
    <?php foreach ($result as $key => $obj): ?>
        <option value="<?php echo $obj->id ?>" <?php if (isset($current) AND $current == $obj->id) echo 'selected' ?>><?php echo $obj->name ?></option>
    <?php endforeach ?>
</select>