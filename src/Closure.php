<?php
/**
 * Closure for Craft CMS
 *
 * Allows you to use arrow function closures in Twig
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\closure;

use Craft;
use craft\console\Application as CraftConsoleApp;
use craft\web\Application as CraftWebApp;
use craft\web\View;
use nystudio107\closure\helpers\Reflection as ReflectionHelper;
use nystudio107\closure\twig\ClosureExpressionParser;
use ReflectionException;
use Twig\Parser;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Module;

/**
 * @author    nystudio107
 * @package   Closure
 * @since     1.0.0
 */
class Closure extends Module implements BootstrapInterface
{
    // Constants
    // =========================================================================

    const ID = 'closure';

    // Protected Static Properties
    // =========================================================================

    protected bool $closureAdded = false;

    // Public Methods
    // =========================================================================

    /**
     * @inerhitdoc
     */
    public function __construct($id = self::ID, $parent = null, $config = [])
    {
        /**
         * Explicitly set the $id parameter, as earlier versions of Yii2 look for a
         * default parameter, and depend on $id being explicitly set:
         * https://github.com/yiisoft/yii2/blob/f3d1534125c9c3dfe8fa65c28a4be5baa822e721/framework/di/Container.php#L436-L448
         */
        parent::__construct($id, $parent, $config);
    }

    /**
     * @inerhitDoc
     */
    public function bootstrap($app)
    {
        // Only bootstrap if this is a CraftWebApp
        if (!($app instanceof CraftWebApp || $app instanceof CraftConsoleApp)) {
            return;
        }
        // Set the instance of this module class, so we can later access it with `Closure::getInstance()`
        static::setInstance($this);
        // Configure our module
        $this->configureModule();
        // Register our event handlers
        $this->registerEventHandlers();
        Craft::info('Closure module bootstrapped', __METHOD__);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Configure our module
     *
     * @return void
     */
    protected function configureModule(): void
    {
        // Register our module
        Craft::$app->setModule($this->id, $this);
    }

    /**
     * Registers our event handlers
     *
     * @return void
     */
    protected function registerEventHandlers(): void
    {
        // Handler: Plugins::EVENT_AFTER_LOAD_PLUGINS
        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            fn() => $this->addClosure()
        );
    }

    /**
     * Add our ClosureExpressionParser to default $allowArrow = true to let
     * arrow function closures work outside of Twig filter contexts
     *
     * @return void
     */
    protected function addClosure(): void
    {
        if ($this->closureAdded) {
            return;
        }
        $twig = Craft::$app->getView()->getTwig();
        // Get the parser object used by Twig
        try {
            $parserReflection = ReflectionHelper::getReflectionProperty($twig, 'parser');
        } catch (ReflectionException $e) {
            Craft::error($e->getMessage(), __METHOD__);
            return;
        }
        $parserReflection->setAccessible(true);
        $parser = $parserReflection->getValue($twig);
        if ($parser === null) {
            $parser = new Parser($twig);
            $parserReflection->setValue($twig, $parser);
        }
        // Create the ClosureExpressionParser object and set the parser to use it
        try {
            $expressionParserReflection = ReflectionHelper::getReflectionProperty($parser, 'expressionParser');
        } catch (ReflectionException $e) {
            Craft::error($e->getMessage(), __METHOD__);
            return;
        }
        $expressionParserReflection->setAccessible(true);
        $expressionParser = new ClosureExpressionParser($parser, $twig);
        $expressionParserReflection->setValue($parser, $expressionParser);
        // Indicate that we've gotten closure
        $this->closureAdded = true;
    }
}
