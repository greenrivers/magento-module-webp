<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Unexpected\Webp\Helper\Process;

class Convert extends Command
{
    const EXTENSIONS_OPTION = 'ext';
    const FOLDERS_OPTION = 'dir';

    /** @var Process */
    private $process;

    /**
     * Convert constructor.
     * @param Process $process
     * @param string|null $name
     */
    public function __construct(Process $process, string $name = null)
    {
        parent::__construct($name);

        $this->process = $process;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('unexpected:webp:convert');
        $this->setDescription('Convert images to webp format');
        $this->addOption(
            self::EXTENSIONS_OPTION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Image extensions i.e.: --ext=jpg,jpeg,png'
        );
        $this->addOption(
            self::FOLDERS_OPTION,
            null,
            InputOption::VALUE_OPTIONAL,
            'Folders to conversion i.e.: --dir=catalog,wysiwyg'
        );

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $extOption = $input->getOption(self::EXTENSIONS_OPTION) ?: 'jpg,jpeg,png';
        $dirOption = $input->getOption(self::FOLDERS_OPTION) ?: 'root';

        $extensions = explode(',', $extOption);
        $folders = explode(',', $dirOption);

        array_walk($extensions, function(&$extension) {
           $extension = '*.' . $extension;
        });

        $images = $this->process->getImages($extensions, $folders);
        $imagesToConversion = $this->process->getImagesToConversion($images);

        $progressBar = new ProgressBar($output, count($imagesToConversion));
        $progressBar->start();

        $convertData = $this->process->convert($imagesToConversion, true, $progressBar);
        $convertedImages = $convertData['converted_images'];
        $errorImages = $convertData['error_images'];

        $progressBar->finish();
        $output->writeln("\n<info>Converted images: ${convertedImages}</info>");
        $output->writeln("\n<error>Error images: ${errorImages}</error>");
    }
}
