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
 * Carousel renders a carousel bootstrap javascript component.
 *
 * For example:
 *
 * ```php
 * echo Carousel::widget([
 *     'items' => [
 *         // the item contains only the image
 *         '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-01.jpg"/>',
 *         // equivalent to the above
 *         ['content' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-02.jpg"/>'],
 *         // the item contains both the image and the caption
 *         [
 *             'content' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-03.jpg"/>',
 *             'caption' => '<h4>This is title</h4><p>This is the caption text</p>',
 *             'captionOptions' => ['class' => ['d-none', 'd-md-block']]
 *             'options' => [...],
 *         ],
 *     ]
 * ]);
 * ```
 *
 * @see https://getbootstrap.com/docs/4.5/components/carousel/
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Simon Karlen <simi.albi@outlook.com>
 */
class Carousel extends Widget
{
    /**
     * @var array|false the labels for the previous and the next control buttons.
     * If false, it means the previous and the next control buttons should not be displayed.
     */
    public $controls = [
        '<span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span>',
        '<span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span>'
    ];
    /**
     * @var bool whether carousel indicators (<ol> tag with anchors to items) should be displayed or not.
     */
    public $showIndicators = true;
    /**
     * @var array list of slides in the carousel. Each array element represents a single
     * slide with the following structure:
     *
     * ```php
     * [
     *     // required, slide content (HTML), such as an image tag
     *     'content' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-01.jpg"/>',
     *     // optional, the caption (HTML) of the slide
     *     'caption' => '<h4>This is title</h4><p>This is the caption text</p>',
     *     // optional the HTML attributes of the slide container
     *     'options' => [],
     * ]
     * ```
     */
    public $items = [];
    /**
     * @var bool Animate slides with a fade transition instead of a slide. Defaults to `false`
     */
    public $crossfade = false;
    /**
     * {@inheritdoc}
     */
    public $options = ['data-ride' => 'carousel'];


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, ['widget' => 'carousel slide']);
        if ($this->crossfade) {
            Html::addCssClass($this->options, ['animation' => 'carousel-fade']);
        }
    }

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function run()
    {
        $this->registerPlugin('carousel');
        return implode("\n", [
                Html::beginTag('div', $this->options),
                $this->renderIndicators(),
                $this->renderItems(),
                $this->renderControls(),
                Html::endTag('div')
            ]) . "\n";
    }

    /**
     * Renders carousel indicators.
     * @return string the rendering result
     */
    public function renderIndicators()
    {
        if ($this->showIndicators === false) {
            return '';
        }
        $indicators = [];
        for ($i = 0, $count = count($this->items); $i < $count; $i++) {
            $options = ['data-target' => '#' . $this->options['id'], 'data-slide-to' => $i];
            if ($i === 0) {
                Html::addCssClass($options, ['activate' => 'active']);
            }
            $indicators[] = Html::tag('li', '', $options);
        }

        return Html::tag('ol', implode("\n", $indicators), ['class' => ['carousel-indicators']]);
    }

    /**
     * Renders carousel items as specified on [[items]].
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    public function renderItems()
    {
        $items = [];
        for ($i = 0, $count = count($this->items); $i < $count; $i++) {
            $items[] = $this->renderItem($this->items[$i], $i);
        }

        return Html::tag('div', implode("\n", $items), ['class' => 'carousel-inner']);
    }

    /**
     * Renders a single carousel item
     * @param string|array $item a single item from [[items]]
     * @param int $index the item index as the first item should be set to `active`
     * @return string the rendering result
     * @throws InvalidConfigException if the item is invalid
     */
    public function renderItem($item, $index)
    {
        if (is_string($item)) {
            $content = $item;
            $caption = null;
            $options = [];
        } elseif (isset($item['content'])) {
            $content = $item['content'];
            $caption = ArrayHelper::getValue($item, 'caption');
            if ($caption !== null) {
                $captionOptions = ArrayHelper::remove($item, 'captionOptions', []);
                Html::addCssClass($captionOptions, ['widget' => 'carousel-caption']);

                $caption = Html::tag('div', $caption, $captionOptions);
            }
            $options = ArrayHelper::getValue($item, 'options', []);
        } else {
            throw new InvalidConfigException('The "content" option is required.');
        }

        Html::addCssClass($options, ['widget' => 'carousel-item']);
        if ($index === 0) {
            Html::addCssClass($options, ['activate' => 'active']);
        }

        return Html::tag('div', $content . "\n" . $caption, $options);
    }

    /**
     * Renders previous and next control buttons.
     * @throws InvalidConfigException if [[controls]] is invalid.
     */
    public function renderControls()
    {
        if (isset($this->controls[0], $this->controls[1])) {
            return Html::a($this->controls[0], '#' . $this->options['id'], [
                    'class' => 'carousel-control-prev',
                    'data-slide' => 'prev',
                    'role' => 'button'
                ]) . "\n"
                . Html::a($this->controls[1], '#' . $this->options['id'], [
                    'class' => 'carousel-control-next',
                    'data-slide' => 'next',
                    'role' => 'button'
                ]);
        } elseif ($this->controls === false) {
            return '';
        } else {
            throw new InvalidConfigException('The "controls" property must be either false or an array of two elements.');
        }
    }
}
