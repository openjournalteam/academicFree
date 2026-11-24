<?php

 /**
 * @file plugins/themes/academicFree/AcademicFreeThemePlugin.inc.php
 *
 * Copyright (c) 2024 openjournaltheme.com
 * Distributed under the GNU GPL v3. For full terms see the file README.
 * No warranty is provided.
 */

import('lib.pkp.classes.plugins.ThemePlugin');
class AcademicFreeThemePlugin extends ThemePlugin
{

    public $pluginDir;

    public function __construct()
    {
        $this->pluginDir = $this->getPluginPath();
    }

    function register($category, $path, $mainContextId = null)
    {
        if (!parent::register($category, $path, $mainContextId)) return false;

        // Don't perform any futher operations if theme is not currently active
        if (!$this->isActive()) {
            return true;
        }
        HookRegistry::register('TemplateManager::display', array($this, 'loadTemplateData'));
        HookRegistry::register('TemplateManager::display', [$this, 'addCanonicalLinkHeader']);
        HookRegistry::register('TemplateManager::display', [$this, 'addHeaderMeta']);

        return true;
    }

    public function addHeaderMeta($hookName, $args)
    {
        $templateMgr            = &$args[0];
        $templateMgr->addHeader(
            $this->getName(),
            '<meta name="' . $this->getName() . '" content="' . $this->getDisplayName() . ' Version ' . $this->getPluginVersion() . ' by openjournaltheme.com">',
            [
                'contexts' => ['frontend'],
            ]
        );
    }

    public function getPluginVersion()
    {
        import('lib.pkp.classes.site.VersionCheck');
        $version = VersionCheck::parseVersionXML($this->getPluginVersionFile());

        return $version['release'];
    }

    public function getPluginVersionFile()
    {
        return $this->getPluginPath() . '/version.xml';
    }


