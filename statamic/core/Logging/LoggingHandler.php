<?php

namespace Statamic\Logging;

use Monolog\Logger;
use Statamic\API\Str;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\HipChatHandler;
use Monolog\Handler\FlowdockHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FleepHookHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\BrowserConsoleHandler;

class LoggingHandler
{
    /**
     * The available logging handlers and their respective load methods
     *
     * @var array
     */
    public $available_loggers = [
        'browser'         => 'registerBrowserConsoleHandler',
        'email'           => 'registerNativeMailerHandler',
        'file'            => 'registerStreamHandler',
        'fleep'           => 'registerFleepHandler',
        'flowdock'        => 'registerFlowdockHandler',
        'hipchat'         => 'registerHipChatHandler',
        'slack'           => 'registerSlackHandler'
    ];

    public function __construct($monolog)
    {
        $this->monolog = $monolog;

        $this->registerHandlers();
    }

    public function registerHandlers()
    {
        $config = array_merge(
            array_get($GLOBALS, 'statamic_debug_config.defaults'),
            array_get($GLOBALS, 'statamic_debug_config.settings')
        );

        $handlers = array_get($config, 'loggers');

        foreach ((array) $handlers as $handler => $config) {
            $this->addHandler($handler, $config);
        }
    }

    /**
     * Find and create a valid Monolog handler.
     *
     * @param $handler
     * @param $config
     * @return object
     * @throws \Exception
     */
    public function addHandler($handler, $config)
    {
        if (array_key_exists($handler, $this->available_loggers)) {
            $method = $this->available_loggers[$handler];
            $this->monolog->pushHandler($this->$method($config));
        } else {
            throw new \Exception(
                sprintf('%s is not a valid Logging Handler', $handler)
            );
        }
    }

    /**
     * Sends logging events through the Slack api to a hipchat room
     *
     * @return object
     */
    protected function registerSlackHandler($config)
    {
        return (new SlackHandler(
            array_get($config, 'token'),
            array_get($config, 'channel'),
            array_get($config, 'username', 'StagBot'),
            array_get($config, 'use_attachment', true),
            array_get($config, 'icon_emoji'),
            array_get($config, 'level', Logger::CRITICAL),
            array_get($config, 'bubble', true),
            array_get($config, 'use_short_attachment', true)
        ));
    }

    /**
     * Sends logging events through the Slack API
     *
     * @return object
     */
    protected function registerHipChatHandler($config)
    {
        return (new HipChatHandler(
            array_get($config, 'token'),
            array_get($config, 'room'),
            array_get($config, 'name', 'StagBot'),
            array_get($config, 'notify', false),
            array_get($config, 'level', Logger::CRITICAL),
            array_get($config, 'bubble', true),
            array_get($config, 'use_ssl', true),
            array_get($config, 'format', 'text'),
            array_get($config, 'host', 'api.hipchat.com')
        ));
    }

    /**
     * Sends logging events through the Flowdock API
     *
     * @return object
     */
    protected function registerFlowdockHandler($config)
    {
        return (new FlowdockHandler(
            array_get($config, 'token'),
            array_get($config, 'level', Logger::CRITICAL),
            array_get($config, 'bubble', true)
        ));
    }

    /**
     * Sends logging events using PHP's mail() function
     *
     * @return object
     */
    protected function registerNativeMailerHandler($config)
    {
        return (new NativeMailerHandler(
            array_get($config, 'to'),
            array_get($config, 'subject'),
            array_get($config, 'from'),
            array_get($config, 'level', Logger::ERROR),
            array_get($config, 'bubble', true),
            array_get($config, 'max_column_width', 70)
        ));
    }

    /**
     * Sends logging events to Fleep.io
     *
     * @return object
     */
    protected function registerFleepHandler($config)
    {
        return (new FleepHookHandler(
            array_get($config, 'token'),
            array_get($config, 'level', Logger::ERROR),
            array_get($config, 'bubble', true)
        ));
    }

    /**
     * Sending logging events to browser's javascript console
     *
     * @return object
     */
    protected function registerBrowserConsoleHandler($config)
    {
        return (new BrowserConsoleHandler());
    }

    /**
     * Writes logging events to any stream resource
     *
     * @return object
     */
    protected function registerStreamHandler($config)
    {
        $path = Str::ensureRight(array_get($config, 'path', storage_path('logs')), '/');
        $path .= 'statamic';

        if (array_get($config, 'daily')) {
            $path .= '-' . date('Y-m-d');
        }

        return (new StreamHandler(
            $path.'.log',
            array_get($config, 'level', Logger::DEBUG)
        ))->setFormatter(new LineFormatter(null, null, true, true));
    }
}
