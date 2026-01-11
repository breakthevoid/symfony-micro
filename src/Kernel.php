<?php

namespace App;

use App\DependencyInjection\AppExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;


class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    
    /**
     * registerBundles - регистрирует бандлы
     *
     * @return iterable
     */
    public function registerBundles() : iterable
    {
        // Регистрируется по дефолту
        yield new FrameworkBundle();
        // Регистрация обработчика шаблонов
        yield new TwigBundle();

        // Регистрация веб-профайлера
        if ('dev' === $this->getEnvironment()) {
            yield new WebProfilerBundle();
        }
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->registerExtension(new AppExtension());
    }

        
    /**
     * configureContainer - собирает и конфигурирует контейнер
     *
     * @param  mixed $container
     * @return void
     */
    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__.'/../config/framework.yaml');

        // register all classes in /src/ as service
        $container->services()
            ->load('App\\', __DIR__.'/*')
            ->autowire()
            ->autoconfigure()
        ;

        // configure WebProfilerBundle only if the bundle is enabled
        if (isset($this->bundles['WebProfilerBundle'])) {
            $container->extension('web_profiler', [
                'toolbar' => true,
                'intercept_redirects' => false,
            ]);
        }
    }


    /**
     * randomNumber - задание контроллера для роута
     *
     * @param  mixed $limit
     * @return JsonResponse
     */
    
    #[Route('/random/{limit}', name: 'random_number')]    
    public function randomNumber(int $limit): JsonResponse
    {
        return new JsonResponse([
            'number' => random_int(0, $limit),
        ]);
    }


    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        // импорт роутов Web-профайлера, только если бандл включен
        if (isset($this->bundles['WebProfilerBundle'])) {
            $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.php', 'php')->prefix('/_wdt');
            $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.php', 'php')->prefix('/_profiler');
        }

        // загрузка роутов, определенных как PHP-атрибуты
        $routes->import(__DIR__.'/Controller/', 'attribute');
    }

    // optionally, you can define the getCacheDir() and getLogDir() methods
    // to override the default locations for these directories
}
