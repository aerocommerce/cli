<?php

namespace Aero\Cli;

use ZipArchive;

trait ZipManager
{
    /**
     * Extract the zip file into the given directory.
     *
     * @param  string $zipFile
     * @return $this
     */
    protected function extract($zipFile)
    {
        $this->output->write('Extracting zip...');

        $archive = new ZipArchive;

        $archive->open($zipFile);

        $archive->extractTo($this->directory.'_tmp');

        $archive->close();

        $this->output->writeln(' <info>[✔]</info>');

        return $this;
    }

    /**
     * Clean-up the Zip file.
     *
     * @param  string $zipFile
     * @return $this
     */
    protected function cleanUp($zipFile)
    {
        $this->output->write('Cleaning up...');

        foreach (scandir($this->directory.'_tmp', SCANDIR_SORT_DESCENDING) as $dir) {
            @rename($this->directory.'_tmp/'.$dir, $this->directory);
            break;
        }

        @rmdir($this->directory.'_tmp');

        @chmod($zipFile, 0777);

        @unlink($zipFile);

        $this->output->writeln(' <info>[✔]</info>');

        return $this;
    }
}
