<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\bootstrap4;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Tabs renders a Tab bootstrap javascript component.
 *
 * For example:
 *
 * ```php
 * echo Tabs::widget([
 *     'items' => [
 *         [
 *             'label' => 'One',
 *             'content' => 'Anim pariatur cliche...',
 *             'active' => true
 *         ],
 *         [
 *             'label' => 'Two',
 *             'content' => 'Anim pariatur cliche...',
 *             'headerOptions' => [...],
 *             'options' => ['id' => 'myveryownID'],
 *         ],
 *         [
 *             'label' => 'Example',
 *             'url' => 'http://www.example.com',
 *         ],
 *         [
 *             'label' => 'Dropdown',
 *             'items' => [
 *                  [
 *                      'label' => 'DropdownA',
 *                      'content' => 'DropdownA, Anim pariatur cliche...',
 *                  ],
 *                  [
 *                      'label' => 'DropdownB',
 *                      'content' => 'DropdownB, Anim pariatur cliche...',
 *                  ],
 *                  [
 *                      'label' => 'External Link',
 *                      'url' => 'http://www.example.com',
 *                  ],
 *             ],
 *         ],
 *     ],
 * ]);
 * ```
 *
 * @see https://getbootstrap.com/docs/4.5/components/navs/#tabs
 * @see https://getbootstrap.com/docs/4.5/components/card/#navigation
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Simon Karlen <simi.albi@outlook.com>
 */
class Tabs extends Widget
{
    /**
     * @var array list of tabs in the tabs widget. Each array element represents a single
     * tab with the following structure:
     *
     * - label: string, required, the tab header label.
     * - encode: bool, optional, whether this label should be HTML-encoded. This param will override
     *   global `$this->encodeLabels` param.
     * - headerOptions: array, optional, the HTML attributes of the tab header.
     * - linkOptions: array, optional, the HTML attributes of the tab header link tags.
     * - content: string, optional, the content (HTML) of the tab pane.
     * - url: string, optional, an external URL. When this is specified, clicking on this tab will bring
     *   the browser to this URL. This option is available since version 2.0.4.
     * - options: array, optional, the HTML attributes of the tab pane container.
     * - active: bool, optional, whether this item tab header and pane should be active. If no item is marked as
     *   'active' explicitly - the first one will be activated.
     * - visible: bool, optional, whether the item tab header and pane should be visible or not. Defaults to true.
     * - disabled: bool, optional, whether the item tab header and pane should be disabled or not. Defaults to false.
     * - items: array, optional, can be used instead of `content` to specify a dropdown items
     *   configuration array. Each item can hold three extra keys, besides the above ones:
     *     * active: bool, optional, whether the item tab header and pane should be visible or not.
     *     * content: string, required if `items` is not set. The content (HTML) of the tab pane.
     *     * options: optional, array, the HTML attributes of the tab content container.
     */
    public $items = [];
    /**
     * @var array list of HTML attributes for the item container tags. This will be overwritten
     * by the "options" set in individual [[items]]. The following special options are recognized:
     *
     * - tag: string, defaults to "div", the tag name of the item container tags.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $itemOptions = [];
    /**
     * @var array list of HTML attributes for the header container tags. This will be overwritten
     * by the "headerOptions" set in individual [[items]].
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $headerOptions = [];
    /**
     * @var array list of HTML attributes for the tab header link tags. This will be overwritten
     * by the "linkOptions" set in individual [[items]].
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $linkOptions = [];
    /**
     * @var bool whether the labels for header items should be HTML-encoded.
     */
    public $encodeLabels = true;
    /**
     * @var string specifies the Bootstrap tab styling.
     */
    public $navType = 'nav-tabs';
    /**
     * @var bool whether to render the `tab-content` container and its content. You may set this property
     * to be false so that you can manually render `tab-content` yourself in case your tab contents are complex.
     */
    public $renderTabContent = true;
    /**
     * @var array list of HTML attributes for the `tab-content` container. This will always contain the CSS class `tab-content`.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $tabContentOptions = [];
    /**
     * @var string name of a class to use for rendering dropdowns withing this widget. Defaults to [[Dropdown]].
     */
    public $dropdownClass = 'yii\bootstrap4\Dropdown';

