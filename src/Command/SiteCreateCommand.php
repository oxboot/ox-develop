<?php
namespace Ox\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class SiteCreateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('site:create')
            ->setDescription('Creates a new site.')
            ->setHelp('This command allows you to create a site.')
            ->addArgument('site_name', InputArgument::REQUIRED, 'Name of the site')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ox_echo_info('Create site with name = ' . $input->getArgument('site_name'));
        $fs = new Filesystem();
        $m = new Mustache_Engine(['loader' => new Mustache_Loader_FilesystemLoader(OX_DIR . '/../templates')]);
        $fs->mkdir('/var/www/' . $input->getArgument('site_name') . '/htdocs/');
        $site_template =  $m->render('site', ['site_name' => $input->getArgument('site_name')]);
        $fs->dumpFile('/etc/nginx/sites-available/' . $input->getArgument('site_name'), $site_template);
        $fs->symlink( '/etc/nginx/sites-available/' . $input->getArgument('site_name'), '/etc/nginx/sites-enabled/' . $input->getArgument('site_name'));
        ox_console('service nginx restart');
    }
}
