<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODDropzone
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * addFilter($name, $filter)
 * rmFilter($name)
 * setFilter($name, $filter)
 * getFilter($name)
 * getFilters()
 * enaMultiple()
 * disMultiple()
 * getMultiple
 * setMaxFiles($number)
 * getMaxFiles()
 * setPathSave($path)
 * getPathSave()
 * addFile($name, $pathFile)
 * removeFile($name)
 * setFiles(array $files)
 * getFiles()
 * clearFiles()
 * getUploadFiles()
 * clearUplaodFiles()
 */
class ODDropzone extends ODContained
{
    public function __construct($id) {
        parent::__construct($id, "oobject/odcontained/oddropzone/oddropzone.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        return $this;
    }

    public function addFilter($name, $filter)
    {
        $name   = (string) $name;
        $filter = (string) $filter;
        $properties = $this->getProperties();
        $filters    = (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();

        if (!array_key_exists($name, $filters)) {
            $filters[$name] = $filter;

            $properties['filters'] = $filters;
            $this->setProperties( $properties );
            return true;
        } else {
            return false;
        }
    }

    public function rmFilter($name)
    {
        $name       = (string) $name;
        $properties = $this->getProperties();
        $filters    = (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();

        if (!array_key_exists($name, $filters)) {
            unset($filters[$name]);

            $properties['filters'] = $filters;
            $this->setProperties( $properties );
            return true;
        } else {
            return false;
        }
    }

    public function setFilter($name, $filter)
    {
        $name   = (string) $name;
        $filter = (string) $filter;
        $properties = $this->getProperties();
        $filters    = (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();

        $filters[$name] = $filter;

        $properties['filters'] = $filters;
        $this->setProperties( $properties );
        return true;
    }

    public function setFilters(array $filters)
    {
        $properties = $this->getProperties();
        $properties['filters'] = $filters;
        $this->setProperties($properties);
        return $this;
    }

    public function getFilter($name)
    {
        $name   = (string) $name;
        $properties = $this->getProperties();

        if (array_key_exists($name, $properties['filters'])) {
            return $properties['filters'][$name];
        }
        return false;
    }

    public function getFilters()
    {
        $properties = $this->getProperties();
        return (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();
    }

    public function enaMultiple()
    {
        $properties = $this->getProperties();
        $properties['multiple'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disMultiple()
    {
        $properties = $this->getProperties();
        $properties['multiple'] = false;
        $this->setProperties($properties);
        return $this;
    }
    
    public function getMultiple() 
    {
        $properties = $this->getProperties();
        return (isset($properties['multiple']) && !empty($properties['multiple'])) ? $properties['multiple'] : array();
    }
    
    public function setMaxFiles($number)
    {
        $number = (int) $number;
        if ($this->getMultiple() and $number > 1) {
            $properties = $this->getProperties();
            $properties['maxFiles'] = $number;
            $this->setProperties($properties);
            return $this;
        }
        return FALSE;
    }
    
    public function getMaxFiles() 
    {
        $properties = $this->getProperties();
        return (isset($properties['maxFiles']) && !empty($properties['maxFiles'])) ? $properties['maxFiles'] : array();
    }
    
    public function addFile($name, $pathFile)
    {
        $name = (string) $name;
        $pathFile = (string) $pathFile;
        
        $properties = $this->getProperties();
        if (!array_key_exists('servFiles', $properties)) {
            $properties['servFiles'] = [];
        }
        $item = [];
        $item['name'] = $name;
        $item['type'] = "";
        $item['size'] = filesize($pathFile);
        $item['path'] = $pathFile;
        $properties['servFiles'][$name] = $item;
        
        $this->setProperties($properties);
        return $this;
    }
    
    public function setPathSave($path)
    {
        $path = (string) $path;
        $properties = $this->getProperties();
        $properties['pathSave'] = $path;
        $this->setProperties($properties);
        return $this;
    }
    
    public function getPathSave()
    {
        $properties = $this->getProperties();
        return (isset($properties['pathSave']) && !empty($properties['pathSave'])) ? $properties['pathSave'] : array();
    }

        public function removeFile($name)
    {
        $name = (string) $name;
        $servFiles = [];
        
        $properties = $this->getProperties();
        if (!array_key_exists('servFiles', $properties)) {
            $servFiles = $properties['servFiles'];
        }
        
        if (array_key_exists($name, $servFiles)) {
            unset($servFiles[$name]);
            $properties['servFiles'] = $servFiles;
            $this->setProperties($properties);
            return $this;
        }
        return false; 
    }
    
    public function setFiles(array $files)
    {
        $properties = $this->getProperties();
        foreach ($files as $name => $file) {
            $properties['servFiles'][$name] = $file;
        }
        $this->setProperties($properties);
        return $this;
    }
    
    public function getFiles()
    {
        $properties = $this->getProperties();
        return (isset($properties['servFiles']) && !empty($properties['servFiles'])) ? $properties['servFiles'] : array();
    }
    
    public function clearFiles()
    {
        $properties = $this->getProperties();
        $properties['servFiles'] = [];
        $this->setProperties($properties);
        return $this;
    }
    
    public function getUploadFiles()
    {
        $properties = $this->getProperties();
        return (isset($properties['loadFiles']) && !empty($properties['loadFiles'])) ? $properties['loadFiles'] : array();
    }
    
    public function clearUploadFiles()
    {
        $properties = $this->getProperties();
        $properties['loadFiles'] = [];
        $this->setProperties($properties);
        return $this;
    }
    
    public function addUploadFiles()
    {
        $properties = $this->getProperties();
        $uploadedFiles = $this->getUploadFiles();
        foreach ($uploadedFiles as $key => $uploadedFile) {
            $name = $uploadedFile['name'];
            $path = $uploadedFile['tmp_name'];
            $size = $uploadedFile['size'];
            $type = $uploadedFile['type'];
            $err  = $uploadedFile['error'];
            
            if ($err == '0') {
                if ($key != 'fileName') {
                    $fileContent  = file_get_contents($path);
                    
                    $item['name'] = $name;
                    $item['type'] = $type;
                    $item['size'] = $size;
                    $item['path'] = $path;
                    $properties['servFiles'][$name] = $item;
                    
                    unset($properties['loadFiles'][$key]);
                }
            }
        }
    }
}