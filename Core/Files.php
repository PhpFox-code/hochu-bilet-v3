<?php
    namespace Core;

    use \Core\Image\Image;
    
    class Files {

        /**
         *  Upload image
         *  @param string $mainFolder - name of th block in Config/images.php
         *  @return string            - filename
         */
        public static function uploadImage($mainFolder) {
            if( !Arr::get( $_FILES['file'], 'name' ) ) {
                return false;
            }
            $need = Config::get('images.'.$mainFolder);
            if( !$need ) {
                return false;
            }
            $ext = end( explode('.', $_FILES['file']['name']) );
            $filename = md5($_FILES['file']['name'].'_'.$mainFolder.time()).'.'.$ext;
            foreach( $need AS $one ) {
                $path = HOST.HTML::media('/images/'.$mainFolder.'/'.Arr::get($one, 'path'));
                Files::createFolder($path, '0777');
                $file = $path.'/'.$filename;
                $image = Image::factory($_FILES['file']['tmp_name']);
                if( Arr::get($one, 'resize') ){
                    $image->resize(Arr::get($one, 'width'), Arr::get($one, 'height'), Image::INVERSE);
                }
                if( Arr::get($one, 'crop') ){
                    $image->crop(Arr::get($one, 'width'), Arr::get($one, 'height'));
                }
                $image->save($file);
            }
            return $filename;
        }


        /**
         *  Delete image
         *  @param string $mainFolder - name of th block in Config/images.php
         *  @param string $filename   - name of the file we delete
         *  @return bool
         */
        public static function deleteImage($mainFolder, $filename) {
            $need = Config::get('images.'.$mainFolder);
            if( !$need ) {
                return false;
            }
            foreach( $need AS $one ) {
                $file = HOST.HTML::media('/images/'.$mainFolder.'/'.Arr::get($one, 'path').'/'.$filename);
                @unlink($file);
            }
            return true;
        }


        /**
         *  Create folder with some rights (544 as default)
         *  @param string $path   - path to the dir
         *  @param string $rights - rights for the folder
         *  @return bool
         */
        public static function createFolder($path, $rights = '0544') {
            if( file_exists($path) AND is_dir($path) ){
                return true;
            }
            return (boolean) mkdir($path, $rights);
        }

    }