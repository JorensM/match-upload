<?php

    require_once("abstract/AbstractFileManager.php");

    class UploadsManager extends AbstractFileManager{

        /**
         * Manages files uploaded by POST
         */

        public function validateFile(string $name, array $params = null){
            $file_exists = array_key_exists($name, $_FILES);
            if(!$file_exists){
                $e_message = "Could not find file";
                error(new MyException($e_message, $this, __METHOD__));
                //throw new MyException($e_message, $this, __METHOD__);
            }
            if($file_exists){
                if ($_FILES[$name]['error'] === UPLOAD_ERR_OK) {
                    if($params){
                        if(array_key_exists("ext", $params)){
                            $filename = $_FILES[$name]["name"];
                            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                            $ext_valid = in_array($ext, $params["ext"]);

                            if(!$ext_valid){
                                $e_message = "Invalid filetype: $ext given, expected " . implode("/",$params["ext"]);
                                //error(new MyException($e_message, $this, __METHOD__));
                                throw new MyException($e_message, $this, __METHOD__);
                            }
                        }
                    }
                    return true;
                } else {
                    $e_message = "Upload failed with error code " . $_FILES[$name]['error'];

                    error(new MyException($e_message, $this, __METHOD__));

                    //throw new MyException($e_message, $this, __METHOD__);
                }
            }
        }

        public function openFile(string $name, string $mode = "r"){
            $file_handle = fopen($_FILES[$name]["tmp_name"], $mode);

            return $file_handle;
        }
        
    }