    /**
     * Initialize the theme
     *
     * @return null
     */
    public function init()
    {
        // Register option for bootstrap themes
        $this->addOption('bootstrapTheme', 'radio', array(
            'label'       => 'plugins.themes.academic_free.options.academicFree.label',
            'description' => 'plugins.themes.academic_free.options.academicFree.description',
            'options'     => array(
                'yeti' => 'plugins.themes.academic_free.options.academicFree.yeti',
            ),
        ));

        //header option
        $this->addOption('headerTheme', 'radio', array(
            'label'       => 'plugins.themes.academic_free.headerTheme.name',
            'description' => 'plugins.themes.academic_free.headerTheme.description',
            'options'     => array(
                'red'     => 'plugins.themes.academic_free.headerTheme.red',
                'edu'     => 'plugins.themes.academic_free.headerTheme.edu',
                'dots'    => 'plugins.themes.academic_free.headerTheme.dots',
                'diamond' => 'plugins.themes.academic_free.headerTheme.diamond',
                'blue'    => 'plugins.themes.academic_free.headerTheme.blue',

            ),
        ));

        //logo feature
        // Show or hide the statistic
        $this->addOption('logoStyleBig', 'FieldOptions', array(
            'label'       => __('plugins.themes.academic_free.logo_style') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.logo_style_description'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_pro.enable'),
                ],

            ],
        ));

        // Add theme options
        $this->addOption('baseColour', 'FieldColor', array(
            'label'       => __('plugins.themes.academic_free.theme_color') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.theme_color_desc'),
            'default'     => '#8a0493',
        ));

        // Show or hide the statistic
        $this->addOption('statistics', 'FieldOptions', [
            'label'       => __('plugins.themes.academic_free.stat_label') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.stat.desc'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_free.enable'),
                ],

            ],
            'default'     => true,
        ]);

        // Show or hide the article tracking date
        $this->addOption('article_tracking_date', 'FieldOptions', [
            'label'       => __('plugins.themes.academic_free.article_tracking_date') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.article_tracking_date.desc'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_free.enable'),
                ],

            ],
            'default'     => true,
        ]);

        // Show or hide the galley link on journal index
        $this->addOption('galley_link_on_index', 'FieldOptions', [
            'label'       => __('plugins.themes.academic_free.galley_link_on_index') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.galley_link_on_index_desc'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_free.enable'),
                ],

            ],
            'default'     => false,
        ]);

        // Show or hide dimension badge
        $this->addOption('dimension_badge', 'FieldOptions', [
            'label'       => __('plugins.themes.academic_free.dimension_badge_label') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.dimension_badge_desc'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_free.enable'),
                ],

            ],
            'default'     => false,
        ]);

        // Show or hide the galley link on journal index
        $this->addOption('doi_on_index', 'FieldOptions', [
            'label'       => __('plugins.themes.academic_free.doi_on_index') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.doi_on_index_desc'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_free.enable'),
                ],

            ],
            'default'     => true,
        ]);

        // Enable crossmark on article page
        $this->addOption('crossmark_button', 'FieldOptions', [
            'label'       => __('plugins.themes.academic_free.crossmark_label') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.crossmark_button_desc'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_free.enable'),
                ],

            ],
            'default'     => false,
        ]);

        $this->addOption('maintenance_mode', 'FieldOptions', [
            'label'       => __('plugins.themes.academic_free.maintenance_label') . "(Pro Version Only)",
            'description' => __('plugins.themes.academic_free.maintenance_desc'),
            'options'     => [
                [
                    'value' => true,
                    'label' => __('plugins.themes.academic_free.enable'),
                ],
            ],
            'default'     => false,
        ]);

        // default style is paper
        $bootstrapTheme = $this->getOption('bootstrapTheme');
        $this->addStyle('bootstrap', 'styles/yeti.less',  array('baseUrl' => $this->_getBaseUrl()));

        $locale = AppLocale::getLocale();
        if (AppLocale::getLocaleDirection($locale) === 'rtl') {
            if (Config::getVar('general', 'enable_cdn')) {
                $this->addStyle('bootstrap-rtl', '//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css', array('baseUrl' => ''));
            } else {
                $this->addStyle('bootstrap-rtl', 'styles/bootstrap-rtl.min.css');
            }
        }

        $min     = Config::getVar('general', 'enable_minified') ? '.min' : '';
        $request =  Application::get()->getRequest();
        
        $jquery   = $request->getBaseUrl() . '/lib/pkp/lib/vendor/components/jquery/jquery' . $min . '.js';
        $jqueryUI = $request->getBaseUrl() . '/lib/pkp/lib/vendor/components/jqueryui/jquery-ui' . $min . '.js';

        
        // Use an empty `baseUrl` argument to prevent the theme from looking for
        // the files within the theme directory
        $this->addScript('jQuery', $jquery, array('baseUrl' => ''));
        $this->addScript('jQueryUI', $jqueryUI, array('baseUrl' => ''));
        $this->addScript('jQueryTagIt', $request->getBaseUrl() . '/lib/pkp/js/lib/jquery/plugins/jquery.tag-it.js', array('baseUrl' => ''));

        // Load Bootstrap
        $this->addScript('bootstrap', 'bootstrap/js/bootstrap.min.js');

        // Add navigation menu areas for this theme
        $this->addMenuArea(array('primary', 'user'));

        $this->addStyle('main', 'styles/academic_free.css');
        $this->addStyle('classy', 'styles/responsive.css');
        $this->addStyle(
			'fontAwesome',
			$request->getBaseUrl() . '/lib/pkp/styles/fontawesome/fontawesome.css',
			array('baseUrl' => '')
		);



        $headerTheme = $this->getOption('headerTheme');
        $this->addStyle('headerTheme', 'styles/header/blue.css');
    }

    /**
     * Get the display name of this plugin
     * @return string
     */
    public function getDisplayName()
    {
        return 'Academic Free Theme';
    }

    /**
     * Get the description of this plugin
     * @return string
     */
    public function getDescription()
    {
        return __('plugins.themes.academic_free.description');
    }

    private function getVersionNumber()
    {
        $versionDao = DAORegistry::getDAO('VersionDAO');
        $version    = $versionDao->getCurrentVersion();
        $data       = $version->_data;
        return $data['major'] . $data['minor'];
    }

    /**
     * Fired when the `TemplateManager::display` hook is called.
     *
     * @param string $hookname
     * @param array $args [$templateMgr, $template, $sendContentType, $charset, $output]
     */
    public function loadTemplateData($hookName, $args)
    {

        // Retrieve the TemplateManager
        $templateMgr = $args[0];

        $baseUrl = Config::getVar('general', 'base_url');

        // Attach a custom piece of data to the TemplateManager
        $myCustomData = 'This is my custom data. It could be any PHP variable.';
        $templateMgr->assign('journalVersion', $this->getVersionNumber());
        $templateMgr->assign('pluginDir', $this->getPluginPath());
        $templateMgr->assign('pluginImageDir', $baseUrl . '/' . $this->getPluginPath() . '/images/');
        $templateMgr->assign('themeTag', '<small class="pull-right" style="margin-top: 20px"> Academic Free Theme <br> by <a href="https://openjournaltheme.com"> openjournaltheme.com </a> </small>');
    }

    /**
     * Add <link rel="canonical"> to pages that exist under more than one URL:
     * article/view/ID and article/view/ID/0, issue/view/ID and issue/view/ID/0,
     * and the journal homepage with or without "/index". Without a canonical,
     * Google Scholar and Search Console list these URLs as duplicates.
     *
     * Adds nothing when the page already declares a canonical (for example the
     * core one for outdated article versions), so the tag is never doubled.
     * Galley, version and all other URLs are left untouched.
     *
     * @param string $hookName TemplateManager::display
     * @param array $args First item is the TemplateManager
     * @return bool false, so other handlers of the hook still run
     */
    public function addCanonicalLinkHeader($hookName, $args)
    {
        $templateMgr = isset($args[0]) ? $args[0] : null;
        $request = \Application::get()->getRequest();
        if (!is_object($templateMgr) || !is_object($request) || !$request->getContext()) {
            return false;
        }

        $page = (string) $request->getRequestedPage();
        $op = (string) $request->getRequestedOp();
        $path = array_map('strval', (array) $request->getRequestedArgs());
        $isIdOrIdZero = count($path) === 1 || (count($path) === 2 && $path[1] === '0');

        $url = null;
        if ($page === 'article' && $op === 'view' && $isIdOrIdZero) {
            $article = $templateMgr->getTemplateVars('article');
            if (is_object($article) && method_exists($article, 'getBestId')) {
                $url = $request->url(null, 'article', 'view', [$article->getBestId()]);
            }
        } elseif ($page === 'issue' && $op === 'view' && $isIdOrIdZero) {
            $issue = $templateMgr->getTemplateVars('issue');
            if (is_object($issue) && method_exists($issue, 'getBestIssueId')) {
                $url = $request->url(null, 'issue', 'view', [$issue->getBestIssueId()]);
            }
        } elseif (($page === '' || $page === 'index') && ($op === '' || $op === 'index') && !$path) {
            $url = $request->url($request->getContext()->getPath());
        }

        if (!$url || $this->templateHasCanonicalHeader($templateMgr)) {
            return false;
        }

        $templateMgr->addHeader(
            'canonical',
            '<link rel="canonical" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">'
        );
        return false;
    }

    /**
     * Whether a canonical link was already added to the page headers.
     * TemplateManager keeps them in a private array without a getter, so read
     * it by reflection. If that fails, report false: addHeader() is keyed by
     * name, so adding 'canonical' again replaces the same entry, never doubles.
     *
     * @param object $templateMgr
     * @return bool
     */
    private function templateHasCanonicalHeader($templateMgr)
    {
        try {
            $class = new \ReflectionClass($templateMgr);
            while ($class && !$class->hasProperty('_htmlHeaders')) {
                $class = $class->getParentClass();
            }
            if (!$class) {
                return false;
            }
            $property = $class->getProperty('_htmlHeaders');
            if (PHP_VERSION_ID < 80100) {
                $property->setAccessible(true);
            }
            foreach ((array) $property->getValue($templateMgr) as $byPriority) {
                foreach ((array) $byPriority as $named) {
                    foreach ((array) $named as $entry) {
                        $header = isset($entry['header']) ? (string) $entry['header'] : '';
                        if (preg_match('/rel\s*=\s*["\']canonical["\']/i', $header)) {
                            return true;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            return false;
        }
        return false;
    }
}