    /**
     * @var array Tab panes (contents)
     */
    protected $panes = [];


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, ['widget' => 'nav', $this->navType]);
        Html::addCssClass($this->tabContentOptions, ['panel' => 'tab-content']);
    }

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function run()
    {
        $this->registerPlugin('tab');
        $this->prepareItems($this->items);
        return Nav::widget([
                'dropdownClass' => $this->dropdownClass,
                'options' => ArrayHelper::merge(['role' => 'tablist'], $this->options),
                'items' => $this->items,
                'encodeLabels' => $this->encodeLabels,
            ]) . $this->renderPanes($this->panes);
    }

    /**
     * Renders tab items as specified on [[items]].
     *
     * @param array $items
     * @param string $prefix
     * @throws InvalidConfigException
     */
    protected function prepareItems(&$items, $prefix = '')
    {
        if (!$this->hasActiveTab()) {
            $this->activateFirstVisibleTab();
        }

        foreach ($items as $n => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $options['id'] = ArrayHelper::getValue($options, 'id', $this->options['id'] . $prefix . '-tab' . $n);
            unset($items[$n]['options']['id']); // @see https://github.com/yiisoft/yii2-bootstrap4/issues/108#issuecomment-465219339

            if (!ArrayHelper::remove($item, 'visible', true)) {
                continue;
            }
            if (!array_key_exists('label', $item)) {
                throw new InvalidConfigException("The 'label' option is required.");
            }

            $selected = ArrayHelper::getValue($item, 'active', false);
            $disabled = ArrayHelper::getValue($item, 'disabled', false);
            $headerOptions = ArrayHelper::getValue($item, 'headerOptions', $this->headerOptions);
            if (isset($item['items'])) {
                $this->prepareItems($items[$n]['items'], '-dd' . $n);
                continue;
            } else {
                ArrayHelper::setValue($items[$n], 'options', $headerOptions);
                if (!isset($item['url'])) {
                    ArrayHelper::setValue($items[$n], 'url', '#' . $options['id']);
                    ArrayHelper::setValue($items[$n], 'linkOptions.data.toggle', 'tab');
                    ArrayHelper::setValue($items[$n], 'linkOptions.role', 'tab');
                    ArrayHelper::setValue($items[$n], 'linkOptions.aria-controls', $options['id']);
                    if (!$disabled) {
                        ArrayHelper::setValue($items[$n], 'linkOptions.aria-selected', $selected ? 'true' : 'false');
                    }
                } else {
                    continue;
                }
            }

            Html::addCssClass($options, ['widget' => 'tab-pane']);
            if ($selected) {
                Html::addCssClass($options, ['activate' => 'active']);
            }

            if ($this->renderTabContent) {
                $tag = ArrayHelper::remove($options, 'tag', 'div');
                $this->panes[] = Html::tag($tag, isset($item['content']) ? $item['content'] : '', $options);
            }
        }
    }

    /**
     * @return bool if there's active tab defined
     */
    protected function hasActiveTab()
    {
        foreach ($this->items as $item) {
            if (isset($item['active']) && $item['active'] === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sets the first visible tab as active.
     *
     * This method activates the first tab that is visible and
     * not explicitly set to inactive (`'active' => false`).
     */
    protected function activateFirstVisibleTab()
    {
        foreach ($this->items as $i => $item) {
            $active = ArrayHelper::getValue($item, 'active', null);
            $visible = ArrayHelper::getValue($item, 'visible', true);
            $disabled = ArrayHelper::getValue($item, 'disabled', false);
            if ($visible && $active !== false && $disabled !== true) {
                $this->items[$i]['active'] = true;
                return;
            }
        }
    }

    /**
     * Renders tab panes.
     *
     * @param array $panes
     * @return string the rendering result.
     */
    public function renderPanes($panes)
    {
        return $this->renderTabContent ? "\n" . Html::tag('div', implode("\n", $panes), $this->tabContentOptions) : '';
    }
}
