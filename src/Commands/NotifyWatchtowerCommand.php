<?php

namespace Wlhrtr\Watchtower\Commands;

use Illuminate\Console\Command;
use Wlhrtr\Watchtower\Services\WatchtowerService;

class NotifyWatchtowerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watchtower:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send composer information to Watchtower Server';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(WatchtowerService $watchtowerService)
    {
        $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);
        $packages = array_keys($composerJson['require']);

        $installedPackages = collect($packages)
            ->filter(fn ($package) => $package !== 'php')
            ->map(fn ($package) => [
                'name' => $package,
                'version' => \Composer\InstalledVersions::getPrettyVersion($package)
            ])->values();

        $data = [
            'environment' => config('watchtower.environment'),
            'packages' => $installedPackages
        ];

        $watchtowerService->send($data);

        return 0;
    }
}
