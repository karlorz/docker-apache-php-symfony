<?php
// src/Command/ExportRoutesCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:export-routes',
    description: 'Exports routes for Cypress, excluding specific routes like the Symfony debug Profiler.',
)]
class ExportRoutesCommand extends Command
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $routes = $this->router->getRouteCollection();
        $export = [];

        foreach ($routes as $name => $route) {
            // Skip certain routes based on name or path
            if ($name === '_profiler_router' || preg_match('/^\/_profiler\//', $route->getPath())) {
                continue; // Skip adding this route
            }
            if ($name === '_preview_error' || preg_match('/^\/_error\//', $route->getPath())) {
                continue; // Skip adding this route
            }
            if ($name === '_wdt' || preg_match('/^\/_wdt\//', $route->getPath())) {
                continue; // Skip adding this route
            }

            $methods = $route->getMethods(); // Fetch HTTP methods for the route

            // Include HTTP methods in the export
            $export[] = [
                'name' => $name,
                'path' => $route->getPath(),
                'methods' => $methods
            ];
        }

        file_put_contents('cypress_routes.json', json_encode($export, JSON_PRETTY_PRINT));
        $output->writeln('Routes exported successfully, including HTTP methods, excluding specific routes.');
        return Command::SUCCESS;
    }
}