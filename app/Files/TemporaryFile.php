<?php

/*
  $tm = new TemporaryFile($directoryPath);
  $tempPath = $tm->addFile($filePath);
  unset($tm); //delete file
 */
namespace App\Files;

use Symfony\Component\Filesystem\Filesystem;

class TemporaryFile
{
    private $filesystem;
    private $root;
    private $files = [];
    public function __construct($tempDir, $directoryMode = 0700)
    {
        $this->root = $tempDir;
        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir($tempDir, $directoryMode);
    }
    public function __destruct()
    {
        $this->filesystem->remove($this->files);
    }
    public function add($contents)
    {
        $path = $this->path();
        $this->filesystem->dumpFile($path, $contents);
        $this->files[] = $path;
        return $path;
    }
    public function addFile($path)
    {
        return $this->add(file_get_contents($path));
    }

    private function path()
    {
        return $this->root . DIRECTORY_SEPARATOR . uniqid();
    }
  }
