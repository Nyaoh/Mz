<?php

/** Zend_Translate_Adapter */
require_once 'Zend/File/Transfer/Adapter/Http.php';
require_once 'Zend/File/Transfer/Adapter/Abstract.php';

/**
 * Adapter CSV
 *
 */
class Klee_Util_CustomTransferHttpAdapter extends Zend_File_Transfer_Adapter_Http 
{
	public function receive($files = null, $noPrefix = false, $validation = true) {
		if ($validation){
		    if (!$this->isValid($files)) {
	            return false;
	        }
		}

        $check = $this->_getFiles($files);
        foreach ($check as $file => $content) {
            if (!$content['received']) {
                $directory   = '';
                $destination = $this->getDestination($file);
                if ($destination !== null) {
                    $directory = $destination . DIRECTORY_SEPARATOR;
                }
				if ($noPrefix){
				    $content['name'] = $content['name'];
				} else {
                	$content['name'] = time() . '_' . $content['name'];
				}
				$filename = $directory . $content['name'];
                
				 $this->_files[$file]['name'] = basename($filename);
				
				$rename   = $this->getFilter('Rename');
                if ($rename !== null) {
                    $tmp = $rename->getNewName($content['tmp_name']);
                    if ($tmp != $content['tmp_name']) {
                        $filename = $tmp;
                    }

                    if (dirname($filename) == '.') {
                        $filename = $directory . $filename;
                    }

                    $key = array_search(get_class($rename), $this->_files[$file]['filters']);
                    unset($this->_files[$file]['filters'][$key]);
                }

                // Should never return false when it's tested by the upload validator
                if (!move_uploaded_file($content['tmp_name'], $filename)) {
                    if ($content['options']['ignoreNoFile']) {
                        $this->_files[$file]['received'] = true;
                        $this->_files[$file]['filtered'] = true;
                        continue;
                    }

                    $this->_files[$file]['received'] = false;
                    return false;
                }

                if ($rename !== null) {
                    $this->_files[$file]['destination'] = dirname($filename);
                    $this->_files[$file]['name']        = basename($filename);
                }

                $this->_files[$file]['tmp_name'] = $filename;
                $this->_files[$file]['received'] = true;
            }

            if (!$content['filtered']) {
                if (!$this->_filter($file)) {
                    $this->_files[$file]['filtered'] = false;
                    return false;
                }

                $this->_files[$file]['filtered'] = true;
            }
        }

        return true;
		
	}
	
	
	/**
	 * Internal method to detect the mime type of a file
	 *
	 * @param  array $value File infos
	 * @return string Mimetype of given file
	 */
	protected function _detectMimeType($value)
	{
		$tempMime = $value['type'];
		if (file_exists($value['name'])) {
			$file = $value['name'];
		} else if (file_exists($value['tmp_name'])) {
			$file = $value['tmp_name'];
		} else {
			return null;
		}
	
		if (class_exists('finfo', false)) {
			$const = defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME;
			if (!empty($value['options']['magicFile'])) {
				$mime = @finfo_open($const, $value['options']['magicFile']);
			}
	
			if (empty($mime)) {
				$mime = @finfo_open($const);
			}
	
			if (!empty($mime)) {
				$result = finfo_file($mime, $file);
			}
	
			unset($mime);
		}
	
		if (empty($result) && (function_exists('mime_content_type')
				&& ini_get('mime_magic.magicfile'))) {
			$result = mime_content_type($file);
		}
	
		if (empty($result)) {
			$result = 'application/octet-stream';
		}
		
		return $result;
	}
}
