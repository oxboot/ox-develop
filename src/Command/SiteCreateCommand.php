<?php
namespace Ox\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class SiteCreateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('site:create')
            ->setDescription('Creates a new site')
            ->setHelp('This command allows you to create a site')
            ->addArgument('site_name', InputArgument::REQUIRED, 'Name of the site')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $site_name = $input->getArgument('site_name');
        ox_echo_info('Try to create site ' . $site_name);
        $fs = new Filesystem();
        $site_dir = '/var/www/' . $site_name;
        $site_webdir = $site_dir . '/htdocs';
        if ($fs->exists($site_dir)) {
            ox_echo_error('Site ' . $site_name . ' already exists');
            return false;
        }
        ox_mkdir($site_webdir);
        $fs->dumpFile('/etc/nginx/sites-available/' . $site_name, ox_template('nginx/site', ['site_name' => $input->getArgument('site_name')]));
        $fs->symlink( '/etc/nginx/sites-available/' . $site_name, '/etc/nginx/sites-enabled/' . $site_name);
        $fs->dumpFile($site_webdir . '/index.php', ox_template('php/default', ['site_name' => $site_name]));
        if (!ox_console('nginx -t') || !$fs->exists([$site_dir, '/etc/nginx/sites-available/' . $input->getArgument('site_name'), '/etc/nginx/sites-enabled/' . $site_name])) {
            $fs->remove([$site_dir, '/etc/nginx/sites-available/' . $site_name, '/etc/nginx/sites-enabled/' . $site_name]);
            ox_echo_error('Site ' . $site_name . ' not created, error occurred and all changes will be restored');
            return false;
        }
        ox_chown($site_dir, 'www-data', 'www-data');
        ox_console('service nginx restart');
        $yaml = Yaml::dump(['site_name' => $site_name]);
        $fs->dumpFile('/etc/ox/sites/' . $site_name . '.yml', $yaml);
        ox_echo_success('Site ' . $site_name . ' created successful');
        return true;
    }
}
