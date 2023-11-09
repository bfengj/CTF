<?php
namespace UserMeta\Field;

use UserMeta\Html\Html;

/**
 * Handle page_heading field.
 *
 * @author Khaled Hossain
 * @since 1.2.0
 */
class PageHeading extends SectionHeading
{

    protected function setExtra(array $extra)
    {
        parent::setExtra($extra);
        $validOptions = [
            'form',
            'inPage',
            'isNext',
            'isPrevious',
            'currentPage'
        ];
        foreach ($validOptions as $option) {
            if (isset($extra[$option])) {
                $this->$option = $extra[$option];
            }
        }
    }

    /**
     * Replace um_field_container with um_page_segment
     */
    protected function configure_()
    {
        $this->containerAttr['id'] = 'um_page_segment_' . $this->currentPage;
        $this->containerAttr['class'] = str_replace('um_field_container', 'um_page_segment', $this->containerAttr['class']);
    }

    public function render()
    {
        global $userMeta;
        if (! $this->isQualified)
            return;

        $html = null;
        if ($this->inSection)
            $html .= "</div>";

        if ($this->isPrevious) {
            $html .= Html::input('button', null, [
                'value' => __('Previous', $userMeta->name),
                'onclick' => "umPageNavi($this->currentPage - 2, false, this)",
                'class' => "previous_button " . ! empty($this->form['button_class']) ? $this->form['button_class'] : ''
            ]);
        }
        if ($this->isNext) {
            $html .= Html::input('button', null, [
                'value' => __('Next', $userMeta->name),
                'onclick' => "umPageNavi($this->currentPage, true, this)",
                'class' => "next_button " . ! empty($this->form['button_class']) ? $this->form['button_class'] : ''
            ]);
        }
        if ($this->inPage)
            $html .= "</div>";

        $html .= '<div ' . $this->toString($this->containerAttr) . '>';
        if ($this->label)
            $html .= "<h3>{$this->label}</h3>";
        $html .= $this->renderDescription();
        if (isset($this->field['show_divider']))
            $html .= '<div class="pf_divider"></div>';

        return $html;
    }
}