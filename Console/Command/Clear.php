<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GreenRivers\Webp\Helper\Process;

class Clear extends Command
{
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
        $this->setName('greenrivers:webp:clear');
        $this->setDescription('Remove all webp images');

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $images = $this->process->getImages();
        $progressBar = new ProgressBar($output, $images->count());
        $progressBar->start();

        $removedImages = $this->process->clear($images, true, $progressBar);

        $progressBar->finish();
        $output->writeln("\n<info>Removed images: ${removedImages}</info>");
    }
}