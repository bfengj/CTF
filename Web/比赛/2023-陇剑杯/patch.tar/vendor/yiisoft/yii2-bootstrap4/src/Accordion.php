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
 * Accordion renders an accordion bootstrap javascript component.
 *
 * For example:
 *
 * ```php
 * echo Accordion::widget([
 *     'items' => [
 *         // equivalent to the above
 *         [
 *             'label' => 'Collapsible Group Item #1',
 *             'content' => 'Anim pariatur cliche...',
 *             // open its content by default
 *             'contentOptions' => ['class' => 'in']
 *         ],
 *         // another group item
 *         [
 *             'label' => 'Collapsible Group Item #1',
 *             'content' => 'Anim pariatur cliche...',
 *             'contentOptions' => [...],
 *             'options' => [...],
 *             'expand' => true,
 *         ],
 *         // if you want to swap out .card-block with .list-group, you may use the following
 *         [
 *             'label' => 'Collapsible Group Item #1',
 *             'content' => [
 *                 'Anim pariatur cliche...',
 *                 'Anim pariatur cliche...'
 *             ],
 *             'contentOptions' => [...],
 *             'options' => [...],
 *             'footer' => 'Footer' // the footer label in list-group
 *         ],
 *     ]
 * ]);
 * ```
 *
 * @see https://getbootstrap.com/docs/4.5/components/collapse/#accordion-example
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Simon Karlen <simi.albi@outlook.com>
 */
class Accordion extends Widget
{
    /**
     * @var array list of groups in the collapse widget. Each array element represents a single
     * group with the following structure:
     *
     * - label: string, required, the group header label.
     * - encode: bool, optional, whether this label should be HTML-encoded. This param will override
     *   global `$this->encodeLabels` param.
     * - content: array|string|object, required, the content (HTML) of the group
     * - options: array, optional, the HTML attributes of the group
     * - contentOptions: optional, the HTML attributes of the group's content
     *
     * Since version 2.0.7 you may also specify this property as key-value pairs, where the key refers to the
     * `label` and the value refers to `content`. If value is a string it is interpreted as label. If it is
     * an array, it is interpreted as explained above.
     *
     * For example:
     *
     * ```php
     * echo Accordion::widget([
     *     'items' => [
     *       'Introduction' => 'This is the first collapsable menu',
     *       'Second panel' => [
     *           'content' => 'This is the second collapsable menu',
     *       ],
     *       [
     *           'label' => 'Third panel',
     *           'content' => 'This is the third collapsable menu',
     *       ],
     *   ]
     * ])
     * ```
     */
    public $items = [];
    /**
     * @var bool whether the labels for header items should be HTML-encoded.
     */
    public $encodeLabels = true;
    /**
     * @var bool whether to close other items if an item is opened. Defaults to `true` which causes an
     * accordion effect. Set this to `false` to allow keeping multiple items open at once.
     */
    public $autoCloseItems = true;
    /**
     * @var array the HTML options for the item toggle tag. Key 'tag' might be used here for the tag name specification.
     * For example:
     *
     * ```php
     * [
     *     'tag' => 'div',
     *     'class' => 'custom-toggle',
     * ]
     * ```
     *
     */
    public $itemToggleOptions = [];


    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function run()
    {
        $this->registerPlugin('collapse');
        Html::addCssClass($this->options, ['widget' => 'accordion']);
        return implode("\n", [
                Html::beginTag('div', $this->options),
                $this->renderItems(),
                Html::endTag('div')
            ]) . "\n";
    }

    /**
     * Renders collapsible items as specified on [[items]].
     * @throws InvalidConfigException if label isn't specified
     * @return string the rendering result
     */
    public function renderItems()
    {
        $items = [];
        $index = 0;
        $expanded = array_search(true, ArrayHelper::getColumn(ArrayHelper::toArray($this->items), 'expand', true));
        foreach ($this->items as $key => $item) {
            if (!is_array($item)) {
                $item = ['content' => $item];
            }
            // BC compatibility: expand first item if none is expanded
            if ($expanded === false && $index === 0) {
                $item['expand'] = true;
            }
            if (!array_key_exists('label', $item)) {
                if (is_int($key)) {
                    throw new InvalidConfigException("The 'label' option is required.");
                } else {
                    $item['label'] = $key;
                }
            }
            $header = ArrayHelper::remove($item, 'label');
            $options = ArrayHelper::getValue($item, 'options', []);
            Html::addCssClass($options, ['panel' => 'card']);
            $items[] = Html::tag('div', $this->renderItem($header, $item, $index++), $options);
        }

        return implode("\n", $items);
    }

    /**
     * Renders a single collapsible item group
     * @param string $header a label of the item group [[items]]
     * @param array $item a single item from [[items]]
     * @param int $index the item index as each item group content must have an id
     * @return string the rendering result
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function renderItem($header, $item, $index)
    {
        if (array_key_exists('content', $item)) {
            $id = $this->options['id'] . '-collapse' . $index;
            $expand = ArrayHelper::remove($item, 'expand', false);
            $options = ArrayHelper::getValue($item, 'contentOptions', []);
            $options['id'] = $id;
            Html::addCssClass($options, ['widget' => 'collapse']);

            // check if accordion expanded, if true add show class
            if ($expand) {
                Html::addCssClass($options, ['visibility' => 'show']);
            }

            if (!isset($options['aria-label'], $options['aria-labelledby'])) {
                $options['aria-labelledby'] = $options['id'] . '-heading';
            }

            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            if ($encodeLabel) {
                $header = Html::encode($header);
            }

            $itemToggleOptions = array_merge([
                'tag' => 'button',
                'type' => 'button',
                'data-toggle' => 'collapse',
                'data-target' => '#' . $options['id'],
                'aria-expanded' => $expand ? 'true' : 'false',
                'aria-controls' => $options['id']
            ], $this->itemToggleOptions);

            $itemToggleTag = ArrayHelper::remove($itemToggleOptions, 'tag', 'button');
            if ($itemToggleTag === 'a') {
                ArrayHelper::remove($itemToggleOptions, 'data-target');
                $headerToggle = Html::a($header, '#' . $id, $itemToggleOptions) . "\n";
            } else {
                Html::addCssClass($itemToggleOptions, ['feature' => 'btn-link']);
                $headerToggle = Button::widget([
                        'label' => $header,
                        'encodeLabel' => false,
                        'options' => $itemToggleOptions
                    ]) . "\n";
            }

            $header = Html::tag('h5', $headerToggle, ['class' => 'mb-0']);

            if (is_string($item['content']) || is_numeric($item['content']) || is_object($item['content'])) {
                $content = Html::tag('div', $item['content'], ['class' => 'card-body']) . "\n";
            } elseif (is_array($item['content'])) {
                $content = Html::ul($item['content'], [
                        'class' => 'list-group',
                        'itemOptions' => [
                            'class' => 'list-group-item'
                        ],
                        'encode' => false,
                    ]) . "\n";
            } else {
                throw new InvalidConfigException('The "content" option should be a string, array or object.');
            }
        } else {
            throw new InvalidConfigException('The "content" option is required.');
        }
        $group = [];

        if ($this->autoCloseItems) {
            $options['data-parent'] = '#' . $this->options['id'];
        }

        $group[] = Html::tag('div', $header, ['class' => 'card-header', 'id' => $options['id'] . '-heading']);
        $group[] = Html::beginTag('div', $options);
        $group[] = $content;
        if (isset($item['footer'])) {
            $group[] = Html::tag('div', $item['footer'], ['class' => 'card-footer']);
        }
        $group[] = Html::endTag('div');

        return implode("\n", $group);
    }
}
