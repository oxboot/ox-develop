<?php
namespace Ox\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SiteDeleteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('site:delete')
            ->setDescription('Delete an existing site')
            ->setHelp('This command allows you to delete an existing site')
            ->addArgument('site_name', InputArgument::REQUIRED, 'Name of the site')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ox_echo_info('Try to delete site ' . $input->getArgument('site_name'));
        $fs = new Filesystem();
        $site_dir = '/var/www/' . $input->getArgument('site_name');
        $site_webdir = $site_dir . '/htdocs';
        if (!$fs->exists($site_dir)) {
            ox_echo_error('Site ' . $input->getArgument('site_name') . ' not exists');
            return false;
        }
        $fs->remove([$site_dir, '/etc/nginx/sites-available/' . $input->getArgument('site_name'), '/etc/nginx/sites-enabled/' . $input->getArgument('site_name')]);
        ox_console('service nginx restart');
        ox_echo_success('Site ' . $input->getArgument('site_name') . ' deleted successful');
        return true;
    }
}
