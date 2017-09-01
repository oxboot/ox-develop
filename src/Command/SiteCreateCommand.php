<?php
namespace Ox\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Symfony\Component\Filesystem\Filesystem;

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
        ox_echo_info('Try to create site ' . $input->getArgument('site_name'));
        $fs = new Filesystem();
        $site_dir = '/var/www/' . $input->getArgument('site_name');
        $site_webdir = $site_dir . '/htdocs';
        if ($fs->exists($site_dir)) {
            ox_echo_error('Site ' . $input->getArgument('site_name') . ' already exists');
            return false;
        }
        ox_mkdir($site_webdir);
        $fs->dumpFile($site_webdir . '/index.php', '<?php phpinfo();');
        $m = new Mustache_Engine(['loader' => new Mustache_Loader_FilesystemLoader(OX_DIR . '/../templates')]);
        $site_template =  $m->render('site', ['site_name' => $input->getArgument('site_name')]);
        $fs->dumpFile('/etc/nginx/sites-available/' . $input->getArgument('site_name'), $site_template);
        $fs->symlink( '/etc/nginx/sites-available/' . $input->getArgument('site_name'), '/etc/nginx/sites-enabled/' . $input->getArgument('site_name'));
        if (!ox_console('nginx -t')) {
            $fs->remove([$site_dir, '/etc/nginx/sites-available/' . $input->getArgument('site_name'), '/etc/nginx/sites-enabled/' . $input->getArgument('site_name')]);
            ox_echo_error('Site ' . $input->getArgument('site_name') . ' not created, Nginx configuration error occurred and all changes restored');
            return false;
        }
        ox_chown($site_dir, 'www-data', 'www-data');
        ox_console('service nginx restart');
        ox_echo_success('Site ' . $input->getArgument('site_name') . ' created successful');
        return true;
    }
}
