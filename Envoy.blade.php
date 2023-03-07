@include('vendor/autoload.php')

@servers(['local' => '127.0.0.1', 'web' => 'deployer@83.222.11.191'])

@setup

    $env = isset($env) ? $env : 'local';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.deploy.' . $env);
    $dotenv->load();

    $releaseRotate = 4;
    $gitBranch = 'main';
    $gitRepository = $_ENV['DEPLOY_REPOSITORY'];
    $currentReleaseHash = md5(time());

    $dirBase = $_ENV['DEPLOY_BASE_DIR'] . $gitBranch;
    $dirReleases = $dirBase . '/releases';
    $dirShared = $dirBase . '/shared';
    $dirCurrent = $dirBase . '/current';
    $dirCurrentRelease = $dirReleases . '/' . $currentReleaseHash
@endsetup

@story('deploy', ['on' => ['local']])
    git_clone
    composer
    npm
    config_project
    migrate
    queue
    releases_clean
    set_current
@endstory

@story('deploy-prod', ['on' => ['web']])
    git_clone
    composer
    npm
    config_project
    migrate
    queue
    releases_clean
    set_current
@endstory

@task('git_clone')
    echo "# Git clone"
    mkdir -p {{$dirCurrentRelease}}
    git clone --depth 1 -b {{ $gitBranch }} {{ $gitRepository }} {{ $dirCurrentRelease }}
@endtask

@task('composer')
    echo "# Composer install"
    cd {{$dirCurrentRelease}}
    composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader
@endtask

@task('npm')
    echo "# Npm install"
    cd {{$dirCurrentRelease}}
    yarn install --no-progress
    yarn build
@endtask

@task('config_project')
    echo "# Linking storage directory";
    cd {{$dirCurrentRelease}};
    rm -rf {{$dirCurrentRelease}}/storage/app;
    cd {{$dirCurrentRelease}};
    ln -nfs {{$dirShared}}/storage/app storage/app;
    php artisan storage:link;

    echo "# Linking .env file";
    cd {{$dirCurrentRelease}};
    ln -nfs {{$dirBase}}/.env .env;

    echo "# Change group modes"
    chgrp -R www-data {{$dirShared}};
    chgrp -R www-data {{$dirCurrentRelease}};
    chmod -R ug+rwx {{$dirShared}};
    chmod -R ug+rwx {{$dirCurrentRelease}};

    echo "# Optimising installation";
    php artisan clear-compiled --env={{$env}};
    php artisan optimize --env={{$env}};
    php artisan config:cache --env={{$env}};
    php artisan route:cache --env={{$env}};
    php artisan view:cache --env={{$env}};
    php artisan cache:clear --env={{$env}};
@endtask

@task('migrate')
    echo "# Migrate changes"
    cd {{$dirCurrentRelease}}
    php artisan migrate --force
@endtask

@task('queue')
    echo "# Restart queue"
    cd {{$dirCurrentRelease}}
    php artisan queue:restart
@endtask

@task('releases_clean')
    purging=$(ls -dt {{$dirReleases}}/* | tail -n +{{$releaseRotate}});

    if [ "$purging" != "" ]; then
        echo "# Purging old releases: $purging;"
        rm -rf $purging;
    else
        echo "# No releases found for purging at this time";
    fi
@endtask

@task('set_current')
    echo '# Linking current release';
    ln -nfs {{$dirCurrentRelease}} {{$dirCurrent}};
@endtask

@error
    shell_exec("rm -rf " . $dirCurrentRelease);
    @telegram($_ENV['TELEGRAM_BOT_ID'], $_ENV['TELEGRAM_CHAT_ID'], 'BUILD ERROR: ' . shell_exec('git log -1 --pretty=%B'));
@enderror

@success
    @telegram($_ENV['TELEGRAM_BOT_ID'], $_ENV['TELEGRAM_CHAT_ID'], 'SUCCESS BUILD: ' . shell_exec('git log -1 --pretty=%B'))
@endsuccess
