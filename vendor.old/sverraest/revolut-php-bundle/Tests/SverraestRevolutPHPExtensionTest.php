<?php

namespace Sverraest\RevolutPHPBundle\Tests;

use PHPUnit\Framework\TestCase;
use RevolutPHP\Client;
use Sverraest\RevolutPHPBundle\DependencyInjection\SverraestRevolutPHPExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class SverraestRevolutPHPExtensionTest extends TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testBundleLoadThrowsExceptionUnlessApiKeyIsSet()
    {
        $loader = new SverraestRevolutPHPExtension();
        $config = $this->getEmptyConfig();
        unset($config['api_key']);
        $loader->load([$config], new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testBundleLoadThrowsExceptionUnlessModeIsSet()
    {
        $loader = new SverraestRevolutPHPExtension();
        $config = $this->getEmptyConfig();
        unset($config['mode']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testServiceIsInstanceOfRevolutPHP()
    {
        $loader = new SverraestRevolutPHPExtension();
        $config = $this->getEmptyConfig();
        $loader->load([$config], new ContainerBuilder());
        $this->assertInstanceOf(Client::class, new Client($config['api_key'], $config['mode']));
    }

    /**
     * @return mixed
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
api_key: foo
mode: sandbox
EOF;
        $parser = new Parser();
        return $parser->parse($yaml);
    }
}
