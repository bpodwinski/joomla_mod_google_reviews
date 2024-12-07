<?php

namespace Bp\Module\GoogleReviews\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Bp\Module\GoogleReviews\Site\Helper\GoogleReviewsHelper;

class Dispatcher implements DispatcherInterface
{
    protected $module;
    protected $app;
    protected $params;

    public function __construct(\stdClass $module, CMSApplicationInterface $app, Input $input, Registry $params = null)
    {
        $this->module = $module;
        $this->app = $app;
        $this->params = $params ?? new Registry($module->params);
    }

    public function dispatch()
    {
        $language = Factory::getApplication()->getLanguage();
        $language->load('mod_google_reviews', JPATH_BASE . '/modules/mod_google_reviews');

        $reviews = GoogleReviewsHelper::getGoogleReviews($this->params);

        $module = $this->module;
        $params = $this->params;
        require ModuleHelper::getLayoutPath('mod_google_reviews', $params->get('layout', 'default'));
    }
}
